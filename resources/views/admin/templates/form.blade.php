@extends('layouts.admin', ['title' => $template->exists ? 'Edit persona' : 'New persona'])

@section('content')
<div class="card" style="max-width:860px">
    <form method="POST" action="{{ $template->exists ? route('admin.templates.update', $template) : route('admin.templates.store') }}">
        @csrf
        @if($template->exists) @method('PUT') @endif
        <div class="field"><label>Name</label><input type="text" name="name" value="{{ old('name', $template->name) }}" required maxlength="80"></div>
        <div class="grid g2">
            <div class="field"><label>Topic</label><select name="category">@foreach(\App\Services\TopicClassifier::CATEGORIES as $k => $l)<option value="{{ $k }}" @selected(old('category', $template->category) === $k)>{{ $l['en'] }}</option>@endforeach</select></div>
            <div class="field"><label>Language</label><select name="language"><option value="sw" @selected(old('language', $template->language) === 'sw')>Swahili</option><option value="en" @selected(old('language', $template->language) === 'en')>English</option></select></div>
        </div>
        <div class="field"><label>Persona</label><textarea name="template" rows="8" required>{{ old('template', $template->template) }}</textarea><div class="hint">Describe who the assistant is and how it should behave for this topic. Country context, knowledge, contacts and output rules are appended automatically. Use {app_name} for the service name.</div></div>
        <div class="field"><label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $template->is_active))> Active (deactivates other personas for the same topic and language)</label></div>
        <button class="btn btn-p" type="submit">Save</button>
        <a class="btn" href="{{ route('admin.templates.index') }}">Cancel</a>
    </form>
</div>
@endsection
