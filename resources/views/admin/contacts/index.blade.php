@extends('layouts.admin', ['title' => 'National directory'])

@section('content')
<div class="card">
    <div class="card-h"><div><h2>Official institutions and hotlines</h2><p>Country-wide contacts the engine may quote. Unverified rows are marked; confirm them before relying on them.</p></div><a class="btn btn-p" href="{{ route('admin.contacts.create') }}">+ New contact</a></div>
    <form class="filters" method="GET">
        <select name="category" onchange="this.form.submit()"><option value="">All categories</option>@foreach($categories as $c)<option value="{{ $c }}" @selected(request('category') === $c)>{{ ucfirst($c) }}</option>@endforeach</select>
    </form>
    <table>
        <thead><tr><th>Name</th><th>Category</th><th>Phone</th><th>Email / Web</th><th>Flags</th><th></th></tr></thead>
        <tbody>
        @forelse($contacts as $c)
            <tr>
                <td><strong>{{ $c->name }}</strong><div style="font-size:.76rem;color:var(--muted)">{{ \Illuminate\Support\Str::limit($c->description_en ?: $c->description_sw, 100) }}</div></td>
                <td>{{ ucfirst($c->category) }}</td>
                <td style="white-space:nowrap">{{ $c->phone }}@if($c->alt_phone)<br>{{ $c->alt_phone }}@endif</td>
                <td style="font-size:.78rem">{{ $c->email }}@if($c->website)<br><a href="{{ $c->website }}" target="_blank" rel="noopener">{{ parse_url($c->website, PHP_URL_HOST) }}</a>@endif</td>
                <td style="white-space:nowrap">@if($c->is_emergency)<span class="badge b-red">emergency</span> @endif{!! $c->verified_at ? '<span class="badge b-green">verified</span>' : '<span class="badge b-warn">unverified</span>' !!} @if(!$c->is_active)<span class="badge b-gray">off</span>@endif</td>
                <td style="white-space:nowrap">
                    <a class="btn btn-sm" href="{{ route('admin.contacts.edit', $c) }}">Edit</a>
                    <form class="inline" method="POST" action="{{ route('admin.contacts.verify', $c) }}">@csrf @method('PATCH')<button class="btn btn-sm" type="submit">{{ $c->verified_at ? 'Unverify' : 'Verify' }}</button></form>
                    <form class="inline" method="POST" action="{{ route('admin.contacts.destroy', $c) }}" onsubmit="return confirm('Delete this contact?')">@csrf @method('DELETE')<button class="btn btn-sm btn-d" type="submit">Delete</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;color:var(--muted)">No contacts.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
