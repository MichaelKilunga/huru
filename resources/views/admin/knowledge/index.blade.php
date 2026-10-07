@extends('layouts.admin', ['title' => 'Knowledge base'])

@section('content')
<div class="grid g2">
    <div class="card">
        <div class="card-h"><div><h2>Entries by topic</h2><p>Fact blocks the engine can cite. Keep each one short and sourced.</p></div><a class="btn btn-p" href="{{ route('admin.knowledge.create') }}">+ New entry</a></div>
        <div class="bars">
            @foreach(\App\Services\TopicClassifier::CATEGORIES as $k => $l)
                @php $n = (int) ($counts[$k] ?? 0); @endphp
                <div class="bar"><a href="{{ route('admin.knowledge.index', ['category' => $k]) }}">{{ $l['en'] }}</a><div class="t"><div class="f" style="width:{{ $counts->max() ? round($n / max(1, $counts->max()) * 100) : 0 }}%"></div></div><span>{{ $n }}</span></div>
            @endforeach
        </div>
    </div>
    <div class="card">
        <div class="card-h"><div><h2>Import</h2><p>CSV columns: title, content, category, language, keywords, source, summary. JSON: array of the same keys.</p></div></div>
        <form method="POST" action="{{ route('admin.knowledge.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="field"><label>File (.csv or .json)</label><input type="file" name="file" accept=".csv,.json,.txt" required></div>
            <div class="grid g2">
                <div class="field"><label>Default topic</label><select name="default_category">@foreach(\App\Services\TopicClassifier::CATEGORIES as $k => $l)<option value="{{ $k }}">{{ $l['en'] }}</option>@endforeach</select></div>
                <div class="field"><label>Default language</label><select name="default_language"><option value="sw">Swahili</option><option value="en">English</option></select></div>
            </div>
            <button class="btn" type="submit">Import</button>
        </form>
    </div>
</div>

<div class="card">
    <form class="filters" method="GET">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title or content">
        <select name="category"><option value="">All topics</option>@foreach(\App\Services\TopicClassifier::CATEGORIES as $k => $l)<option value="{{ $k }}" @selected(request('category') === $k)>{{ $l['en'] }}</option>@endforeach</select>
        <select name="language"><option value="">Any language</option><option value="sw" @selected(request('language') === 'sw')>Swahili</option><option value="en" @selected(request('language') === 'en')>English</option></select>
        <button class="btn" type="submit">Filter</button><a class="btn" href="{{ route('admin.knowledge.index') }}">Reset</a>
    </form>
    <table>
        <thead><tr><th>Title</th><th>Topic</th><th>Lang</th><th>Keywords</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($entries as $e)
            <tr>
                <td><strong>{{ $e->title }}</strong><div style="font-size:.78rem;color:var(--muted)">{{ \Illuminate\Support\Str::limit($e->summary ?: $e->content, 110) }}</div></td>
                <td style="white-space:nowrap">{{ \App\Services\TopicClassifier::label($e->category, 'en') }}</td>
                <td>{{ strtoupper($e->language) }}</td>
                <td style="font-size:.76rem;color:var(--muted);max-width:220px">{{ \Illuminate\Support\Str::limit(implode(', ', (array) $e->keywords), 90) }}</td>
                <td>{!! $e->is_active ? '<span class="badge b-green">active</span>' : '<span class="badge b-gray">off</span>' !!}</td>
                <td style="white-space:nowrap">
                    <a class="btn btn-sm" href="{{ route('admin.knowledge.edit', $e) }}">Edit</a>
                    <form class="inline" method="POST" action="{{ route('admin.knowledge.toggle', $e) }}">@csrf @method('PATCH')<button class="btn btn-sm" type="submit">{{ $e->is_active ? 'Off' : 'On' }}</button></form>
                    <form class="inline" method="POST" action="{{ route('admin.knowledge.destroy', $e) }}" onsubmit="return confirm('Delete this entry?')">@csrf @method('DELETE')<button class="btn btn-sm btn-d" type="submit">Delete</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;color:var(--muted)">No entries.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $entries->links('pagination.simple') }}
</div>
@endsection
