<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Web Chat – {{ $settings['app_name'] }}</title>
    <meta name="description" content="Uliza swali lolote la maisha Tanzania na upate jibu la haraka kwa Kiswahili au Kiingereza. Bila intaneti tuma {{ $settings['sms_keyword'] }} kwenda {{ $settings['sms_shortcode'] }}.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/chat') }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/logo.svg">
    <meta name="theme-color" content="#15803d">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--green:#15803d;--green-dark:#166534;--green-bg:#f0fdf4;--green-border:#bbf7d0;--blue:#1d4ed8;--blue-dark:#1e40af;--blue-bg:#eff6ff;--bg:#f8fafc;--card:#fff;--text:#0f172a;--muted:#475569;--border:#cbd5e1;--danger:#b91c1c}
        *{margin:0;padding:0;box-sizing:border-box}
        html,body{height:100%}
        body{font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text);display:flex;align-items:stretch;justify-content:center}
        .app{width:100%;max-width:880px;height:100dvh;display:flex;flex-direction:column;background:var(--card);border-left:1px solid var(--border);border-right:1px solid var(--border)}
        .screen{display:none;flex:1;flex-direction:column;min-height:0}
        .screen.active{display:flex}
        .hidden{display:none!important}
        /* auth */
        .auth{flex:1;overflow:auto;padding:28px 20px;display:flex;flex-direction:column;justify-content:center;max-width:520px;width:100%;margin:0 auto}
        .logo{width:52px;height:52px;border-radius:12px;background:var(--green);color:#fff;font-weight:800;font-size:1.3rem;display:flex;align-items:center;justify-content:center}
        .auth h1{font-size:1.5rem;margin:14px 0 6px;font-weight:800}
        .auth p.lead{color:var(--muted);font-size:.95rem}
        .sms-banner{background:var(--green-bg);border:1px solid var(--green-border);border-radius:10px;padding:12px 14px;margin:18px 0;font-size:.9rem}
        .sms-banner b{color:var(--green-dark)}
        .sms-badge{float:right;background:var(--green);color:#fff;font-weight:800;padding:2px 10px;border-radius:999px;font-size:.8rem}
        label{display:block;font-size:.85rem;font-weight:600;margin-bottom:6px;color:var(--muted)}
        input,select,textarea{width:100%;padding:12px 14px;border:1.5px solid var(--border);border-radius:8px;font:inherit;font-size:1rem;background:#fff}
        input:focus,select:focus,textarea:focus{outline:none;border-color:var(--green)}
        .row{display:flex;gap:10px}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 16px;border-radius:8px;border:2px solid transparent;font:inherit;font-weight:700;cursor:pointer;font-size:.95rem}
        .btn-green{background:var(--green);color:#fff;width:100%}
        .btn-green:hover{background:var(--green-dark)}
        .btn-green:disabled{opacity:.6;cursor:wait}
        .btn-ghost{background:#fff;border-color:var(--border);color:var(--muted)}
        .btn-link{background:none;border:none;color:var(--blue);font-weight:600;cursor:pointer;font:inherit;padding:6px 0}
        .err{background:#fef2f2;border:1px solid #fecaca;color:var(--danger);padding:10px 12px;border-radius:8px;font-size:.88rem;font-weight:600;margin:10px 0}
        .ok{background:var(--green-bg);border:1px solid var(--green-border);color:var(--green-dark);padding:10px 12px;border-radius:8px;font-size:.88rem;font-weight:600;margin:10px 0}
        .small{font-size:.8rem;color:var(--muted);margin-top:10px}
        /* chat header */
        .hdr{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-bottom:1px solid var(--border);gap:10px;flex-wrap:wrap}
        .hdr .who{display:flex;align-items:center;gap:10px}
        .hdr .logo{width:38px;height:38px;font-size:1rem;border-radius:9px}
        .hdr h2{font-size:1rem;font-weight:800}
        .hdr .st{font-size:.75rem;color:var(--green-dark);font-weight:600}
        .hdr .actions{display:flex;gap:6px;align-items:center;flex-wrap:wrap}
        .tag{font-size:.75rem;font-weight:700;padding:5px 10px;border-radius:999px;background:var(--green-bg);color:var(--green-dark);border:1px solid var(--green-border)}
        .ibtn{background:#fff;border:1px solid var(--border);border-radius:8px;padding:7px 10px;font:inherit;font-size:.8rem;font-weight:600;color:var(--muted);cursor:pointer;text-decoration:none}
        .ibtn:hover{border-color:var(--blue);color:var(--blue)}
        .offline{background:#fffbeb;border-bottom:1px solid #fde68a;color:#92400e;font-size:.85rem;padding:8px 14px;font-weight:600}
        /* filters */
        .filters{display:flex;gap:6px;padding:8px 14px;border-bottom:1px solid var(--border);flex-wrap:wrap;align-items:center}
        .filters input,.filters select{padding:7px 10px;font-size:.85rem;width:auto;flex:1;min-width:120px}
        .filters .ibtn{padding:8px 12px}
        .filters .status{width:100%;font-size:.75rem;color:var(--muted)}
        /* messages */
        .msgs{flex:1;overflow-y:auto;padding:16px 14px;display:flex;flex-direction:column;gap:10px;background:var(--bg)}
        .welcome{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:16px}
        .welcome h3{font-size:1rem;margin-bottom:6px}
        .welcome p{font-size:.88rem;color:var(--muted)}
        .sugg{display:flex;flex-wrap:wrap;gap:6px;margin-top:10px}
        .sugg button{background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 11px;font:inherit;font-size:.8rem;font-weight:600;color:var(--blue);cursor:pointer}
        .sugg button:hover{border-color:var(--blue)}
        .msg{max-width:84%;padding:10px 14px;border-radius:14px;font-size:.95rem;line-height:1.5;white-space:pre-wrap;word-wrap:break-word;position:relative}
        .msg.in{align-self:flex-end;background:var(--green);color:#fff;border-bottom-right-radius:4px}
        .msg.out{align-self:flex-start;background:var(--card);border:1px solid var(--border);border-bottom-left-radius:4px}
        .msg.sys{align-self:center;background:var(--blue-bg);border:1px solid #bfdbfe;color:var(--blue-dark);font-size:.85rem;max-width:95%}
        .meta{display:flex;gap:8px;align-items:center;margin-top:6px;font-size:.72rem;opacity:.85}
        .msg.in .meta{justify-content:flex-end}
        .msg.out .meta{color:var(--muted)}
        .cat{background:var(--green-bg);color:var(--green-dark);border:1px solid var(--green-border);padding:1px 7px;border-radius:999px;font-weight:700}
        .fb{display:flex;gap:4px;margin-left:auto}
        .fb button{background:none;border:1px solid var(--border);border-radius:6px;padding:2px 7px;cursor:pointer;font-size:.8rem;color:var(--muted)}
        .fb button.on{background:var(--green-bg);border-color:var(--green);color:var(--green-dark)}
        .more{align-self:center}
        .typing{display:flex;gap:4px;padding:6px 14px}
        .typing span{width:7px;height:7px;background:var(--green);border-radius:50%;opacity:.4;animation:t 1.2s infinite}
        .typing span:nth-child(2){animation-delay:.2s}.typing span:nth-child(3){animation-delay:.4s}
        @keyframes t{0%,100%{transform:translateY(0);opacity:.4}50%{transform:translateY(-4px);opacity:1}}
        /* composer */
        .composer{display:flex;gap:8px;padding:10px 14px;border-top:1px solid var(--border);background:var(--card);padding-bottom:calc(10px + env(safe-area-inset-bottom))}
        .composer textarea{resize:none;max-height:120px;line-height:1.4;padding:11px 13px}
        .send{width:48px;height:48px;border-radius:8px;background:var(--green);color:#fff;border:none;cursor:pointer;flex-shrink:0;display:flex;align-items:center;justify-content:center}
        .send:hover{background:var(--green-dark)}
        /* modal */
        .modal{position:fixed;inset:0;background:rgba(15,23,42,.45);display:flex;align-items:center;justify-content:center;padding:16px;z-index:50}
        .modal .box{background:#fff;border-radius:12px;padding:20px;width:100%;max-width:420px}
        .modal h3{margin-bottom:12px}
        .modal .field{margin-bottom:12px}
        @media (max-width:600px){.app{border:none}.msg{max-width:92%}.hdr .st{display:none}}
    </style>
</head>
<body>
<div class="app" id="app">

    <!-- AUTH -->
    <div id="auth-screen" class="screen active">
        <div class="auth">
            <div class="logo">H</div>
            <h1>{{ $settings['app_name'] }} – uliza lolote</h1>
            <p class="lead">Ingiza namba yako ya simu. Tutakutumia namba fupi ya uthibitisho ili mazungumzo yako yabaki yako.</p>

            <div class="sms-banner">
                <span class="sms-badge">SMS {{ $settings['sms_shortcode'] }}</span>
                <b>Huna intaneti?</b> Tuma <b>{{ $settings['sms_keyword'] }}</b> [swali lako] kwenda <b>{{ $settings['sms_shortcode'] }}</b> kutoka simu yoyote.
            </div>

            @if(session('error'))<div class="err">{{ session('error') }}</div>@endif
            <div id="auth-error" class="err hidden"></div>
            <div id="auth-ok" class="ok hidden"></div>

            <form id="phone-form">
                <label for="phone">Namba ya simu / Phone number</label>
                <div class="row">
                    <input type="tel" id="phone" name="phone_number" placeholder="07XX XXX XXX" autocomplete="tel" required>
                    <select id="lang" name="language" style="width:110px"><option value="sw">Kiswahili</option><option value="en">English</option></select>
                </div>
                <button type="submit" class="btn btn-green" id="phone-btn" style="margin-top:12px">Endelea / Continue</button>
            </form>

            <form id="otp-form" class="hidden">
                <label for="code">Namba ya uthibitisho (tarakimu 6) / Verification code</label>
                <input type="text" id="code" name="code" inputmode="numeric" pattern="\d{6}" maxlength="6" placeholder="123456" autocomplete="one-time-code" required>
                <button type="submit" class="btn btn-green" id="otp-btn" style="margin-top:12px">Thibitisha / Verify</button>
                <div class="row" style="justify-content:space-between;margin-top:6px"><button type="button" class="btn-link" id="otp-back">Badilisha namba</button><button type="button" class="btn-link" id="otp-resend">Tuma tena</button></div>
            </form>

            <p class="small">Kwa kuendelea unakubali <a href="{{ route('legal.terms') }}">Vigezo na Masharti</a> na <a href="{{ route('legal.privacy') }}">Sera ya Faragha</a>. <a href="{{ route('welcome') }}">Rudi mwanzo</a></p>
        </div>
    </div>

    <!-- CHAT -->
    <div id="chat-screen" class="screen">
        <div class="hdr">
            <div class="who">
                <div class="logo">H</div>
                <div><h2>{{ $settings['app_name'] }}</h2><div class="st" id="who-line">Tayari kukusaidia</div></div>
            </div>
            <div class="actions">
                <span class="tag" title="Tuma {{ $settings['sms_keyword'] }} [swali] kwenda {{ $settings['sms_shortcode'] }}">SMS {{ $settings['sms_keyword'] }} → {{ $settings['sms_shortcode'] }}</span>
                <button class="ibtn" id="prefs-btn">Mipangilio</button>
                <a class="ibtn" href="{{ route('community.index') }}">Jamii</a>
                <button class="ibtn" id="logout-btn">Toka</button>
            </div>
        </div>
        <div class="offline hidden" id="offline-bar">Uko nje ya mtandao. Tuma <b>{{ $settings['sms_keyword'] }}</b> [swali] kwenda <b>{{ $settings['sms_shortcode'] }}</b> kwa SMS.</div>
        <div class="filters">
            <input type="text" id="f-keyword" placeholder="Tafuta kwenye historia...">
            <input type="date" id="f-date">
            <select id="f-category"><option value="">Mada zote</option></select>
            <button class="ibtn" id="f-apply">Tafuta</button>
            <button class="ibtn hidden" id="f-reset">Futa</button>
            <div class="status hidden" id="f-status"></div>
        </div>
        <div class="msgs" id="msgs"></div>
        <div class="typing hidden" id="typing"><span></span><span></span><span></span></div>
        <form class="composer" id="composer">
            <textarea id="input" rows="1" placeholder="Andika swali lako hapa..." maxlength="2000"></textarea>
            <button type="submit" class="send" aria-label="Tuma"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg></button>
        </form>
    </div>

    <!-- PREFS MODAL -->
    <div class="modal hidden" id="prefs">
        <div class="box">
            <h3>Mipangilio yako</h3>
            <form id="prefs-form">
                <div class="field"><label>Jina (si lazima)</label><input type="text" name="name" id="p-name" maxlength="40"></div>
                <div class="field"><label>Lugha ya majibu</label><select name="preferred_language" id="p-lang"><option value="auto">Fuata lugha ninayoandika</option><option value="sw">Kiswahili daima</option><option value="en">English always</option></select></div>
                <div class="field"><label>Mkoa wako (kwa huduma zilizo karibu)</label><select name="region" id="p-region"><option value="">— Chagua mkoa —</option></select></div>
                <div class="row"><button type="button" class="btn btn-ghost" id="prefs-cancel" style="flex:1">Funga</button><button type="submit" class="btn btn-green" style="flex:1">Hifadhi</button></div>
            </form>
        </div>
    </div>
</div>

<script>
(() => {
    const $ = (id) => document.getElementById(id);
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const state = { user: null, settings: null, phone: '', lang: 'sw', oldest: null, hasMore: false, filtering: false };
    const SUGGESTIONS = {
        sw: ['Nifanyeje kupata kitambulisho cha NIDA?', 'Haki zangu ni zipi nikikamatwa na polisi?', 'Dalili za malaria kwa mtoto ni zipi?', 'Nipande mahindi lini mkoa wangu?', 'Nieleze fractions kwa darasa la sita', 'Nianzeje biashara ndogo?'],
        en: ['How do I register for a NIDA ID?', 'What are my rights if the police arrest me?', 'What are malaria danger signs in a child?', 'When should I plant maize in my region?', 'Explain fractions for Standard Six', 'How do I start a small business?']
    };

    const api = async (url, opts = {}) => {
        const res = await fetch(url, Object.assign({ headers: Object.assign({ 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, opts.body && !(opts.body instanceof FormData) ? { 'Content-Type': 'application/json' } : {}) }, opts));
        let data = {};
        try { data = await res.json(); } catch (e) {}
        if (!res.ok) { const msg = data.message || (data.errors && Object.values(data.errors)[0][0]) || ('Kosa ' + res.status); throw new Error(msg); }
        return data;
    };
    const show = (id) => { document.querySelectorAll('.screen').forEach(s => s.classList.remove('active')); $(id).classList.add('active'); };
    const err = (el, msg) => { el.textContent = msg; el.classList.remove('hidden'); };
    const hide = (el) => el.classList.add('hidden');
    const t = (sw, en) => (state.lang === 'en' ? en : sw);

    // ---------------------------------------------------------------- boot
    async function boot() {
        try {
            const s = await api('/chat/session');
            state.settings = s.settings;
            fillCategories(s.settings.categories);
            fillRegions(s.settings.regions);
            if (s.authenticated) { state.user = s.user; enterChat(); } else { show('auth-screen'); }
        } catch (e) { show('auth-screen'); }
    }

    function fillCategories(cats) {
        const sel = $('f-category');
        cats.forEach(c => { const o = document.createElement('option'); o.value = c.key; o.textContent = c.sw + ' / ' + c.en; sel.appendChild(o); });
    }
    function fillRegions(regions) {
        const sel = $('p-region');
        regions.forEach(r => { const o = document.createElement('option'); o.value = r; o.textContent = r; sel.appendChild(o); });
    }

    // ---------------------------------------------------------------- auth
    $('phone-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        hide($('auth-error')); hide($('auth-ok'));
        const btn = $('phone-btn'); btn.disabled = true; btn.textContent = '...';
        state.phone = $('phone').value.trim(); state.lang = $('lang').value;
        try {
            const r = await api('/chat/request-otp', { method: 'POST', body: JSON.stringify({ phone_number: state.phone, language: state.lang }) });
            if (!r.otp_required) { state.user = r.user; enterChat(); return; }
            $('phone-form').classList.add('hidden'); $('otp-form').classList.remove('hidden');
            let msg = t('Tumetuma namba ya uthibitisho kwa ' + r.phone_masked + '.', 'We sent a verification code to ' + r.phone_masked + '.');
            if (r.debug_code) msg += ' [dev: ' + r.debug_code + ']';
            $('auth-ok').textContent = msg; $('auth-ok').classList.remove('hidden');
            $('code').focus();
        } catch (ex) { err($('auth-error'), ex.message); }
        finally { btn.disabled = false; btn.textContent = 'Endelea / Continue'; }
    });
    $('otp-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        hide($('auth-error'));
        const btn = $('otp-btn'); btn.disabled = true;
        try {
            const r = await api('/chat/verify-otp', { method: 'POST', body: JSON.stringify({ phone_number: state.phone, code: $('code').value.trim() }) });
            state.user = r.user; enterChat();
        } catch (ex) { err($('auth-error'), ex.message); }
        finally { btn.disabled = false; }
    });
    $('otp-back').addEventListener('click', () => { $('otp-form').classList.add('hidden'); $('phone-form').classList.remove('hidden'); hide($('auth-ok')); });
    $('otp-resend').addEventListener('click', () => $('phone-form').requestSubmit());

    // ---------------------------------------------------------------- chat
    async function enterChat() {
        const redirect = new URLSearchParams(location.search).get('redirect');
        if (redirect && redirect.startsWith('/')) { location.href = redirect; return; }
        show('chat-screen');
        state.lang = state.user.preferred_language === 'en' ? 'en' : state.lang;
        $('who-line').textContent = (state.user.name ? state.user.name + ' · ' : '') + state.user.phone_masked + (state.user.region ? ' · ' + state.user.region : '');
        await loadMessages();
        const q = new URLSearchParams(location.search).get('q');
        if (q) { $('input').value = q; $('input').focus(); history.replaceState({}, '', '/chat'); }
    }

    async function loadMessages(filters = {}, append = false) {
        const params = new URLSearchParams(filters);
        const r = await api('/chat/messages?' + params.toString());
        const box = $('msgs');
        if (!append) box.innerHTML = '';
        if (!append && r.messages.length === 0 && !state.filtering) renderWelcome();
        const frag = document.createDocumentFragment();
        r.messages.forEach(m => frag.appendChild(renderMsg(m)));
        if (append) { box.querySelector('.more')?.remove(); box.prepend(frag); } else { box.appendChild(frag); }
        state.hasMore = r.has_more; state.oldest = r.messages.length ? r.messages[0].id : state.oldest;
        if (state.hasMore && !state.filtering) {
            const b = document.createElement('button'); b.className = 'ibtn more'; b.textContent = t('Onyesha mazungumzo ya zamani', 'Show older messages');
            b.onclick = () => loadMessages({ before: state.oldest }, true); box.prepend(b);
        }
        if (!append) scrollBottom();
    }

    function renderWelcome() {
        const w = document.createElement('div'); w.className = 'welcome';
        w.innerHTML = '<h3>' + t('Karibu! Uliza lolote kuhusu maisha Tanzania.', 'Welcome! Ask anything about life in Tanzania.') + '</h3><p>' + t('Sheria, afya, kilimo, masomo, huduma za serikali, fedha, ajira, teknolojia... Taja mahali ulipo ukihitaji huduma iliyo karibu.', 'Law, health, farming, school subjects, government services, money, jobs, technology... Mention where you are if you need nearby services.') + '</p><div class="sugg"></div>';
        const s = w.querySelector('.sugg');
        SUGGESTIONS[state.lang].forEach(q => { const b = document.createElement('button'); b.type = 'button'; b.textContent = q; b.onclick = () => { $('input').value = q; $('composer').requestSubmit(); }; s.appendChild(b); });
        $('msgs').appendChild(w);
    }

    function renderMsg(m) {
        const d = document.createElement('div');
        const isIn = m.direction === 'inbound';
        d.className = 'msg ' + (isIn ? 'in' : 'out');
        d.dataset.id = m.id || '';
        const body = document.createElement('div'); body.textContent = m.content; d.appendChild(body);
        const meta = document.createElement('div'); meta.className = 'meta';
        const time = document.createElement('span'); time.textContent = m.created_at ? new Date(m.created_at).toLocaleString('sw-TZ', { hour: '2-digit', minute: '2-digit', day: '2-digit', month: 'short' }) : '';
        meta.appendChild(time);
        if (!isIn && m.category_label) { const c = document.createElement('span'); c.className = 'cat'; c.textContent = m.category_label; meta.appendChild(c); }
        if (!isIn && m.id) {
            const fb = document.createElement('div'); fb.className = 'fb';
            [[1, '👍'], [-1, '👎']].forEach(([v, icon]) => {
                const b = document.createElement('button'); b.type = 'button'; b.textContent = icon; b.title = v === 1 ? t('Imesaidia', 'Helpful') : t('Haikusaidia', 'Not helpful');
                if (m.feedback === v) b.classList.add('on');
                b.onclick = async () => { try { await api('/chat/feedback', { method: 'POST', body: JSON.stringify({ message_id: m.id, rating: v }) }); fb.querySelectorAll('button').forEach(x => x.classList.remove('on')); b.classList.add('on'); } catch (e) {} };
                fb.appendChild(b);
            });
            meta.appendChild(fb);
        }
        d.appendChild(meta);
        return d;
    }

    function addSystem(text) { const d = document.createElement('div'); d.className = 'msg sys'; d.textContent = text; $('msgs').appendChild(d); scrollBottom(); }
    function scrollBottom() { const b = $('msgs'); b.scrollTop = b.scrollHeight; }

    $('composer').addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = $('input').value.trim();
        if (!text) return;
        $('msgs').querySelector('.welcome')?.remove();
        $('msgs').appendChild(renderMsg({ direction: 'inbound', content: text, created_at: new Date().toISOString() }));
        $('input').value = ''; $('input').style.height = 'auto';
        $('typing').classList.remove('hidden'); scrollBottom();
        try {
            const r = await api('/chat/send', { method: 'POST', body: JSON.stringify({ message: text }) });
            $('typing').classList.add('hidden');
            if (r.reply) $('msgs').appendChild(renderMsg(r.reply));
        } catch (ex) {
            $('typing').classList.add('hidden');
            if (/401/.test(ex.message)) { state.user = null; show('auth-screen'); return; }
            addSystem('⚠️ ' + ex.message);
        }
        scrollBottom();
    });
    $('input').addEventListener('input', function () { this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 120) + 'px'; });
    $('input').addEventListener('keydown', (e) => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); $('composer').requestSubmit(); } });

    // filters
    $('f-apply').addEventListener('click', () => {
        const f = { keyword: $('f-keyword').value.trim(), date: $('f-date').value, category: $('f-category').value };
        Object.keys(f).forEach(k => { if (!f[k]) delete f[k]; });
        if (!Object.keys(f).length) return;
        state.filtering = true; $('f-reset').classList.remove('hidden'); $('f-status').classList.remove('hidden');
        $('f-status').textContent = t('Matokeo ya utafutaji', 'Search results');
        loadMessages(f);
    });
    $('f-reset').addEventListener('click', () => { $('f-keyword').value = ''; $('f-date').value = ''; $('f-category').value = ''; state.filtering = false; hide($('f-reset')); hide($('f-status')); loadMessages(); });

    // prefs
    $('prefs-btn').addEventListener('click', () => { $('p-name').value = state.user.name || ''; $('p-lang').value = state.user.preferred_language || 'auto'; $('p-region').value = state.user.region || ''; $('prefs').classList.remove('hidden'); });
    $('prefs-cancel').addEventListener('click', () => hide($('prefs')));
    $('prefs-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        try {
            const r = await api('/chat/preferences', { method: 'POST', body: JSON.stringify({ name: $('p-name').value, preferred_language: $('p-lang').value, region: $('p-region').value }) });
            state.user = r.user; hide($('prefs'));
            state.lang = state.user.preferred_language === 'en' ? 'en' : (state.user.preferred_language === 'sw' ? 'sw' : state.lang);
            $('who-line').textContent = (state.user.name ? state.user.name + ' · ' : '') + state.user.phone_masked + (state.user.region ? ' · ' + state.user.region : '');
            addSystem(t('Mipangilio imehifadhiwa.', 'Preferences saved.'));
        } catch (ex) { alert(ex.message); }
    });

    $('logout-btn').addEventListener('click', async () => {
        if (!confirm(t('Unataka kutoka?', 'Sign out?'))) return;
        await api('/chat/logout', { method: 'POST' }); state.user = null; $('msgs').innerHTML = ''; show('auth-screen');
    });

    // offline awareness + PWA
    const net = () => $('offline-bar').classList.toggle('hidden', navigator.onLine);
    window.addEventListener('online', net); window.addEventListener('offline', net); net();
    if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js').catch(() => {});

    boot();
})();
</script>
</body>
</html>
