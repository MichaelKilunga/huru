<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ═══════════════════════════════════════════════════════
         PRIMARY SEO META
    ═══════════════════════════════════════════════════════ --}}
    <title>Chat with HuruLearn – Instant Legal Guidance for Tanzania</title>
    <meta name="description" content="HuruLearn delivers legal education, rights awareness, and constitutional guidance through basic SMS and Web. No internet? Use SMS. Have data? Use our Web Chat.">
    <meta name="keywords" content="SMS legal education, legal rights Tanzania, constitution Tanzania, offline learning, HuruLearn, Huru Digital, civic education, Kiswahili legal help, TanzLII, OSG Tanzania">
    <meta name="author" content="Huru Digital Co. Ltd.">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">

    {{-- Canonical URL --}}
    <link rel="canonical" href="https://hurulearn.hurudigital.co.tz/chat">

    {{-- Alternate languages --}}
    <link rel="alternate" hreflang="en" href="https://hurulearn.hurudigital.co.tz/chat">
    <link rel="alternate" hreflang="sw" href="https://hurulearn.hurudigital.co.tz/chat">
    <link rel="alternate" hreflang="x-default" href="https://hurulearn.hurudigital.co.tz/chat">

    {{-- ═══════════════════════════════════════════════════════
         OPEN GRAPH (Facebook, LinkedIn, WhatsApp …)
    ═══════════════════════════════════════════════════════ --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="HuruLearn">
    <meta property="og:title" content="Chat with HuruLearn – Instant Legal Guidance">
    <meta property="og:description" content="Legal education, rights awareness, and constitutional knowledge delivered via any device. No internet? Use SMS. Have data? Use our Web Chat.">
    <meta property="og:url" content="https://hurulearn.hurudigital.co.tz/chat">
    <meta property="og:image" content="https://hurulearn.hurudigital.co.tz/og-image.svg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="HuruLearn — SMS Education Platform for Africa">
    <meta property="og:locale" content="en_TZ">
    <meta property="og:locale:alternate" content="sw_TZ">

    {{-- ═══════════════════════════════════════════════════════
         TWITTER / X CARD
    ═══════════════════════════════════════════════════════ --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@HuruLearnTZ">
    <meta name="twitter:creator" content="@HuruDigitalCoLtd">
    <meta name="twitter:title" content="Chat with HuruLearn – Instant Legal Guidance">
    <meta name="twitter:description" content="Legal education, rights awareness, and constitutional knowledge via basic SMS and Web. No internet? Use SMS. Have data? Use Web Chat.">
    <meta name="twitter:image" content="https://hurulearn.hurudigital.co.tz/og-image.svg">
    <meta name="twitter:image:alt" content="HuruLearn SMS Education Platform">

    {{-- ═══════════════════════════════════════════════════════
         GEO / REGIONAL META
    ═══════════════════════════════════════════════════════ --}}
    <meta name="geo.region" content="TZ">
    <meta name="geo.placename" content="Tanzania">
    <meta name="geo.position" content="-6.369028;34.888822">
    <meta name="ICBM" content="-6.369028, 34.888822">

    {{-- ═══════════════════════════════════════════════════════
         PWA / MANIFEST
    ═══════════════════════════════════════════════════════ --}}
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/logo.svg">
    <meta name="theme-color" content="#2563eb">

    {{-- ═══════════════════════════════════════════════════════
         FONTS & PERFORMANCE
    ═══════════════════════════════════════════════════════ --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #15803d;
            --primary-dark: #166534;
            --secondary: #0f172a;
            --gov-blue: #1d4ed8;
            --gov-blue-dark: #1e40af;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #0f172a;
            --text-muted: #475569;
            --border-color: #cbd5e1;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chat-container {
            width: 100%;
            height: 100%;
            max-width: 650px;
            display: flex;
            flex-direction: column;
            position: relative;
            background: #ffffff;
            border: 1px solid var(--border-color);
        }

        .screen {
            display: none;
            width: 100%;
            height: 100%;
            transition: opacity 0.3s ease;
        }

        .screen.active {
            display: flex;
            flex-direction: column;
        }

        /* Auth Screen */
        #auth-screen {
            justify-content: center;
            padding: 2rem;
            background: #f8fafc;
        }

        .auth-card {
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 14px;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.08);
        }

        .auth-header h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.75rem;
            margin: 1rem 0 0.5rem;
            color: #14532d;
            font-weight: 800;
        }

        .auth-header p {
            color: #334155;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: #15803d;
            color: #ffffff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.4rem;
            margin: 0 auto;
        }

        .sms-offline-banner {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-left: 5px solid #15803d;
            border-radius: 8px;
            padding: 1rem 1.1rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .sms-offline-banner .banner-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 800;
            font-size: 0.88rem;
            color: #14532d;
            margin-bottom: 0.3rem;
        }

        .sms-offline-banner .sms-badge {
            background: #15803d;
            color: #ffffff;
            font-weight: 900;
            font-size: 0.8rem;
            padding: 0.25rem 0.65rem;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }

        .sms-offline-banner p {
            font-size: 0.85rem;
            color: #166534;
            margin: 0;
            line-height: 1.5;
        }

        .input-group {
            text-align: left;
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            color: #0f172a;
            font-weight: 700;
        }

        .input-group input {
            width: 100%;
            padding: 0.9rem 1.1rem;
            border-radius: 8px;
            border: 2px solid #94a3b8;
            background: #ffffff;
            color: #0f172a;
            font-size: 1.05rem;
            font-weight: 600;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-group input:focus {
            border-color: #15803d;
            box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.15);
        }

        .btn-primary {
            width: 100%;
            padding: 0.95rem;
            border-radius: 8px;
            border: none;
            background: #15803d;
            color: white;
            font-weight: 800;
            font-size: 1.05rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #166534;
        }

        /* Chat Screen */
        #chat-screen {
            background: #f8fafc;
        }

        .chat-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            z-index: 10;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .header-info .logo {
            width: 38px;
            height: 38px;
            font-size: 0.9rem;
            border-radius: 6px;
        }

        .header-info h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.05rem;
            margin: 0;
            font-weight: 700;
            color: var(--text);
        }

        .status {
            font-size: 0.75rem;
            color: #15803d;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
        }
        .status::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #15803d;
            border-radius: 50%;
            display: inline-block;
        }

        .sms-channel-tag {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-logout {
            background: #f1f5f9;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 0.5rem 0.85rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
        }

        .btn-community {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: var(--gov-blue);
            padding: 0.5rem 0.85rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
        }

        .btn-community:hover {
            background: #dbeafe;
            color: var(--gov-blue-dark);
        }

        .chat-messages {
            flex: 1;
            padding: 1.25rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .message {
            max-width: 82%;
            padding: 0.85rem 1.1rem;
            border-radius: 12px;
            font-size: 0.95rem;
            line-height: 1.5;
            word-break: break-word;
        }

        .message.outbound {
            align-self: flex-start;
            background: #ffffff;
            color: var(--text);
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 2px;
        }

        .message.inbound {
            align-self: flex-end;
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 2px;
        }

        .message-meta {
            font-size: 0.7rem;
            margin-top: 0.35rem;
            opacity: 0.8;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 4px;
        }

        .message.outbound .message-meta {
            color: var(--text-muted);
            justify-content: flex-start;
        }

        .chat-input-area {
            padding: 1rem 1.25rem;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 0.75rem;
        }

        .chat-input-area input {
            flex: 1;
            padding: 0.85rem 1.1rem;
            border-radius: 8px;
            border: 1.5px solid var(--border-color);
            background: #ffffff;
            color: var(--text);
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .chat-input-area input:focus {
            border-color: var(--primary);
        }

        .btn-send {
            background: var(--primary);
            color: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .btn-send:hover {
            background: var(--primary-dark);
        }

        .hidden { display: none !important; }

        .typing-indicator {
            padding: 0.5rem 1.25rem;
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .typing-indicator span {
            width: 6px;
            height: 6px;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0.4;
            animation: typing 1.2s infinite ease-in-out;
        }

        .filter-bar {
            padding: 0.75rem 1.25rem;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
        }

        .filter-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .filter-group input {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.45rem 0.75rem;
            color: var(--text);
            font-size: 0.85rem;
            outline: none;
        }

        .btn-filter {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-reset {
            background: #e2e8f0;
            color: var(--text-muted);
        }

        .search-status {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.4rem;
        }

        @keyframes typing {
            0%, 100% { transform: translateY(0); opacity: 0.4; }
            50% { transform: translateY(-4px); opacity: 1; }
        }

        @media (max-width: 600px) {
            .chat-container { max-width: 100%; border-radius: 0; }
            .auth-card { border-radius: 0; height: 100%; display: flex; flex-direction: column; justify-content: center; border: none; }
        }
</style>
</head>
<body>

<div class="chat-container">
    <div id="auth-screen" class="screen active">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">HL</div>
                <h1>HuruLearn — Elimu ya Sheria</h1>
                <p>Ingiza namba yako ya simu kuanza kupata msaada na elimu ya sheria mara moja.</p>
            </div>

            <!-- Primary Offline SMS Entry Banner -->
            <div class="sms-offline-banner">
                <div class="banner-title">
                    <span>Njia Kuu ya SMS (Bila Intaneti)</span>
                    <span class="sms-badge">SMS 15054</span>
                </div>
                <p>
                    Huna bando au Intaneti? Tuma neno <strong>HURU</strong> [swali lako] kwenda <strong>15054</strong> kutoka simu yoyote ya mkononi kupata majibu ya kisheria.
                </p>
            </div>

            @if(session('error'))
                <div class="auth-error-alert" style="background: #fee2e2; border: 1px solid #fca5a5; padding: 0.75rem; border-radius: 8px; color: #991b1b; margin-bottom: 1.5rem; font-size: .85rem; font-weight: 600; text-align: center;">
                    {{ session('error') }}
                </div>
            @endif
            <form id="login-form">
                @csrf
                <div class="input-group">
                    <label for="phone_number">Namba ya Simu ya Mkononi</label>
                    <input type="text" id="phone_number" name="phone_number" placeholder="+255 7XX XXX XXX" required>
                </div>
                <button type="submit" id="login-btn" class="btn-primary">Ingia / Anza Sasa</button>
            </form>
            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('welcome') }}" style="color: #1d4ed8; text-decoration: underline; font-size: 0.88rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; transition: color 0.2s;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Rudi Ukurasa wa Kwanza
                </a>
            </div>
        </div>
    </div>

    <div id="chat-screen" class="screen">
        <div class="chat-header">
            <div class="header-info">
                <div class="logo">HL</div>
                <div>
                    <h2>HuruLearn Law</h2>
                    <span class="status">Legal Advisor Online</span>
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <span class="sms-channel-tag" title="Text HURU [question] to 15054">
                    SMS: HURU to 15054
                </span>
                <a href="{{ route('community.index') }}" class="btn-community">Community</a>
                <button id="logout-btn" class="btn-logout">Logout</button>
            </div>
        </div>
        <div class="filter-bar">
            <div class="filter-group">
                <input type="text" id="keyword-search" placeholder="Search keywords..." aria-label="Search keywords">
                <input type="date" id="date-filter" aria-label="Filter by date">
                <button id="apply-filters" class="btn-filter">Search</button>
                <button id="reset-filters" class="btn-filter btn-reset hidden">Reset</button>
            </div>
            <div id="search-status" class="search-status hidden">
                Showing search results...
            </div>
        </div>
        <div id="chat-messages" class="chat-messages">
            <!-- Messages go here -->
        </div>
        <div id="typing-indicator" class="typing-indicator hidden">
            <span></span><span></span><span></span>
        </div>
        <form id="chat-form" class="chat-input-area">
            @csrf
            <input type="text" id="message-input" placeholder="Ask a question..." autocomplete="off">
            <button type="submit" id="send-btn" class="btn-send">
                <svg viewBox="0 0 24 24" width="24" height="24" style="transform: rotate(45deg);"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" fill="currentColor"></path></svg>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const authScreen = document.getElementById('auth-screen');
        const chatScreen = document.getElementById('chat-screen');
        const loginForm = document.getElementById('login-form');
        const chatForm = document.getElementById('chat-form');
        const chatMessages = document.getElementById('chat-messages');
        const messageInput = document.getElementById('message-input');
        const typingIndicator = document.getElementById('typing-indicator');
        const logoutBtn = document.getElementById('logout-btn');
        const keywordSearch = document.getElementById('keyword-search');
        const dateFilter = document.getElementById('date-filter');
        const applyFiltersBtn = document.getElementById('apply-filters');
        const resetFiltersBtn = document.getElementById('reset-filters');
        const searchStatus = document.getElementById('search-status');

        // Check for existing session
        async function loadMessages(filters = {}) {
            try {
                const params = new URLSearchParams(filters).toString();
                const res = await fetch(`/chat/messages?${params}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                if (data.status === 'success') {
                    const urlParams = new URLSearchParams(window.location.search);
                    const redirectUrl = urlParams.get('redirect');
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                        return;
                    }
                    showChat(data.messages, !!params);
                }
            } catch (err) { console.error('Failed to load messages', err); }
        }
        loadMessages();

        applyFiltersBtn.addEventListener('click', () => {
            const keyword = keywordSearch.value.trim();
            const date = dateFilter.value;
            if (keyword || date) {
                resetFiltersBtn.classList.remove('hidden');
                searchStatus.classList.remove('hidden');
                loadMessages({ keyword, date });
            }
        });

        resetFiltersBtn.addEventListener('click', () => {
            keywordSearch.value = '';
            dateFilter.value = '';
            resetFiltersBtn.classList.add('hidden');
            searchStatus.classList.add('hidden');
            loadMessages();
        });

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('login-btn');
            btn.innerHTML = 'Inaingia...';
            btn.disabled = true;

            const formData = new FormData(loginForm);
            try {
                const res = await fetch('/chat/login', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.status === 'success') {
                    const urlParams = new URLSearchParams(window.location.search);
                    const redirectUrl = urlParams.get('redirect');
                    if (redirectUrl) {
                        window.location.href = redirectUrl;
                        return;
                    }
                    const msgRes = await fetch('/chat/messages', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const msgData = await msgRes.json();
                    showChat(msgData.messages);
                } else {
                    alert('Kosa: ' + data.message);
                }
            } catch (err) {
                alert('Matatizo ya mtandao. Tafadhali jaribu tena.');
            } finally {
                btn.innerHTML = 'Ingia / Anza Sasa';
                btn.disabled = false;
            }
        });

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = messageInput.value.trim();
            if (!msg) return;

            // Optimistic Update
            addMessageToUi({ content: msg, direction: 'inbound' });
            messageInput.value = '';

            typingIndicator.classList.remove('hidden');
            scrollToBottom();

            try {
                const res = await fetch('/chat/send', {
                    method: 'POST',
                    body: JSON.stringify({ message: msg }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();
                typingIndicator.classList.add('hidden');

                if (data.status === 'success') {
                    addMessageToUi(data.ai_message);
                } else {
                    addMessageToUi({ content: '⚠️ ' + data.message, direction: 'outbound' });
                }
            } catch (err) {
                typingIndicator.classList.add('hidden');
                addMessageToUi({ content: '❌ Samahani, kuna tatizo limetokea.', direction: 'outbound' });
            }
            scrollToBottom();
        });

        logoutBtn.addEventListener('click', async () => {
            if (!confirm('Je, una uhakika unataka kuondoka?')) return;
            await fetch('/chat/logout', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            window.location.href = '/';
        });

        function showChat(messages = [], isSearchResult = false) {
            authScreen.classList.remove('active');
            chatScreen.classList.add('active');
            chatMessages.innerHTML = '';
            
            if (messages.length === 0) {
                if (isSearchResult) {
                    const div = document.createElement('div');
                    div.style.textAlign = 'center';
                    div.style.padding = '2rem';
                    div.style.color = 'var(--text-muted)';
                    div.textContent = 'No past chats found matching your filters.';
                    chatMessages.appendChild(div);
                } else {
                    addMessageToUi({ content: 'Habari! Mimi ni msaidizi wako wa kisheria wa HuruLearn nchini Tanzania. Una swali gani leo kuhusu sheria au Katiba ya Tanzania?\n\nAngalizo: Maelezo yangu ni ya kielimu tu na si ushauri wa kisheria wa kitaalamu.', direction: 'outbound' });
                }
            } else {
                messages.forEach(addMessageToUi);
            }
            scrollToBottom();
        }

        function addMessageToUi(msg) {
            const div = document.createElement('div');
            div.className = `message ${msg.direction === 'inbound' ? 'inbound' : 'outbound'}`;
            // Simple newline to <br> conversion
            div.innerHTML = msg.content.replace(/\n/g, '<br>');
            chatMessages.appendChild(div);
        }

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
</body>
</html>
