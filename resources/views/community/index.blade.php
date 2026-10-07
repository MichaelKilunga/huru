@extends('layouts.public', ['title' => 'Jamii', 'description' => 'Jamii ya Huru SMS: jadili, shirikiana uzoefu na upate ushauri kutoka kwa wananchi wenzako.', 'robots' => 'noindex, follow'])

@section('content')
<style>
    .top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:18px}
    .top p{color:var(--muted);font-size:.9rem}
    .btn{display:inline-flex;align-items:center;gap:6px;background:var(--green);color:#fff;text-decoration:none;font-weight:700;padding:10px 14px;border-radius:8px;border:none;font:inherit;cursor:pointer;font-size:.9rem}
    .btn.alt{background:#fff;color:var(--blue);border:2px solid var(--blue)}
    .grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
    .th{border:1px solid var(--border);border-radius:10px;padding:16px;background:#fff}
    .th h3{font-size:1rem;margin-bottom:4px}
    .th h3 a{color:var(--text);text-decoration:none}
    .th p{font-size:.88rem;color:var(--muted)}
    .th .meta{display:flex;gap:10px;align-items:center;margin-top:10px;font-size:.78rem;color:var(--muted);flex-wrap:wrap}
    .badge{background:var(--green-bg);color:var(--green-dark);border:1px solid var(--green-border);padding:2px 8px;border-radius:999px;font-weight:700;font-size:.72rem}
    .badge.blue{background:#eff6ff;color:#1e40af;border-color:#bfdbfe}
    form.inline{display:inline}
    .new{border:1px dashed var(--border);border-radius:10px;padding:16px;margin-top:22px}
    .new input,.new textarea{width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font:inherit;margin-bottom:8px}
    .alert{padding:10px 12px;border-radius:8px;font-size:.88rem;font-weight:600;margin-bottom:12px}
    .alert-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
    .alert-ok{background:var(--green-bg);border:1px solid var(--green-border);color:var(--green-dark)}
    @media (max-width:640px){.grid{grid-template-columns:1fr}}
</style>

<div class="top">
    <div><h1>Jamii ya {{ $settings['app_name'] }}</h1><p>Karibu, {{ $user->displayName() }}. Jadili, uliza na ushirikiane uzoefu na wananchi wenzako.</p></div>
    <a class="btn alt" href="{{ route('chat.index') }}">← Rudi kwenye chat</a>
</div>

@if(session('error'))<div class="alert alert-err">{{ session('error') }}</div>@endif
@if(session('success'))<div class="alert alert-ok">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-err">{{ $errors->first() }}</div>@endif

<h2>Mijadala ya wazi</h2>
<div class="grid">
    @forelse($publicThreads as $thread)
        <div class="th">
            <h3><a href="{{ route('community.show', $thread) }}">{{ $thread->title }}</a> @if($thread->is_system)<span class="badge">Rasmi</span>@endif</h3>
            <p>{{ \Illuminate\Support\Str::limit($thread->description, 140) }}</p>
            <div class="meta">
                <span>{{ $thread->posts_count }} ujumbe</span><span>{{ $thread->members_count }} wanachama</span>
                @if(in_array($thread->id, $joinedIds))
                    <span class="badge blue">Umejiunga</span>
                @else
                    <form class="inline" method="POST" action="{{ route('community.join', $thread) }}">@csrf<button class="btn" type="submit" style="padding:6px 10px;font-size:.78rem">Jiunge</button></form>
                @endif
            </div>
        </div>
    @empty
        <p>Hakuna mijadala bado.</p>
    @endforelse
</div>

@if($joinedThreads->isNotEmpty())
<h2>Mijadala yangu</h2>
<div class="grid">
    @foreach($joinedThreads as $thread)
        <div class="th">
            <h3><a href="{{ route('community.show', $thread) }}">{{ $thread->title }}</a> @if($thread->is_private)<span class="badge blue">Faragha</span>@endif</h3>
            <p>{{ \Illuminate\Support\Str::limit($thread->description, 120) }}</p>
            <div class="meta"><span>{{ $thread->posts_count }} ujumbe</span><span>Jukumu: {{ $thread->pivot->role }}</span></div>
        </div>
    @endforeach
</div>
@endif

<div class="new">
    <h2 style="margin-top:0">Anzisha mjadala mpya</h2>
    <form method="POST" action="{{ route('community.threads.store') }}">
        @csrf
        <input type="text" name="title" placeholder="Kichwa cha mjadala" required minlength="3" maxlength="120">
        <textarea name="description" rows="2" placeholder="Unajadili nini? (si lazima)" maxlength="1000"></textarea>
        <label style="font-size:.88rem;color:var(--muted);display:flex;gap:8px;align-items:center;margin-bottom:10px"><input type="checkbox" name="is_private" value="1" style="width:auto;margin:0"> Mjadala wa faragha (kwa mwaliko tu)</label>
        <button class="btn" type="submit">Anzisha</button>
    </form>
</div>
@endsection
