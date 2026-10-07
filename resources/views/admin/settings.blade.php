@extends("layouts.admin", ["title" => "System Settings"])

@section("content")
    <div style="max-width:680px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">⚙️ Global Application Settings</div>
                <div class="card-subtitle">Controls AI behaviour and platform configuration</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Primary SMS Language</label>
                        <select name="primary_language" class="form-select">
                            <option value="auto" {{ ($settings['primary_language'] ?? 'sw') == 'auto' ? 'selected' : '' }}>
                                🌍 Auto Detect (Dynamic)
                            </option>
                            <option value="sw" {{ ($settings['primary_language'] ?? 'sw') == 'sw' ? 'selected' : '' }}>
                                🇹🇿 Swahili (Kiswahili)
                            </option>
                            <option value="en" {{ ($settings['primary_language'] ?? 'sw') == 'en' ? 'selected' : '' }}>
                                🇬🇧 English
                            </option>
                        </select>
                        <div class="form-hint">Auto Detect will dynamically determine the language from user's SMS. Selecting a specific language enforces it.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bot Name (Branding)</label>
                        <input type="text" name="bot_name" class="form-input"
                               value="{{ $settings['bot_name'] ?? env('APP_NAME') }}"
                               placeholder="HuruLearn">
                        <div class="form-hint">Used in admin branding only. Does not affect SMS responses.</div>
                    </div>

                    <div style="height:1px; background:rgba(255,255,255,0.07); margin:1.5rem 0;"></div>

                    <div class="form-group">
                        <label class="form-label" style="display:flex; align-items:center; gap:0.5rem;">
                            <span style="font-size:1.2rem;">🔌</span> AI Engine Status
                        </label>
                        <select name="ai_enabled" class="form-select">
                            <option value="1" {{ ($settings['ai_enabled'] ?? '1') == '1' ? 'selected' : '' }}>🟢 Active (Processing SMS)</option>
                            <option value="0" {{ ($settings['ai_enabled'] ?? '1') == '0' ? 'selected' : '' }}>🔴 Paused (Maintenance Mode)</option>
                        </select>
                        <div class="form-hint">Turn off to temporarily stop the AI from replying to incoming SMS. Useful during maintenance or budget limits.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Maintenance Auto-Reply Message</label>
                        <textarea name="ai_maintenance_message" class="form-textarea" rows="2">{{ $settings['ai_maintenance_message'] ?? 'Samahani, mfumo wetu unafanyiwa matengenezo. Tafadhali jaribu tena baadaye. (System is under maintenance, please try later.)' }}</textarea>
                    </div>

                    <div style="height:1px; background:rgba(255,255,255,0.07); margin:1.5rem 0;"></div>

                    <div class="form-group">
                        <label class="form-label" style="display:flex; align-items:center; gap:0.5rem;">
                            <span style="font-size:1.2rem;">📝</span> Max Response Words (Prompt Instruction)
                        </label>
                        <input type="number" name="ai_max_words" class="form-input"
                               value="{{ $settings['ai_max_words'] ?? 320 }}"
                               min="10" max="1000">
                        <div class="form-hint">Instructs the AI to keep answers within this word count (e.g. 160 words). Influences prompt constraints.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="display:flex; align-items:center; gap:0.5rem;">
                            <span style="font-size:1.2rem;">💬</span> Web Chat Message History Limit
                        </label>
                        <input type="number" name="web_chat_limit" class="form-input"
                                value="{{ $settings['web_chat_limit'] ?? 4 }}"
                                min="1" max="50">
                        <div class="form-hint">Controls how many previous messages are loaded in the web chatbot interface for context and display.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="display:flex; align-items:center; gap:0.5rem;">
                            <span style="font-size:1.2rem;">⚙️</span> Max Response Tokens (API Cap)
                        </label>
                        <input type="number" name="ai_max_tokens" class="form-input"
                               value="{{ $settings['ai_max_tokens'] ?? 8000 }}"
                               min="500" max="10000">
                        <div class="form-hint">Hard limit on the number of tokens the AI API will generate (Gemini Flash Lite is highly efficient, 2000-4000 is recommended).</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="display:flex; align-items:center; gap:0.5rem;">
                            <span style="font-size:1.2rem;">🌡️</span> AI Temperature
                        </label>
                        <input type="number" step="0.1" name="ai_temperature" class="form-input"
                               value="{{ $settings['ai_temperature'] ?? 0.7 }}"
                               min="0.0" max="2.0">
                        <div class="form-hint">Controls randomness: 0.0 is strict/focused, 1.0+ is more creative. Default is 0.7.</div>
                    </div>

                    <div style="height:1px; background:rgba(255,255,255,0.07); margin:1.5rem 0;"></div>

                    <div style="display:flex; justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary">💾 Save Configuration</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card" style="margin-top:1.5rem; border-color:rgba(59,130,246,0.2);">
            <div class="card-header" style="border-color:rgba(59,130,246,0.15);">
                <div class="card-title" style="color:var(--blue-light);">ℹ️ How Settings Work</div>
            </div>
            <div class="card-body">
                <ul style="list-style:none; display:flex; flex-direction:column; gap:.8rem;">
                    <li style="display:flex; gap:.75rem; align-items:flex-start; font-size:.875rem; color:var(--gray-400);">
                        <span style="color:var(--amber); font-weight:700; flex-shrink:0;">✦</span>
                        Primary language affects keyword matching priority across the AI engine.
                    </li>
                    <li style="display:flex; gap:.75rem; align-items:flex-start; font-size:.875rem; color:var(--gray-400);">
                        <span style="color:var(--amber); font-weight:700; flex-shrink:0;">✦</span>
                        AI Configuration alters both API limits (Tokens/Temperature) and strict prompt rules (Max Words) instantly.
                    </li>
                    <li style="display:flex; gap:.75rem; align-items:flex-start; font-size:.875rem; color:var(--gray-400);">
                        <span style="color:var(--amber); font-weight:700; flex-shrink:0;">✦</span>
                        Legal and civic content for other languages is still searchable when keywords match.
                    </li>
                    <li style="display:flex; gap:.75rem; align-items:flex-start; font-size:.875rem; color:var(--gray-400);">
                        <span style="color:var(--amber); font-weight:700; flex-shrink:0;">✦</span>
                        If AI is paused, incoming SMS will receive the maintenance message without contacting the AI API point.
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
