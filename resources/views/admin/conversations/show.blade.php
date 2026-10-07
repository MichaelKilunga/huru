@extends('layouts.admin', ['title' => 'Conversation'])

@section('content')
<div class="card">
    <div class="card-h">
        <div>
            <h2>{{ $user->name ?: 'Citizen' }} · {{ $user->phone_number }}</h2>
            <p>Language: {{ $user->preferred_language ?: 'auto' }} · Region: {{ $user->region ? ucwords($user->region) : '—' }} · Strikes: {{ $user->abuse_count }} · Last seen: {{ $user->last_seen_at?->diffForHumans() ?? '—' }} @if($user->is_banned)<span class="badge b-red">BANNED</span>@endif @if($user->opted_out)<span class="badge b-warn">opted out</span>@endif</p>
        </div>
        <div style="display:flex;gap:6px">
            <form class="inline" method="POST" action="{{ route('admin.users.ban', $user) }}">@csrf @method('PATCH')<button class="btn btn-sm {{ $user->is_banned ? '' : 'btn-d' }}" type="submit">{{ $user->is_banned ? 'Unblock' : 'Block' }}</button></form>
            <form class="inline" method="POST" action="{{ route('admin.users.strikes', $user) }}">@csrf @method('PATCH')<button class="btn btn-sm" type="submit">Clear strikes</button></form>
            <a class="btn btn-sm" href="{{ route('admin.conversations.index') }}">Back</a>
        </div>
    </div>

    @foreach($messages as $m)
        <div style="border:1px solid var(--border);border-radius:10px;padding:12px 14px;margin-bottom:10px;background:{{ $m->direction === 'inbound' ? '#fff' : 'var(--green-bg)' }}">
            <div style="display:flex;justify-content:space-between;gap:10px;font-size:.75rem;color:var(--muted);margin-bottom:6px;flex-wrap:wrap">
                <span>{!! $m->direction === 'inbound' ? '<span class="badge b-green">question</span>' : '<span class="badge b-blue">reply</span>' !!} <span class="badge b-gray">{{ $m->channel }}</span> {{ $m->category ? \App\Services\TopicClassifier::label($m->category, 'en') : '' }}</span>
                <span>{{ $m->created_at->timezone('Africa/Dar_es_Salaam')->format('d M Y H:i:s') }}</span>
            </div>
            <div style="white-space:pre-wrap;font-size:.92rem">{{ $m->content }}</div>
            @if($m->aiLog)
                <details style="margin-top:8px;font-size:.8rem;color:var(--muted)">
                    <summary style="cursor:pointer">{{ $m->aiLog->model }} · {{ $m->aiLog->status }} · {{ $m->aiLog->prompt_tokens }}+{{ $m->aiLog->completion_tokens }} tokens · {{ $m->aiLog->latency_ms }} ms @if($m->feedback) · feedback {{ $m->feedback->rating === 1 ? '👍' : '👎' }} {{ $m->feedback->comment }}@endif</summary>
                    <div class="mono" style="margin-top:6px">{{ $m->aiLog->prompt }}</div>
                </details>
            @endif
        </div>
    @endforeach
    {{ $messages->links('pagination.simple') }}
</div>
@endsection
