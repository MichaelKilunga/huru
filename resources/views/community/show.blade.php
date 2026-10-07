<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $thread->title }} – Jamii ya HuruLearn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --gov-green: #15803d;
            --gov-green-dark: #166534;
            --gov-blue: #1d4ed8;
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
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Top Nav */
        nav {
            padding: 0.9rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            z-index: 100;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-btn {
            color: var(--gov-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .thread-info h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--gov-green-dark);
        }

        .thread-info p {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .btn-leave-thread {
            background: none;
            border: 1px solid #fca5a5;
            background: #fee2e2;
            color: #991b1b;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-leave-thread:hover {
            background: #fca5a5;
        }

        .chat-container {
            flex: 1;
            display: flex;
            overflow: hidden;
            position: relative;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: #ffffff;
            border-right: 1px solid var(--border-color);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        .sidebar-section h4 {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gov-green-dark);
            margin-bottom: 0.75rem;
            font-weight: 800;
        }

        .sidebar-section p {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .member-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .member-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.88rem;
            color: var(--text-main);
            font-weight: 600;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: var(--gov-green);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
        }

        /* Messages Area */
        .main-chat {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
        }

        .messages-list {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            scroll-behavior: smooth;
        }

        .message {
            display: flex;
            gap: 0.85rem;
            max-width: 82%;
        }

        .message.own {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .msg-content {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .msg-bubble {
            padding: 0.8rem 1.1rem;
            border-radius: 12px;
            font-size: 0.95rem;
            line-height: 1.5;
            background: #ffffff;
            border: 1px solid var(--border-color);
            color: var(--text-main);
        }

        .message.own .msg-bubble {
            background: var(--gov-green);
            color: #ffffff;
            border: none;
        }

        .msg-meta {
            font-size: 0.7rem;
            color: var(--text-muted);
            display: flex;
            gap: 0.6rem;
        }

        .message.own .msg-meta {
            justify-content: flex-end;
            color: var(--text-muted);
        }

        /* Input Area */
        .input-area {
            padding: 1rem 1.5rem;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
        }

        .input-wrapper {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            gap: 0.75rem;
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            padding: 0.5rem 0.75rem;
            border-radius: 10px;
            align-items: center;
        }

        .input-wrapper:focus-within {
            border-color: var(--gov-green);
        }

        .input-wrapper textarea {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--text-main);
            outline: none;
            padding: 0.4rem;
            font-size: 0.95rem;
            resize: none;
            max-height: 120px;
            font-family: inherit;
        }

        .send-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--gov-green);
            color: #ffffff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }

        .send-btn:hover {
            background: var(--gov-green-dark);
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .message { max-width: 95%; }
            .messages-list { padding: 1rem; }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-left">
            <a href="{{ route('community.index') }}" class="back-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Jamii
            </a>
            <div class="thread-info">
                <h1>{{ $thread->title }}</h1>
                <p>{{ $members->count() }} Wanachama · {{ $thread->is_private ? 'Binafsi' : 'Umma' }}</p>
            </div>
        </div>
        <div class="nav-right">
            <form action="{{ route('community.leave', $thread->slug) }}" method="POST">
                @csrf
                <button type="submit" class="btn-leave-thread">Ondoka Kwenye Majadiliano</button>
            </form>
        </div>
    </nav>

    <div class="chat-container">
        <aside class="sidebar">
            <div class="sidebar-section">
                <h4>Maelezo</h4>
                <p>{{ $thread->description ?? 'Hakuna maelezo yaliyowekwa.' }}</p>
            </div>
            <div class="sidebar-section">
                <h4>Wanachama</h4>
                <div class="member-list">
                    @foreach($members as $member)
                    <div class="member-item">
                        <div class="avatar">{{ strtoupper(substr($member->name ?? $member->phone_number, 0, 1)) }}</div>
                        <span>{{ $member->name ?? $member->phone_number }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </aside>

        <main class="main-chat">
            <div class="messages-list" id="messagesList">
                @foreach($posts as $post)
                <div class="message {{ $post->user_id == $user->id ? 'own' : '' }}">
                    <div class="msg-content">
                        @if($post->user_id != $user->id)
                        <div class="msg-meta" style="margin-bottom: 0.2rem;">
                            <span style="font-weight: 700; color: var(--gov-blue);">{{ $post->user->name ?? $post->user->phone_number }}</span>
                        </div>
                        @endif
                        <div class="msg-bubble">{{ $post->content }}</div>
                        <div class="msg-meta">{{ $post->created_at->format('H:i') }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="input-area">
                <form id="chatForm" class="input-wrapper">
                    @csrf
                    <textarea id="messageInput" placeholder="Andika ujumbe wako hapa..." rows="1"></textarea>
                    <button type="submit" class="send-btn" title="Tuma Ujumbe">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        const messagesList = document.getElementById('messagesList');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');

        // Scroll to bottom
        messagesList.scrollTop = messagesList.scrollHeight;

        // Auto-expand textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const content = messageInput.value.trim();
            if(!content) return;

            messageInput.value = '';
            messageInput.style.height = 'auto';

            // Optimistic UI update
            const tempId = Date.now();
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
            
            const msgHtml = `
                <div class="message own" id="temp-${tempId}">
                    <div class="msg-content">
                        <div class="msg-bubble">${content}</div>
                        <div class="msg-meta">${time} · Inatuma...</div>
                    </div>
                </div>
            `;
            messagesList.insertAdjacentHTML('beforeend', msgHtml);
            messagesList.scrollTop = messagesList.scrollHeight;

            try {
                const response = await fetch("{{ route('community.posts.store', $thread->slug) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ content })
                });

                if(response.ok) {
                    const data = await response.json();
                    const tempMsg = document.getElementById(`temp-${tempId}`);
                    if(tempMsg) {
                        tempMsg.querySelector('.msg-meta').innerText = time;
                    }
                } else {
                    alert('Imefeli kutuma ujumbe.');
                }
            } catch (error) {
                console.error(error);
                alert('Hitilafu imetokea.');
            }
        });
    </script>
</body>
</html>
