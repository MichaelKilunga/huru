<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeEntry;
use App\Services\KnowledgeRetriever;
use App\Services\TopicClassifier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KnowledgeController extends Controller
{
    public function index(Request $request)
    {
        $query = KnowledgeEntry::query()->latest();

        if ($request->filled('q')) {
            $q = '%' . $request->string('q') . '%';
            $query->where(fn ($w) => $w->where('title', 'like', $q)->orWhere('content', 'like', $q));
        }
        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }
        if ($request->filled('language')) {
            $query->where('language', $request->string('language'));
        }

        $entries = $query->paginate(20)->withQueryString();
        $counts = KnowledgeEntry::query()->selectRaw('category, count(*) as total')->groupBy('category')->pluck('total', 'category');

        return view('admin.knowledge.index', compact('entries', 'counts'));
    }

    public function create()
    {
        return view('admin.knowledge.form', ['entry' => new KnowledgeEntry(['language' => 'sw', 'category' => 'general', 'is_active' => true])]);
    }

    public function store(Request $request, KnowledgeRetriever $retriever)
    {
        $data = $this->validated($request);
        $data['keywords'] = $this->keywords($data, $retriever);
        KnowledgeEntry::query()->create($data);

        return redirect()->route('admin.knowledge.index')->with('success', 'Knowledge entry created.');
    }

    public function edit(KnowledgeEntry $knowledge)
    {
        return view('admin.knowledge.form', ['entry' => $knowledge]);
    }

    public function update(Request $request, KnowledgeEntry $knowledge, KnowledgeRetriever $retriever)
    {
        $data = $this->validated($request);
        $data['keywords'] = $this->keywords($data, $retriever);
        $knowledge->update($data);

        return redirect()->route('admin.knowledge.index')->with('success', 'Knowledge entry updated.');
    }

    public function toggle(KnowledgeEntry $knowledge)
    {
        $knowledge->update(['is_active' => ! $knowledge->is_active]);

        return back()->with('success', 'Entry ' . ($knowledge->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function destroy(KnowledgeEntry $knowledge)
    {
        $knowledge->delete();

        return back()->with('success', 'Knowledge entry deleted.');
    }

    /**
     * Bulk import. CSV columns: title, content, category, language, keywords (comma separated), source, summary.
     * JSON: an array of objects with the same keys (keywords may be an array).
     */
    public function import(Request $request, KnowledgeRetriever $retriever)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,json|max:5120',
            'default_category' => 'required|in:' . implode(',', TopicClassifier::keys()),
            'default_language' => 'required|in:sw,en',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $rows = [];

        if ($ext === 'json') {
            $decoded = json_decode(file_get_contents($file->getRealPath()), true);
            if (is_array($decoded)) {
                $rows = isset($decoded['title']) ? [$decoded] : $decoded;
            }
        } else {
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle);
            if ($header) {
                $header = array_map(fn ($h) => Str::snake(Str::lower(trim((string) $h))), $header);
                while (($row = fgetcsv($handle)) !== false) {
                    if (count(array_filter($row)) === 0) {
                        continue;
                    }
                    $rows[] = array_combine($header, array_pad($row, count($header), null));
                }
            }
            fclose($handle);
        }

        $count = 0;
        foreach ($rows as $row) {
            if (empty($row['title']) || empty($row['content'])) {
                continue;
            }
            $category = in_array($row['category'] ?? '', TopicClassifier::keys(), true) ? $row['category'] : $request->input('default_category');
            $language = in_array($row['language'] ?? '', ['sw', 'en'], true) ? $row['language'] : $request->input('default_language');
            $keywords = $row['keywords'] ?? [];
            if (is_string($keywords)) {
                $keywords = array_filter(array_map('trim', explode(',', $keywords)));
            }
            $data = [
                'title' => Str::limit(trim($row['title']), 250, ''),
                'content' => trim($row['content']),
                'summary' => $row['summary'] ?? Str::limit(trim($row['content']), 160),
                'category' => $category,
                'language' => $language,
                'source' => $row['source'] ?? null,
                'is_active' => true,
            ];
            $data['keywords'] = $this->keywords($data + ['keywords_text' => implode(',', $keywords)], $retriever);

            KnowledgeEntry::query()->updateOrCreate(['title' => $data['title'], 'language' => $language], $data);
            $count++;
        }

        return back()->with('success', "Imported {$count} knowledge entries.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:250',
            'content' => 'required|string|max:20000',
            'summary' => 'nullable|string|max:500',
            'category' => 'required|in:' . implode(',', TopicClassifier::keys()),
            'language' => 'required|in:sw,en',
            'keywords_text' => 'nullable|string|max:1000',
            'source' => 'nullable|string|max:250',
            'is_active' => 'sometimes|boolean',
        ]) + ['is_active' => $request->boolean('is_active')];
    }

    /** Explicit keywords plus title tokens, so retrieval works even when the admin leaves keywords empty. */
    private function keywords(array $data, KnowledgeRetriever $retriever): array
    {
        $explicit = array_filter(array_map(fn ($k) => Str::lower(trim($k)), explode(',', (string) ($data['keywords_text'] ?? ''))));
        $fromTitle = $retriever->tokens($data['title']);

        return array_values(array_unique(array_merge($explicit, $fromTitle)));
    }
}
