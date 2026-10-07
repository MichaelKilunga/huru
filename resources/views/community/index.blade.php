<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamii ya HuruLearn – Majadiliano ya Sheria</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --gov-green: #15803d;
            --gov-green-dark: #166534;
            --gov-blue: #1d4ed8;
            --gov-blue-dark: #1e40af;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #475569;
            --border-color: #cbd5e1;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* Top Nav */
        nav {
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--gov-green-dark);
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800;
            font-size: 1.25rem;
        }

        .logo img {
            width: 34px;
            height: 34px;
            border-radius: 6px;
        }

        .btn-nav-back {
            background: #ffffff;
            border: 1.5px solid var(--gov-blue);
            color: var(--gov-blue);
            padding: 0.45rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-nav-back:hover {
            background: #eff6ff;
        }

        /* Main Container */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }

        header {
            margin-bottom: 2.5rem;
        }

        header h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--gov-green-dark);
            margin-bottom: 0.4rem;
        }

        header p {
            color: var(--text-muted);
            font-size: 1.05rem;
            font-weight: 500;
        }

        .section-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--text-main);
        }

        .section-title span {
            width: 4px;
            height: 20px;
            background: var(--gov-green);
            border-radius: 2px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3.5rem;
        }

        .card {
            background: var(--card-bg);
            border: 1.5px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(15,23,42,0.04);
            transition: border-color 0.2s;
        }

        .card:hover {
            border-color: var(--gov-green);
        }

        .tag {
            display: inline-block;
            padding: 0.25rem 0.65rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.85rem;
            width: fit-content;
        }

        .tag-system {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .tag-public {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .card h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.2rem;
            color: var(--text-main);
            margin-bottom: 0.6rem;
            font-weight: 700;
        }

        .card p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 1.25rem;
            flex-grow: 1;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.25rem;
            border-radius: 6px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
            border: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--gov-green);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: var(--gov-green-dark);
        }

        .btn-outline {
            background: #ffffff;
            border: 1.5px solid var(--gov-green);
            color: var(--gov-green);
        }

        .btn-outline:hover {
            background: #f0fdf4;
        }

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--gov-green);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(21,128,61,0.35);
            cursor: pointer;
            transition: background 0.2s;
            z-index: 100;
            border: none;
        }

        .fab:hover {
            background: var(--gov-green-dark);
        }

        .fab svg {
            width: 28px;
            height: 28px;
        }

        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1.5rem;
        }

        .modal {
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: 12px;
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(15,23,42,0.15);
        }

        .modal h2 {
            font-family: 'Space Grotesk', sans-serif;
            color: var(--gov-green-dark);
            margin-bottom: 1.25rem;
            font-size: 1.4rem;
            font-weight: 800;
        }

        .form-group {
            margin-bottom: 1.25rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 0.88rem;
            color: var(--text-main);
            margin-bottom: 0.4rem;
            font-weight: 700;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: 6px;
            padding: 0.75rem 0.9rem;
            color: var(--text-main);
            font-size: 0.95rem;
            outline: none;
            font-family: inherit;
        }

        .form-group input:focus, .form-group textarea:focus {
            border-color: var(--gov-green);
        }

        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
            header h1 { font-size: 1.75rem; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="/" class="logo">
            <img src="/logo.svg" alt="HuruLearn">
            <span>Jamii ya HuruLearn</span>
        </a>
        <a href="{{ route('chat.index') }}" class="btn-nav-back">Rudi kwenye Chat</a>
    </nav>

    <div class="container">
        <header>
            <h1>Jamii ya HuruLearn</h1>
            <p>Ungana na wananchi na wadau wa sheria. Shiriki kwenye majadiliano ya haki na katiba!</p>
        </header>

        @if(session('error'))
            <div style="background: #fee2e2; border: 1px solid #fca5a5; padding: 1rem; border-radius: 8px; color: #991b1b; margin-bottom: 2rem; font-size: 0.9rem; font-weight: 600;">
                {{ session('error') }}
            </div>
        @endif

        @if($joinedThreads->count() > 0)
            <div class="section-title"><span></span> Majadiliano Yako</div>
            <div class="grid">
                @foreach($joinedThreads as $thread)
                <div class="card">
                    <div>
                        <span class="tag {{ $thread->is_system ? 'tag-system' : 'tag-public' }}">
                            {{ $thread->is_system ? 'Rasmi' : 'Jamii' }}
                        </span>
                        <h3>{{ $thread->title }}</h3>
                        <p>{{ Str::limit($thread->description, 100) }}</p>
                    </div>
                    <a href="{{ route('community.show', $thread->slug) }}" class="btn btn-primary">Fungua Majadiliano</a>
                </div>
                @endforeach
            </div>
        @endif

        <div class="section-title"><span></span> Majadiliano ya Umma</div>
        <div class="grid">
            @foreach($publicThreads as $thread)
                @if(!$joinedThreads->contains($thread))
                <div class="card">
                    <div>
                        <span class="tag {{ $thread->is_system ? 'tag-system' : 'tag-public' }}">
                            {{ $thread->is_system ? 'Rasmi' : 'Jamii' }}
                        </span>
                        <h3>{{ $thread->title }}</h3>
                        <p>{{ Str::limit($thread->description, 100) }}</p>
                    </div>
                    <form action="{{ route('community.join', $thread->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="width: 100%;">Jiunge na Majadiliano</button>
                    </form>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Create Thread FAB -->
    <button class="fab" title="Anzisha Majadiliano Mapya" onclick="document.getElementById('createModal').style.display='flex'">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    </button>

    <!-- Create Thread Modal -->
    <div class="modal-overlay" id="createModal" onclick="if(event.target == this) this.style.display='none'">
        <div class="modal">
            <h2>Anzisha Majadiliano Mapya</h2>
            <form action="{{ route('community.threads.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Mada ya Majadiliano</label>
                    <input type="text" name="title" placeholder="Mfano: Haki za Mpangaji na Mwenye Nyumba" required>
                </div>
                <div class="form-group">
                    <label>Maelezo ya Majadiliano</label>
                    <textarea name="description" rows="3" placeholder="Eleza kwa ufupi mada hii inahusu nini..."></textarea>
                </div>
                <div class="form-group" style="display: flex; align-items: center; gap: 0.6rem;">
                    <input type="checkbox" name="is_private" id="is_private" style="width: auto;">
                    <label for="is_private" style="margin-bottom: 0; font-weight: 500;">Fanya majadiliano haya yawe ya binafsi</label>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" class="btn btn-outline" style="flex: 1;" onclick="document.getElementById('createModal').style.display='none'">Ghairi</button>
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Tuma Mada</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
