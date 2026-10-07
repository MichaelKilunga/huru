@extends('layouts.admin', ['title' => 'Citizens'])

@section('content')
<div class="card">
    <form class="filters" method="GET">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Phone or name">
        <select name="region"><option value="">All regions</option>@foreach(\App\Services\TanzaniaContext::REGIONS as $r)<option value="{{ $r }}" @selected(request('region') === $r)>{{ ucwords($r) }}</option>@endforeach</select>
        <label class="check"><input type="checkbox" name="banned" value="1" @checked(request('banned'))> Blocked only</label>
        <button class="btn" type="submit">Filter</button><a class="btn" href="{{ route('admin.users.index') }}">Reset</a>
    </form>
    <table>
        <thead><tr><th>Phone</th><th>Name</th><th>Lang</th><th>Region</th><th>Messages</th><th>Strikes</th><th>Last seen</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($users as $u)
            <tr>
                <td style="white-space:nowrap"><a href="{{ route('admin.conversations.show', $u) }}">{{ $u->phone_number }}</a></td>
                <td>{{ $u->name ?: '—' }}</td>
                <td>{{ strtoupper($u->preferred_language ?? 'auto') }}</td>
                <td>{{ $u->region ? ucwords($u->region) : '—' }}</td>
                <td>{{ $u->messages_count }}</td>
                <td>{{ $u->abuse_count }}</td>
                <td style="white-space:nowrap;color:var(--muted)">{{ $u->last_seen_at?->diffForHumans() ?? '—' }}</td>
                <td>@if($u->is_banned)<span class="badge b-red">blocked</span>@elseif($u->opted_out)<span class="badge b-warn">opted out</span>@else<span class="badge b-green">active</span>@endif</td>
                <td style="white-space:nowrap">
                    <form class="inline" method="POST" action="{{ route('admin.users.ban', $u) }}">@csrf @method('PATCH')<button class="btn btn-sm {{ $u->is_banned ? '' : 'btn-d' }}" type="submit">{{ $u->is_banned ? 'Unblock' : 'Block' }}</button></form>
                    <form class="inline" method="POST" action="{{ route('admin.users.strikes', $u) }}">@csrf @method('PATCH')<button class="btn btn-sm" type="submit">Clear strikes</button></form>
                    <form class="inline" method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Delete this citizen and all their messages?')">@csrf @method('DELETE')<button class="btn btn-sm btn-d" type="submit">Delete</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="9" style="text-align:center;color:var(--muted)">No citizens yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $users->links('pagination.simple') }}
</div>
@endsection
