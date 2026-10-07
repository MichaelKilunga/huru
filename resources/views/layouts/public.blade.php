<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Huru SMS' }} – Huru SMS</title>
    <meta name="description" content="{{ $description ?? 'Huru SMS – uliza lolote kwa SMS 15054 au mtandaoni.' }}">
    <meta name="robots" content="{{ $robots ?? 'index, follow' }}">
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#15803d">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--green:#15803d;--green-dark:#166534;--green-bg:#f0fdf4;--green-border:#bbf7d0;--blue:#1d4ed8;--bg:#f8fafc;--card:#fff;--text:#0f172a;--muted:#475569;--border:#cbd5e1}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text);line-height:1.6}
        header{background:#fff;border-bottom:1px solid var(--border)}
        .nav{max-width:960px;margin:0 auto;padding:0 16px;height:60px;display:flex;align-items:center;justify-content:space-between}
        .brand{display:flex;align-items:center;gap:10px;text-decoration:none;color:var(--green-dark);font-weight:800;font-size:1.2rem}
        .brand img{width:32px;height:32px;border-radius:8px}
        .nav a.btn{background:var(--green);color:#fff;text-decoration:none;font-weight:700;padding:8px 14px;border-radius:8px;font-size:.9rem}
        .sms{font-size:.85rem;color:var(--green-dark);font-weight:700;margin-right:12px}
        main{max-width:960px;margin:0 auto;padding:32px 16px 48px}
        .card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:28px}
        h1{font-size:1.7rem;font-weight:800;margin-bottom:6px}
        h2{font-size:1.1rem;font-weight:800;margin:22px 0 8px;color:var(--green-dark)}
        p,li{font-size:.95rem;color:#1e293b}
        ul{padding-left:20px}
        li{margin-bottom:6px}
        .updated{font-size:.8rem;color:var(--muted);margin-bottom:12px}
        a{color:var(--blue)}
        em{color:var(--muted)}
        footer{text-align:center;font-size:.85rem;color:var(--muted);padding:20px 16px;border-top:1px solid var(--border);background:#fff}
        footer a{color:var(--muted);margin:0 8px;text-decoration:none}
        @media (max-width:600px){.sms{display:none}.card{padding:20px}}
    </style>
</head>
<body>
<header>
    <div class="nav">
        <a href="{{ route('welcome') }}" class="brand"><img src="/logo.svg" alt="">Huru SMS</a>
        <div><span class="sms">SMS: HURU → 15054</span><a class="btn" href="{{ route('chat.index') }}">Web Chat</a></div>
    </div>
</header>
<main>
    <div class="card">
        @yield('content')
    </div>
</main>
<footer>
    © {{ date('Y') }} Huru Digital Co. Ltd. · SMS HURU kwenda 15054
    <div style="margin-top:6px"><a href="{{ route('legal.terms') }}">Vigezo na Masharti</a><a href="{{ route('legal.privacy') }}">Sera ya Faragha</a><a href="{{ route('community.index') }}">Jamii</a></div>
</footer>
</body>
</html>
