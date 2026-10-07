@extends('layouts.admin', ['title' => 'Local services'])

@section('content')
<div class="card">
    <div class="card-h"><div><h2>Place-bound services</h2><p>Legal aid providers, hospitals, police desks and offices by region and district. Run <code>php artisan huru:import-legal-aid</code> to pull the national legal aid list.</p></div><a class="btn btn-p" href="{{ route('admin.resources.create') }}">+ New service</a></div>
    <form class="filters" method="GET">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, district, address">
        <select name="region"><option value="">All regions</option>@foreach($regions as $r)<option value="{{ $r }}" @selected(request('region') === $r)>{{ ucwords($r) }} ({{ $byRegion[$r] ?? 0 }})</option>@endforeach</select>
        <select name="category"><option value="">All categories</option>@foreach($categories as $k => $l)<option value="{{ $k }}" @selected(request('category') === $k)>{{ $l }}</option>@endforeach</select>
        <button class="btn" type="submit">Filter</button><a class="btn" href="{{ route('admin.resources.index') }}">Reset</a>
    </form>
    <table>
        <thead><tr><th>Name</th><th>Category</th><th>Region / District</th><th>Address</th><th>Contact</th><th>Source</th><th></th></tr></thead>
        <tbody>
        @forelse($resources as $r)
            <tr>
                <td><strong>{{ $r->name }}</strong> @if(!$r->is_active)<span class="badge b-gray">off</span>@endif</td>
                <td style="white-space:nowrap">{{ $categories[$r->category] ?? $r->category }}</td>
                <td style="white-space:nowrap">{{ ucwords($r->region) }}@if($r->district) / {{ ucwords($r->district) }}@endif</td>
                <td style="font-size:.8rem;max-width:300px">{{ \Illuminate\Support\Str::limit($r->location, 120) }}</td>
                <td style="font-size:.8rem;white-space:nowrap">{{ $r->phone }}@if($r->email)<br>{{ $r->email }}@endif</td>
                <td style="font-size:.75rem;color:var(--muted)">{{ $r->source }}</td>
                <td style="white-space:nowrap">
                    <a class="btn btn-sm" href="{{ route('admin.resources.edit', $r) }}">Edit</a>
                    <form class="inline" method="POST" action="{{ route('admin.resources.destroy', $r) }}" onsubmit="return confirm('Delete this service?')">@csrf @method('DELETE')<button class="btn btn-sm btn-d" type="submit">Delete</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center;color:var(--muted)">No services match.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $resources->links('pagination.simple') }}
</div>
@endsection
