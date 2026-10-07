<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} &mdash; HuruLearn Admin</title>
    {{-- Prevent admin panel from being indexed by search engines --}}
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/logo.svg">
    <meta name="theme-color" content="#1e1b4b">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --indigo: #1e1b4b; --indigo-dark: #0f0d2e; --indigo-mid: #16143a;
            --blue: #3b82f6; --blue-light: #60a5fa;
            --amber: #f59e0b; --amber-light: #fcd34d;
            --teal: #14b8a6; --teal-light: #5eead4;
            --white: #ffffff;
            --gray-50: #f8fafc; --gray-100: #f1f5f9; --gray-200: #e2e8f0;
            --gray-300: #cbd5e1; --gray-400: #94a3b8; --gray-500: #64748b;
            --gray-600: #475569; --gray-700: #334155; --gray-800: #1e293b; --gray-900: #0f172a;
            --sidebar-w: 240px;
            --success: #10b981; --danger: #ef4444; --warning: #f59e0b;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: #0b0a1e; color: var(--gray-100); min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w); background: var(--indigo-dark);
            border-right: 1px solid rgba(255,255,255,0.07);
            position: fixed; top: 0; left: 0; height: 100vh;
            display: flex; flex-direction: column; z-index: 100;
            transition: transform .3s;
        }
        .sidebar-logo {
            padding: 1.5rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; gap: .7rem; text-decoration: none;
        }
        .sidebar-logo-icon {
            width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--amber), #3b82f6);
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: .95rem; color: #fff;
        }
        .sidebar-logo-text { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.1rem; color: #fff; line-height: 1.1; }
        .sidebar-logo-sub { font-size: .65rem; color: var(--gray-500); font-weight: 500; letter-spacing: .05em; text-transform: uppercase; }
        .sidebar-nav { flex: 1; padding: 1.2rem 0; overflow-y: auto; }
        .sidebar-section { padding: .4rem 1.2rem .2rem; font-size: .65rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--gray-600); margin-top: .5rem; }
        .sidebar-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .65rem 1.2rem; margin: .1rem .6rem; border-radius: 10px;
            text-decoration: none; color: var(--gray-400); font-size: .875rem; font-weight: 500;
            transition: all .2s; position: relative;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.06); color: var(--white); }
        .sidebar-link.active { background: rgba(245,158,11,0.15); color: var(--amber-light); }
        .sidebar-link.active::before { content: ''; position: absolute; left: 0; top: 25%; bottom: 25%; width: 3px; background: var(--amber); border-radius: 0 3px 3px 0; }
        .sidebar-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar-footer { padding: 1rem 1.2rem; border-top: 1px solid rgba(255,255,255,0.07); }
        .sidebar-footer a { display: flex; align-items: center; gap: .6rem; text-decoration: none; color: var(--gray-500); font-size: .8rem; transition: color .2s; }
        .sidebar-footer a:hover { color: var(--gray-300); }

        /* TOPBAR */
        .topbar {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0; height: 60px;
            background: rgba(11,10,30,0.9); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; z-index: 90;
        }
        .topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.05rem; font-weight: 600; color: var(--gray-100); }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }
        .topbar-badge {
            padding: .3rem .8rem; border-radius: 50px; font-size: .72rem; font-weight: 600;
            background: rgba(20,184,166,0.15); border: 1px solid rgba(20,184,166,0.3); color: var(--teal-light);
            display: flex; align-items: center; gap: .4rem;
        }
        .topbar-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--teal); display: inline-block; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        /* MAIN */
        .main-wrapper { margin-left: var(--sidebar-w); min-height: 100vh; width: calc(100% - var(--sidebar-w)); }
        .main-content { margin-top: 60px; padding: 2rem; }

        /* CARDS */
        .card {
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px; overflow: hidden;
        }
        .card-header {
            padding: 1.2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 600; color: var(--gray-100); }
        .card-subtitle { font-size: .78rem; color: var(--gray-500); }
        .card-body { padding: 1.5rem; }

        /* STAT CARDS */
        .stat-card {
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px; padding: 1.5rem; position: relative; overflow: hidden;
            transition: transform .2s, border-color .2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card-glow { position: absolute; top: -30px; right: -30px; width: 100px; height: 100px; border-radius: 50%; opacity: .12; }
        .stat-label { font-size: .78rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--gray-500); margin-bottom: .5rem; }
        .stat-value { font-family: 'Space Grotesk', sans-serif; font-size: 2.2rem; font-weight: 700; color: var(--gray-100); line-height: 1; }
        .stat-icon { position: absolute; top: 1.2rem; right: 1.2rem; font-size: 1.5rem; opacity: .6; }

        /* TABLES */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { padding: .75rem 1.2rem; text-align: left; font-size: .7rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: var(--gray-500); border-bottom: 1px solid rgba(255,255,255,0.07); }
        .data-table td { padding: .9rem 1.2rem; font-size: .85rem; color: var(--gray-300); border-bottom: 1px solid rgba(255,255,255,0.04); }
        .data-table tbody tr { transition: background .15s; }
        .data-table tbody tr:hover { background: rgba(255,255,255,0.03); }
        .data-table tbody tr:last-child td { border-bottom: none; }

        /* BADGES */
        .badge { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .65rem; border-radius: 50px; font-size: .72rem; font-weight: 600; }
        .badge-green { background: rgba(16,185,129,0.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.25); }
        .badge-blue { background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.25); }
        .badge-amber { background: rgba(245,158,11,0.15); color: var(--amber-light); border: 1px solid rgba(245,158,11,0.25); }
        .badge-red { background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.25); }
        .badge-gray { background: rgba(255,255,255,0.07); color: var(--gray-400); border: 1px solid rgba(255,255,255,0.1); }

        /* FORMS */
        .form-label { display: block; font-size: .82rem; font-weight: 500; color: var(--gray-400); margin-bottom: .4rem; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: .65rem 1rem; border-radius: 10px;
            background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.1);
            color: var(--gray-100); font-size: .875rem; font-family: inherit; outline: none;
            transition: border-color .2s, background .2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--amber); background: rgba(255,255,255,0.08); }
        .form-input::placeholder, .form-textarea::placeholder { color: var(--gray-600); }
        .form-select option { background: #1e1b4b; color: var(--gray-100); }
        .form-hint { font-size: .75rem; color: var(--gray-600); margin-top: .35rem; }
        .form-group { margin-bottom: 1.2rem; }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: .5rem; padding: .6rem 1.3rem; border-radius: 10px; font-size: .875rem; font-weight: 600; cursor: pointer; border: none; transition: all .2s; text-decoration: none; font-family: inherit; }
        .btn-primary { background: linear-gradient(135deg, var(--amber), #e67e22); color: #fff; box-shadow: 0 4px 15px rgba(245,158,11,0.25); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.35); }
        .btn-blue { background: linear-gradient(135deg, var(--blue), #1d4ed8); color: #fff; box-shadow: 0 4px 15px rgba(59,130,246,0.2); }
        .btn-blue:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59,130,246,0.3); }
        .btn-danger { background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }
        .btn-ghost { background: rgba(255,255,255,0.06); color: var(--gray-300); border: 1px solid rgba(255,255,255,0.1); }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); }
        .btn-sm { padding: .35rem .8rem; font-size: .78rem; }
        .btn-full { width: 100%; justify-content: center; }

        /* ALERTS */
        .alert { padding: 1rem 1.2rem; border-radius: 12px; font-size: .875rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: .75rem; }
        .alert-success { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25); color: #6ee7b7; }
        .alert-error { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: #fca5a5; }
        .alert-icon { font-size: 1rem; flex-shrink: 0; }

        /* GRID */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.2rem; margin-bottom: 1.8rem; }

        /* FOOTER */
        .admin-footer { padding: 1.2rem 2rem; border-top: 1px solid rgba(255,255,255,0.07); text-align: center; font-size: .78rem; color: var(--gray-600); }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        /* MOBILE */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; width: 100%; }
            .topbar { left: 0; }
            .mobile-menu-btn { display: flex !important; }
        }
        .mobile-menu-btn { display: none; background: none; border: none; color: var(--gray-400); cursor: pointer; font-size: 1.3rem; }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <img src="/logo.svg" alt="Logo" style="width: 36px; height: 36px;">
        <div>
            <div class="sidebar-logo-text">HuruLearn</div>
            <div class="sidebar-logo-sub">Admin Panel</div>
        </div>
    </a>
    <nav class="sidebar-nav">
        <div class="sidebar-section">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="sidebar-icon">📊</span> Dashboard
        </a>
        <div class="sidebar-section">Content</div>
        <a href="{{ route('admin.curriculum.index') }}" class="sidebar-link {{ request()->routeIs('admin.curriculum.*') ? 'active' : '' }}">
            <span class="sidebar-icon">⚖️</span> Legal Database
        </a>
        <a href="{{ route('admin.templates.index') }}" class="sidebar-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
            <span class="sidebar-icon">🤖</span> AI Templates
        </a>
        <div class="sidebar-section">System</div>
        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <span class="sidebar-icon">⚙️</span> Settings
        </a>
        <a href="/" class="sidebar-link">
            <span class="sidebar-icon">🌐</span> View Landing Page
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="/">← Back to Public Site</a>
    </div>
</aside>

<!-- TOPBAR -->
<div class="topbar">
    <div style="display:flex; align-items:center; gap:1rem;">
        <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
        <span class="topbar-title">{{ $title ?? 'Dashboard' }}</span>
    </div>
    <div class="topbar-right">
        @php $aiEnabled = \App\Models\SystemSetting::where('key', 'ai_enabled')->value('value') ?? '1'; @endphp
        @if($aiEnabled == '1')
            <div class="topbar-badge">Live SMS Active</div>
        @else
            <div class="topbar-badge" style="background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); color: #fca5a5;">
                <style>.topbar-badge::before { display: none; }</style>
                <span>⏸️</span> AI Paused
            </div>
        @endif
    </div>
</div>

<!-- MAIN -->
<div class="main-wrapper">
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">✓</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <span class="alert-icon">✕</span> {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </div><!-- /main-content -->

    <footer class="admin-footer">
        <div>&copy; {{ date('Y') }} HuruLearn by Huru Digital Co. Ltd. &mdash; AI SMS Education Platform</div>
        <div style="margin-top: 0.5rem; display: flex; justify-content: center; gap: 1rem;">
            <a href="{{ route('legal.terms') }}" style="color: var(--gray-600); text-decoration: none;">Terms & Conditions</a>
            <span>&bull;</span>
            <a href="{{ route('legal.privacy') }}" style="color: var(--gray-600); text-decoration: none;">Privacy Policy</a>
        </div>
    </footer>

</div><!-- /main-wrapper -->

<script>
    const menuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('sidebar');
    if (menuBtn) menuBtn.addEventListener('click', () => sidebar.classList.toggle('open'));
</script>
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js').then((reg) => {
                console.log('ServiceWorker registration successful');
            }).catch((err) => {
                console.log('ServiceWorker registration failed:', err);
            });
        });
    }
</script>
</body>
</html>
