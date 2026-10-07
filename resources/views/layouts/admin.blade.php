<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} – Huru Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--green:#15803d;--green-dark:#166534;--green-bg:#f0fdf4;--green-border:#bbf7d0;--blue:#1d4ed8;--blue-dark:#1e40af;--blue-bg:#eff6ff;--bg:#f8fafc;--card:#fff;--text:#0f172a;--muted:#475569;--border:#cbd5e1;--danger:#b91c1c;--danger-bg:#fef2f2;--warn:#b45309;--warn-bg:#fffbeb;--sidebar:240px}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text);font-size:.92rem;line-height:1.5}
        a{color:var(--blue)}
        .sidebar{position:fixed;top:0;left:0;width:var(--sidebar);height:100vh;background:#fff;border-right:1px solid var(--border);display:flex;flex-direction:column;z-index:30}
        .brand{display:flex;align-items:center;gap:10px;padding:16px;border-bottom:1px solid var(--border);text-decoration:none;color:var(--green-dark);font-weight:800;font-size:1.15rem}
        .brand img{width:32px;height:32px;border-radius:8px}
        .brand small{display:block;font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);font-weight:600}
        .nav{flex:1;overflow:auto;padding:10px}
        .nav .sec{font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);padding:12px 10px 4px}
        .nav a{display:flex;align-items:center;gap:8px;padding:8px 10px;border-radius:8px;text-decoration:none;color:var(--muted);font-weight:600;font-size:.88rem}
        .nav a:hover{background:var(--bg);color:var(--text)}
        .nav a.on{background:var(--green-bg);color:var(--green-dark)}
        .sb-foot{padding:12px 16px;border-top:1px solid var(--border);font-size:.8rem;color:var(--muted)}
        .sb-foot form{margin-top:6px}
        .main{margin-left:var(--sidebar);min-height:100vh}
        .topbar{height:56px;background:#fff;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 24px;position:sticky;top:0;z-index:20}
        .topbar h1{font-size:1.05rem;font-weight:800}
        .content{padding:24px;max-width:1280px}
        .card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:20px;margin-bottom:18px}
        .card-h{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px}
        .card-h h2{font-size:1rem;font-weight:800}
        .card-h p{font-size:.82rem;color:var(--muted)}
        .grid{display:grid;gap:14px}
        .g4{grid-template-columns:repeat(4,1fr)}.g3{grid-template-columns:repeat(3,1fr)}.g2{grid-template-columns:repeat(2,1fr)}
        .stat{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:16px}
        .stat .l{font-size:.75rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--muted)}
        .stat .v{font-size:1.7rem;font-weight:800;margin-top:4px}
        .stat .s{font-size:.78rem;color:var(--muted)}
        table{width:100%;border-collapse:collapse;font-size:.85rem}
        th{text-align:left;font-size:.72rem;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);padding:8px 10px;border-bottom:1px solid var(--border)}
        td{padding:9px 10px;border-bottom:1px solid #e2e8f0;vertical-align:top}
        tr:hover td{background:#f8fafc}
        .truncate{max-width:420px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .badge{display:inline-block;padding:2px 8px;border-radius:999px;font-size:.72rem;font-weight:700;border:1px solid transparent}
        .b-green{background:var(--green-bg);color:var(--green-dark);border-color:var(--green-border)}
        .b-blue{background:var(--blue-bg);color:var(--blue-dark);border-color:#bfdbfe}
        .b-gray{background:#f1f5f9;color:var(--muted);border-color:#e2e8f0}
        .b-red{background:var(--danger-bg);color:var(--danger);border-color:#fecaca}
        .b-warn{background:var(--warn-bg);color:var(--warn);border-color:#fde68a}
        .btn{display:inline-flex;align-items:center;gap:6px;padding:8px 13px;border-radius:8px;border:1px solid var(--border);background:#fff;color:var(--text);font:inherit;font-weight:600;font-size:.85rem;cursor:pointer;text-decoration:none}
        .btn:hover{border-color:var(--blue);color:var(--blue)}
        .btn-p{background:var(--green);border-color:var(--green);color:#fff}
        .btn-p:hover{background:var(--green-dark);color:#fff}
        .btn-d{color:var(--danger);border-color:#fecaca}
        .btn-d:hover{background:var(--danger-bg);color:var(--danger)}
        .btn-sm{padding:5px 9px;font-size:.78rem}
        form.inline{display:inline}
        .alert{padding:10px 14px;border-radius:8px;font-weight:600;font-size:.88rem;margin-bottom:14px}
        .a-ok{background:var(--green-bg);border:1px solid var(--green-border);color:var(--green-dark)}
        .a-err{background:var(--danger-bg);border:1px solid #fecaca;color:var(--danger)}
        .a-warn{background:var(--warn-bg);border:1px solid #fde68a;color:var(--warn)}
        .field{margin-bottom:14px}
        .field label{display:block;font-size:.8rem;font-weight:700;margin-bottom:5px}
        .field .hint{font-size:.76rem;color:var(--muted);margin-top:4px}
        input[type=text],input[type=email],input[type=password],input[type=number],input[type=date],input[type=url],input[type=file],select,textarea{width:100%;padding:9px 11px;border:1.5px solid var(--border);border-radius:8px;font:inherit;font-size:.9rem;background:#fff}
        input:focus,select:focus,textarea:focus{outline:none;border-color:var(--green)}
        textarea{resize:vertical;min-height:90px}
        .check{display:flex;align-items:center;gap:8px;font-size:.88rem;font-weight:600}
        .check input{width:auto}
        .filters{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:14px}
        .filters input,.filters select{width:auto;min-width:140px;padding:7px 10px;font-size:.85rem}
        .bars{display:flex;flex-direction:column;gap:6px}
        .bar{display:grid;grid-template-columns:160px 1fr 50px;gap:8px;align-items:center;font-size:.82rem}
        .bar .t{height:10px;background:#e2e8f0;border-radius:999px;overflow:hidden}
        .bar .f{height:100%;background:var(--green)}
        .spark{display:flex;align-items:flex-end;gap:4px;height:70px}
        .spark div{flex:1;background:var(--blue);border-radius:3px 3px 0 0;min-height:2px;position:relative}
        .spark div span{position:absolute;bottom:-18px;left:0;right:0;text-align:center;font-size:.6rem;color:var(--muted)}
        .mono{font-family:ui-monospace,Menlo,Consolas,monospace;font-size:.8rem;white-space:pre-wrap;background:#f8fafc;border:1px solid var(--border);border-radius:8px;padding:10px;max-height:320px;overflow:auto}
        .menu-btn{display:none}
        @media (max-width:900px){.sidebar{transform:translateX(-100%);transition:.2s}.sidebar.open{transform:none}.main{margin-left:0}.menu-btn{display:inline-block}.g4,.g3,.g2{grid-template-columns:1fr 1fr}.content{padding:14px}}
        @media (max-width:600px){.g4,.g3,.g2{grid-template-columns:1fr}}
    </style>
</head>
<body>
<aside class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="brand"><img src="/logo.svg" alt=""><span>Huru<small>Admin panel</small></span></a>
    <nav class="nav">
        <div class="sec">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">📊 Dashboard</a>
        <a href="{{ route('admin.conversations.index') }}" class="{{ request()->routeIs('admin.conversations.*') ? 'on' : '' }}">💬 Conversations</a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'on' : '' }}">👥 Citizens</a>
        <div class="sec">Knowledge</div>
        <a href="{{ route('admin.knowledge.index') }}" class="{{ request()->routeIs('admin.knowledge.*') ? 'on' : '' }}">📚 Knowledge base</a>
        <a href="{{ route('admin.contacts.index') }}" class="{{ request()->routeIs('admin.contacts.*') ? 'on' : '' }}">📞 National directory</a>
        <a href="{{ route('admin.resources.index') }}" class="{{ request()->routeIs('admin.resources.*') ? 'on' : '' }}">📍 Local services</a>
        <a href="{{ route('admin.templates.index') }}" class="{{ request()->routeIs('admin.templates.*') ? 'on' : '' }}">🧭 Personas</a>
        <div class="sec">System</div>
        <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'on' : '' }}">⚙️ Settings</a>
        <a href="{{ route('welcome') }}" target="_blank">🌍 Public site ↗</a>
    </nav>
    <div class="sb-foot">
        {{ auth()->user()->name }}<br><span style="font-size:.72rem">{{ auth()->user()->email }}</span>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-sm" type="submit">Sign out</button></form>
    </div>
</aside>
<div class="main">
    <div class="topbar">
        <div style="display:flex;gap:10px;align-items:center"><button class="btn btn-sm menu-btn" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button><h1>{{ $title ?? 'Dashboard' }}</h1></div>
        <div style="font-size:.78rem;color:var(--muted)">{{ now()->timezone('Africa/Dar_es_Salaam')->format('D, d M Y H:i') }} EAT</div>
    </div>
    <div class="content">
        @if(session('success'))<div class="alert a-ok">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert a-err">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="alert a-err">{{ $errors->first() }}</div>@endif
        @yield('content')
    </div>
</div>
</body>
</html>
