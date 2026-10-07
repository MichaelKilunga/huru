<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\Curriculum;
use Illuminate\Support\Str;

class CurriculumController extends Controller
{
    public function index()
    {
        $curriculums = Curriculum::latest()->paginate(15);
        return view('admin.curriculum.index', compact('curriculums'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'doc_file' => 'required|file|mimes:csv,txt,json',
            'language' => 'required|in:en,sw'
        ]);

        $file = $request->file('doc_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $count = 0;

        if ($extension === 'csv') {
            $handle = fopen($file->getRealPath(), 'r');
            // Skip header
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 2) continue;

                $title = $row[0];
                $content = $row[1];
                $summary = $row[2] ?? null;
                $tags = $row[3] ?? null;

                $keywords = array_filter(explode(' ', Str::lower(preg_replace('/[^a-z0-9 ]/i', '', $title))));
                if ($tags) {
                    $keywords = array_unique(array_merge($keywords, array_map('trim', explode(',', Str::lower($tags)))));
                }

                Curriculum::create([
                    'title' => $title,
                    'content' => $content,
                    'summary' => $summary,
                    'tags' => $tags,
                    'keywords' => $keywords,
                    'language' => $request->language,
                    'is_active' => true,
                ]);
                $count++;
            }
            fclose($handle);
        } elseif ($extension === 'json') {
            $jsonContent = file_get_contents($file->getRealPath());
            $data = json_decode($jsonContent, true);
            if (is_array($data)) {
                // If it's a single object, wrap it
                if (isset($data['title']) && isset($data['content'])) {
                    $data = [$data];
                }
                foreach ($data as $item) {
                    if (!isset($item['title']) || !isset($item['content'])) continue;

                    $title = $item['title'];
                    $content = $item['content'];
                    $summary = $item['summary'] ?? null;
                    $tags = $item['tags'] ?? null;

                    $keywords = array_filter(explode(' ', Str::lower(preg_replace('/[^a-z0-9 ]/i', '', $title))));
                    if ($tags) {
                        $keywords = array_unique(array_merge($keywords, array_map('trim', explode(',', Str::lower($tags)))));
                    }

                    Curriculum::create([
                        'title' => $title,
                        'content' => $content,
                        'summary' => $summary,
                        'tags' => $tags,
                        'keywords' => $keywords,
                        'language' => $request->language,
                        'is_active' => true,
                    ]);
                    $count++;
                }
            }
        } else {
            // TXT file - Parse as a single document, using filename as title, and paragraphs as content blocks
            $txtContent = file_get_contents($file->getRealPath());
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $title = ucwords(str_replace(['-', '_'], ' ', $filename));

            $sections = preg_split('/\n\s*\n/', $txtContent);
            $sections = array_map('trim', $sections);
            $sections = array_filter($sections);

            if (count($sections) > 0) {
                foreach ($sections as $index => $section) {
                    $sectionTitle = count($sections) > 1 ? "$title - Part " . ($index + 1) : $title;
                    $keywords = array_filter(explode(' ', Str::lower(preg_replace('/[^a-z0-9 ]/i', '', $sectionTitle))));

                    Curriculum::create([
                        'title' => $sectionTitle,
                        'content' => $section,
                        'summary' => Str::limit($section, 150),
                        'tags' => 'imported,txt',
                        'keywords' => $keywords,
                        'language' => $request->language,
                        'is_active' => true,
                    ]);
                    $count++;
                }
            }
        }

        Cache::forget("curriculum_active_{$request->language}");

        return redirect()->back()->with('success', "Imported $count legal database entries.");
    }

    public function destroy(Curriculum $curriculum)
    {
        Cache::forget("curriculum_active_{$curriculum->language}");
        $curriculum->delete();
        return redirect()->back()->with('success', 'Legal database entry deleted.');
    }
}
