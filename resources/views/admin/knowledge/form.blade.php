@extends('layouts.admin', ['title' => $entry->exists ? 'Edit knowledge entry' : 'New knowledge entry'])

@section('content')
<div class="card" style="max-width:860px">
    <form method="POST" action="{{ $entry->exists ? route('admin.knowledge.update', $entry) : route('admin.knowledge.store') }}">
        @csrf
        @if($entry->exists) @method('PUT') @endif
        <div class="field"><label>Title</label><input type="text" name="title" value="{{ old('title', $entry->title) }}" required maxlength="250"><div class="hint">Specific and searchable, e.g. "Kitambulisho cha Taifa (NIDA): jinsi ya kujisajili".</div></div>
        <div class="grid g2">
            <div class="field"><label>Topic</label><select name="category">@foreach(\App\Services\TopicClassifier::CATEGORIES as $k => $l)<option value="{{ $k }}" @selected(old('category', $entry->category) === $k)>{{ $l['en'] }} / {{ $l['sw'] }}</option>@endforeach</select></div>
            <div class="field"><label>Language of this entry</label><select name="language"><option value="sw" @selected(old('language', $entry->language) === 'sw')>Swahili</option><option value="en" @selected(old('language', $entry->language) === 'en')>English</option></select></div>
        </div>
        <div class="field"><label>Content</label><textarea name="content" rows="10" required>{{ old('content', $entry->content) }}</textarea><div class="hint">Plain facts with steps, offices and figures. The engine quotes this verbatim as verified knowledge, so only write what you can stand behind.</div></div>
        <div class="field"><label>Summary (optional)</label><input type="text" name="summary" value="{{ old('summary', $entry->summary) }}" maxlength="500"></div>
        <div class="field"><label>Keywords (comma separated)</label><input type="text" name="keywords_text" value="{{ old('keywords_text', implode(', ', (array) $entry->keywords)) }}"><div class="hint">Words citizens would use, in both languages. Title words are added automatically.</div></div>
        <div class="field"><label>Source (optional)</label><input type="text" name="source" value="{{ old('source', $entry->source) }}" maxlength="250" placeholder="e.g. Sheria ya Ardhi Sura 113; nida.go.tz"></div>
        <div class="field"><label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $entry->is_active))> Active</label></div>
        <button class="btn btn-p" type="submit">Save</button>
        <a class="btn" href="{{ route('admin.knowledge.index') }}">Cancel</a>
    </form>
</div>
@endsection
