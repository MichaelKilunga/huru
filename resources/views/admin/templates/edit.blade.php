@extends("layouts.admin", ["title" => "Edit AI Prompt Template"])

@section("content")
    <div style="max-width: 600px; margin: 0 auto;">
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <div class="card-title">✏️ Edit Template: {{ $template->name }}</div>
                <a href="{{ route('admin.templates.index') }}" class="btn btn-sm" style="background:var(--gray-700); color:white; text-decoration:none; border:1px solid rgba(255,255,255,0.1);">Back to Templates</a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.templates.update', $template) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Template Name</label>
                        <input type="text" name="name" class="form-input" placeholder="e.g., Default Swahili" value="{{ old('name', $template->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prompt Template</label>
                        <textarea name="template" class="form-textarea" rows="12"
                            placeholder="You are a teacher. Use context: {context}. User says: {user_input}" required>{{ old('template', $template->template) }}</textarea>
                        <div class="form-hint">Placeholders: <code style="background:rgba(255,255,255,0.08); padding:.1rem .3rem; border-radius:4px;">{context}</code>, <code style="background:rgba(255,255,255,0.08); padding:.1rem .3rem; border-radius:4px;">{user_input}</code></div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div class="form-group">
                            <label class="form-label">Temperature</label>
                            <input type="number" name="temperature" class="form-input" step="0.1" min="0" max="2" value="{{ old('temperature', $template->temperature) }}">
                            <div class="form-hint">0 = strict, 2 = creative</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Max Tokens</label>
                            <input type="number" name="max_tokens" class="form-input" value="{{ old('max_tokens', $template->max_tokens) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tone</label>
                        <input type="text" name="tone" class="form-input" value="{{ old('tone', $template->tone) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Language</label>
                        <select name="language" class="form-select">
                            <option value="sw" {{ old('language', $template->language) === 'sw' ? 'selected' : '' }}>🇹🇿 Swahili</option>
                            <option value="en" {{ old('language', $template->language) === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">💾 Update Template</button>
                </form>
            </div>
        </div>
    </div>
@endsection
