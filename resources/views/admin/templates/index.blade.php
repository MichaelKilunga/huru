@extends("layouts.admin", ["title" => "AI Prompt Templates"])

@section("content")
    <div style="display:grid; grid-template-columns:340px 1fr; gap:1.5rem; align-items:start;">

        <!-- Create Template -->
        <div class="card" style="position:sticky; top:80px;">
            <div class="card-header">
                <div class="card-title">🤖 Create Template</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.templates.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Template Name</label>
                        <input type="text" name="name" class="form-input" placeholder="e.g., Default Swahili" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prompt Template</label>
                        <textarea name="template" class="form-textarea" rows="8"
                            placeholder="You are a teacher. Use context: {context}. User says: {user_input}" required></textarea>
                        <div class="form-hint">Placeholders: <code style="background:rgba(255,255,255,0.08); padding:.1rem .3rem; border-radius:4px;">{context}</code>, <code style="background:rgba(255,255,255,0.08); padding:.1rem .3rem; border-radius:4px;">{user_input}</code></div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div class="form-group">
                            <label class="form-label">Temperature</label>
                            <input type="number" name="temperature" class="form-input" step="0.1" min="0" max="2" value="0.7">
                            <div class="form-hint">0 = strict, 2 = creative</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Max Tokens</label>
                            <input type="number" name="max_tokens" class="form-input" value="200">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tone</label>
                        <input type="text" name="tone" class="form-input" value="educational">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Language</label>
                        <select name="language" class="form-select">
                            <option value="sw">🇹🇿 Swahili</option>
                            <option value="en">🇬🇧 English</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">💾 Save Template</button>
                </form>
            </div>
        </div>

        <!-- Templates List -->
        <div style="display:flex; flex-direction:column; gap:1.2rem;">
            @forelse($templates as $tpl)
                <div class="card" style="border-left:3px solid {{ $tpl->is_active ? 'var(--teal)' : 'rgba(255,255,255,0.1)' }};">
                    <div class="card-header">
                        <div>
                            <div style="font-family:'Space Grotesk',sans-serif; font-size:1rem; font-weight:700; color:var(--gray-100);">{{ $tpl->name }}</div>
                            <div style="display:flex; gap:.5rem; margin-top:.4rem; flex-wrap:wrap;">
                                @if($tpl->language === 'sw')
                                    <span class="badge badge-amber">🇹🇿 Swahili</span>
                                @else
                                    <span class="badge badge-blue">🇬🇧 English</span>
                                @endif
                                <span class="badge badge-gray">{{ $tpl->tone }}</span>
                                <span class="badge badge-gray">T={{ $tpl->temperature }}</span>
                                <span class="badge badge-gray">{{ $tpl->max_tokens }} tokens</span>
                                @if($tpl->is_active)
                                    <span class="badge badge-green">✓ Active</span>
                                @endif
                            </div>
                        </div>
                        <div style="display:flex; gap:.5rem;">
                            <a href="{{ route('admin.templates.edit', $tpl) }}" class="btn btn-sm" style="background:var(--gray-700); color:white; border:1px solid rgba(255,255,255,0.1); text-decoration:none;">✏️ Edit</a>
                            <form action="{{ route('admin.templates.destroy', $tpl) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this template?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:var(--red); color:white; border:none;">🗑️ Delete</button>
                            </form>
                            <form action="{{ route('admin.templates.toggle', $tpl) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $tpl->is_active ? 'btn-danger' : 'btn-blue' }}">
                                    {{ $tpl->is_active ? '⏸ Deactivate' : '▶ Activate' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <pre style="background:rgba(0,0,0,0.3); border:1px solid rgba(255,255,255,0.07); border-radius:10px; padding:1rem; font-size:.78rem; color:var(--gray-400); white-space:pre-wrap; font-family:'Courier New',monospace; line-height:1.7; overflow-x:auto;">{{ $tpl->template }}</pre>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div style="padding:3rem; text-align:center; color:var(--gray-600);">
                        <div style="font-size:3rem; margin-bottom:1rem;">🤖</div>
                        <div style="font-size:1rem; font-weight:600; margin-bottom:.5rem;">No templates yet</div>
                        <div style="font-size:.875rem;">Create your first AI teacher prompt to get started.</div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
