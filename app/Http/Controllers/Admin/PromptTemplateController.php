<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromptTemplate;
use App\Services\TopicClassifier;
use Illuminate\Http\Request;

class PromptTemplateController extends Controller
{
    public function index()
    {
        $templates = PromptTemplate::query()->orderBy('category')->orderBy('language')->get();

        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.form', ['template' => new PromptTemplate(['category' => 'general', 'language' => 'sw', 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        PromptTemplate::query()->create($data);
        $this->ensureSingleActive($data);

        return redirect()->route('admin.templates.index')->with('success', 'Template created.');
    }

    public function edit(PromptTemplate $template)
    {
        return view('admin.templates.form', compact('template'));
    }

    public function update(Request $request, PromptTemplate $template)
    {
        $data = $this->validated($request, $template);
        $template->update($data);
        $this->ensureSingleActive($data, $template->id);

        return redirect()->route('admin.templates.index')->with('success', 'Template updated.');
    }

    public function toggle(PromptTemplate $template)
    {
        $template->update(['is_active' => ! $template->is_active]);
        if ($template->is_active) {
            $this->ensureSingleActive($template->only('category', 'language'), $template->id);
        }

        return back()->with('success', 'Template ' . ($template->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function destroy(PromptTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.templates.index')->with('success', 'Template deleted.');
    }

    private function validated(Request $request, ?PromptTemplate $existing = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:80|unique:prompt_templates,name' . ($existing ? ',' . $existing->id : ''),
            'category' => 'required|in:' . implode(',', TopicClassifier::keys()),
            'language' => 'required|in:sw,en',
            'template' => 'required|string|min:20|max:4000',
            'is_active' => 'sometimes|boolean',
        ]) + ['is_active' => $request->boolean('is_active')];
    }

    /** Only one active persona per (category, language). */
    private function ensureSingleActive(array $data, ?int $keepId = null): void
    {
        if (empty($data['is_active']) && $keepId === null) {
            return;
        }
        $keep = $keepId ?? PromptTemplate::query()->where('name', $data['name'])->value('id');
        PromptTemplate::query()
            ->where('category', $data['category'])
            ->where('language', $data['language'])
            ->where('id', '!=', $keep)
            ->update(['is_active' => false]);
        cache()->forget('huru.templates.active');
    }
}
