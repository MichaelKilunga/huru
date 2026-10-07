@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
@if(!$health['engine_configured'])<div class="alert a-err">GEMINI_API_KEY is missing. The engine cannot answer until it is set in .env.</div>@endif
@if(!$health['sms_configured'])<div class="alert a-warn">Africa's Talking credentials are missing. SMS replies and OTP codes will not be sent (web login opens without OTP outside production).</div>@endif
@if(!$health['webhook_secret'])<div class="alert a-warn">AT_WEBHOOK_SECRET is empty. Set it and register the callback URL with ?token=… before going live.</div>@endif

<div class="grid g4">
    <div class="stat"><div class="l">Citizens</div><div class="v">{{ number_format($stats['users']) }}</div><div class="s">+{{ $stats['users_today'] }} today</div></div>
    <div class="stat"><div class="l">Questions today</div><div class="v">{{ number_format($stats['questions_today']) }}</div><div class="s">{{ number_format($stats['questions_week']) }} this week</div></div>
    <div class="stat"><div class="l">Channels (7d)</div><div class="v">{{ number_format($stats['sms_week']) }} <span style="font-size:.9rem;color:var(--muted)">SMS</span></div><div class="s">{{ number_format($stats['web_week']) }} web</div></div>
    <div class="stat"><div class="l">Tokens today</div><div class="v">{{ number_format($stats['tokens_today']) }}</div><div class="s">avg {{ $stats['latency_avg'] }} ms · {{ $stats['errors_week'] }} errors (7d)</div></div>
</div>
<div style="height:14px"></div>
<div class="grid g4">
    <div class="stat"><div class="l">Helpful votes</div><div class="v" style="color:var(--green-dark)">{{ $stats['helpful'] }}</div><div class="s">{{ $stats['unhelpful'] }} not helpful</div></div>
    <div class="stat"><div class="l">Blocked citizens</div><div class="v" style="color:var(--danger)">{{ $stats['banned'] }}</div><div class="s">abuse strike system</div></div>
    <div class="stat"><div class="l">Engine</div><div class="v" style="font-size:1rem;margin-top:10px">{{ $health['engine_configured'] ? '● Configured' : '○ Missing key' }}</div><div class="s">queue: {{ $health['queue'] }}</div></div>
    <div class="stat"><div class="l">SMS gateway</div><div class="v" style="font-size:1rem;margin-top:10px">{{ $health['sms_configured'] ? '● Configured' : '○ Not configured' }}</div><div class="s">webhook secret {{ $health['webhook_secret'] ? 'set' : 'missing' }}</div></div>
</div>
<div style="height:18px"></div>

<div class="grid g2">
    <div class="card">
        <div class="card-h"><div><h2>Questions per day</h2><p>Last 14 days, both channels</p></div></div>
        @php $max = max(1, (int) ($daily->max() ?? 1)); @endphp
        <div class="spark">
            @for($i = 13; $i >= 0; $i--)
                @php $day = now()->subDays($i)->toDateString(); $v = (int) ($daily[$day] ?? 0); @endphp
                <div style="height:{{ max(3, round($v / $max * 100)) }}%" title="{{ $day }}: {{ $v }}"><span>{{ now()->subDays($i)->format('d') }}</span></div>
            @endfor
        </div>
        <div style="height:20px"></div>
    </div>
    <div class="card">
        <div class="card-h"><div><h2>Topics this week</h2><p>Detected by the topic classifier</p></div></div>
        @php $top = max(1, (int) ($byCategory->max('total') ?? 1)); @endphp
        <div class="bars">
            @forelse($byCategory as $row)
                <div class="bar"><span>{{ $row['label'] }}</span><div class="t"><div class="f" style="width:{{ round($row['total'] / $top * 100) }}%"></div></div><span>{{ $row['total'] }}</span></div>
            @empty
                <p style="color:var(--muted);font-size:.85rem">No questions yet.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="card">
    <div class="card-h"><div><h2>Recent interactions</h2><p>Latest 25 messages across SMS and web</p></div><a class="btn btn-sm" href="{{ route('admin.conversations.index') }}">View all</a></div>
    <div style="overflow-x:auto">
    <table>
        <thead><tr><th>Time</th><th>Citizen</th><th>Ch.</th><th>Dir.</th><th>Topic</th><th>Content</th><th>Engine</th></tr></thead>
        <tbody>
        @forelse($recent as $m)
            <tr>
                <td style="white-space:nowrap;color:var(--muted)">{{ $m->created_at->timezone('Africa/Dar_es_Salaam')->format('d/m H:i') }}</td>
                <td><a href="{{ route('admin.conversations.show', $m->user_id) }}">{{ \App\Support\Phone::mask($m->user?->phone_number) }}</a>@if($m->user?->is_banned) <span class="badge b-red">banned</span>@elseif(($m->user?->abuse_count ?? 0) > 0) <span class="badge b-warn">strike {{ $m->user->abuse_count }}</span>@endif</td>
                <td><span class="badge b-gray">{{ $m->channel }}</span></td>
                <td>{!! $m->direction === 'inbound' ? '<span class="badge b-green">in</span>' : '<span class="badge b-blue">out</span>' !!}</td>
                <td>{{ $m->category ? \App\Services\TopicClassifier::label($m->category, 'en') : '—' }}</td>
                <td class="truncate" title="{{ $m->content }}">{{ $m->content }}</td>
                <td style="white-space:nowrap;font-size:.75rem;color:var(--muted)">@if($m->aiLog){{ $m->aiLog->total_tokens }} tok · {{ $m->aiLog->latency_ms }} ms @if($m->aiLog->status !== 'ok')<span class="badge b-red">{{ $m->aiLog->status }}</span>@endif @if($m->feedback) {{ $m->feedback->rating === 1 ? '👍' : '👎' }}@endif @endif</td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center;color:var(--muted)">No messages yet. Send HURU to the shortcode or open the web chat.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
