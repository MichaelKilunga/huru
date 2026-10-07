@extends('layouts.public', ['title' => $thread->title, 'description' => \Illuminate\Support\Str::limit($thread->description ?? $thread->title, 150), 'robots' => 'noindex, follow'])

@section('content')
<style>
    .top{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:14px}
    .top p{color:var(--muted);font-size:.9rem}
    .btn{display:inline-flex;align-items:center;gap:6px;background:var(--green);color:#fff;text-decoration:none;font-weight:700;padding:9px 13px;border-radius:8px;border:none;font:inherit;cursor:pointer;font-size:.88rem}
    .btn.alt{background:#fff;color:var(--blue);border:2px solid var(--blue)}
    .btn.muted{background:#fff;color:var(--muted);border:1px solid var(--border)}
    .members{font-size:.8rem;color:var(--muted);margin-bottom:14px}
    .posts{display:flex;flex-direction:column;gap:10px;margin:14px 0}
    .post{border:1px solid var(--border);border-radius:10px;padding:12px 14px;background:#fff}
    .post.mine{border-color:var(--green-border);background:var(--green-bg)}
    .post .who{font-size:.78rem;color:var(--muted);font-weight:600;margin-bottom:4px;display:flex;justify-content:space-between;gap:8px}
    .post .body{font-size:.95rem;white-space:pre-wrap}
    .compose textarea{width:100%;padding:11px 12px;border:1.5px solid var(--border);border-radius:8px;font:inherit;margin-bottom:8px;resize:vertical}
    .alert{padding:10px 12px;border-radius:8px;font-size:.88rem;font-weight:600;margin-bottom:12px}
    .alert-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
    .alert-ok{background:var(--green-bg);border:1px solid var(--green-border);color:var(--green-dark)}
    .notice{background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;padding:10px 12px;border-radius:8px;font-size:.88rem}
    form.inline{display:inline}
</style>

<div class="top">
    <div>
        <h1 style="font-size:1.4rem">{{ $thread->title }}</h1>
        <p>{{ $thread->description }}</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a class="btn alt" href="{{ route('community.index') }}">← Mijadala</a>
        @if($isMember && !$thread->is_system)
            <form class="inline" method="POST" action="{{ route('community.leave', $thread) }}">@csrf<button class="btn muted" type="submit">Ondoka</button></form>
        @elseif(!$isMember && !$thread->is_private)
            <form class="inline" method="POST" action="{{ route('community.join', $thread) }}">@csrf<button class="btn" type="submit">Jiunge</button></form>
        @endif
    </div>
</div>

<div class="members">Wanachama {{ $members->count() }}: {{ $members->take(8)->map->displayName()->implode(', ') }}{{ $members->count() > 8 ? ' na wengine' : '' }}</div>

@if(session('error'))<div class="alert alert-err">{{ session('error') }}</div>@endif
@if(session('success'))<div class="alert alert-ok">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-err">{{ $errors->first() }}</div>@endif
@if($thread->is_system)<div class="notice">Huu ni mjadala rasmi. Ujumbe huonekana baada ya kuhakikiwa na timu ya {{ $settings['app_name'] }}.</div>@endif

<div class="posts">
    @forelse($posts as $post)
        <div class="post {{ $post->user_id === $user->id ? 'mine' : '' }}">
            <div class="who"><span>{{ $post->user?->displayName() ?? 'Mwananchi' }}</span><span>{{ $post->created_at->diffForHumans() }}</span></div>
            <div class="body">{{ $post->content }}</div>
        </div>
    @empty
        <p style="color:var(--muted)">Hakuna ujumbe bado. Kuwa wa kwanza kuandika.</p>
    @endforelse
</div>
{{ $posts->links('pagination.simple') }}

@if($isMember)
<div class="compose">
    <form method="POST" action="{{ route('community.posts.store', $thread) }}">
        @csrf
        <textarea name="content" rows="3" placeholder="Andika ujumbe wako..." required minlength="2" maxlength="3000"></textarea>
        <button class="btn" type="submit">Tuma</button>
    </form>
</div>
@else
    <p style="color:var(--muted);font-size:.9rem">Jiunge na mjadala huu ili kuandika.</p>
@endif
@endsection
