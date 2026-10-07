<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in – Huru Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body{font-family:Inter,system-ui,sans-serif;background:#f8fafc;margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:16px;color:#0f172a}
        .box{background:#fff;border:1px solid #cbd5e1;border-radius:12px;padding:28px;width:100%;max-width:380px}
        .logo{width:48px;height:48px;border-radius:12px;background:#15803d;color:#fff;font-weight:800;font-size:1.2rem;display:flex;align-items:center;justify-content:center;margin-bottom:12px}
        h1{font-size:1.2rem;margin:0 0 4px}
        p{color:#475569;font-size:.88rem;margin:0 0 16px}
        label{display:block;font-size:.8rem;font-weight:700;margin:10px 0 5px}
        input{width:100%;padding:10px 12px;border:1.5px solid #cbd5e1;border-radius:8px;font:inherit;box-sizing:border-box}
        input:focus{outline:none;border-color:#15803d}
        button{width:100%;margin-top:16px;background:#15803d;color:#fff;border:none;border-radius:8px;padding:11px;font:inherit;font-weight:700;cursor:pointer}
        button:hover{background:#166534}
        .err{background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:9px 12px;border-radius:8px;font-size:.85rem;font-weight:600;margin-bottom:8px}
        .chk{display:flex;align-items:center;gap:8px;font-size:.85rem;margin-top:10px}
        .chk input{width:auto}
    </style>
</head>
<body>
<form class="box" method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    <div class="logo">H</div>
    <h1>Huru Admin</h1>
    <p>Sign in with your administrator account.</p>
    @if($errors->any())<div class="err">{{ $errors->first() }}</div>@endif
    @if(session('error'))<div class="err">{{ session('error') }}</div>@endif
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    <label class="chk"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
    <button type="submit">Sign in</button>
</form>
</body>
</html>
