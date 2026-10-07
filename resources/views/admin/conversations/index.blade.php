@extends('layouts.admin', ['title' => 'Conversations'])

@section('content')
<div class="card">
    <form class="filters" method="GET">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search text, phone or name">
        <select name="category"><option value="">All topics</option>@foreach(\App\Services\TopicClassifier::CATEGORIES as $k => $l)<option value="{{ $k }}" @selected(request('category') === $k)>{{ $l['en'] }}</option>@endforeach</select>
        <select name="channel"><option value="">All channels</option><option value="sms" @selected(request('channel') === 'sms')>SMS</option><option value="web" @selected(request('channel') === 'web')>Web</option></select>
        <select name="direction"><option value="">Both directions</option><option value="inbound" @selected(request('direction') === 'inbound')>Questions</option><option value="outbound" @selected(request('direction') === 'outbound')>Replies</option></select>
        <input type="date" name="date" value="{{ request('date') }}">
        <button class="btn" type="submit">Filter</button>
        <a class="btn" href="{{ route('admin.conversations.index') }}">Reset</a>
    </form>
    <div style="overflow-x:auto">
    <table>
        <thead><tr><th>Time</th><th>Citizen</th><th>Ch.</th><th>Dir.</th><th>Lang</th><th>Topic</th><th>Content</th><th>Engine</th></tr></thead>
        <tbody>
        @forelse($messages as $m)
            <tr>
                <td style="white-space:nowrap;color:var(--muted)">{{ $m->created_at->timezone('Africa/Dar_es_Salaam')->format('d/m/y H:i') }}</td>
                <td style="white-space:nowrap"><a href="{{ route('admin.conversations.show', $m->user_id) }}">{{ $m->user?->name ?: \App\Support\Phone::mask($m->user?->phone_number) }}</a></td>
                <td><span class="badge b-gray">{{ $m->channel }}</span></td>
                <td>{!! $m->direction === 'inbound' ? '<span class="badge b-green">in</span>' : '<span class="badge b-blue">out</span>' !!}</td>
                <td>{{ strtoupper($m->language ?? '') }}</td>
                <td style="white-space:nowrap">{{ $m->category ? \App\Services\TopicClassifier::label($m->category, 'en') : '—' }}</td>
                <td class="truncate" title="{{ $m->content }}">{{ $m->content }}</td>
                <td style="white-space:nowrap;font-size:.75rem;color:var(--muted)">@if($m->aiLog){{ $m->aiLog->model }} · {{ $m->aiLog->total_tokens }} tok · {{ $m->aiLog->latency_ms }} ms @if($m->aiLog->status !== 'ok')<span class="badge b-red">{{ $m->aiLog->status }}</span>@endif @endif @if($m->feedback) {{ $m->feedback->rating === 1 ? '👍' : '👎' }}@endif</td>
            </tr>
        @empty
            <tr><td colspan="8" style="text-align:center;color:var(--muted)">Nothing matches.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    {{ $messages->links('pagination.simple') }}
</div>
@endsection
