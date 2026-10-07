@extends('layouts.admin', ['title' => 'Personas'])

@section('content')
<div class="card">
    <div class="card-h"><div><h2>Persona templates</h2><p>One active persona per topic and language. Topics without a persona fall back to the general one. Placeholders: {app_name}, {category}.</p></div><a class="btn btn-p" href="{{ route('admin.templates.create') }}">+ New persona</a></div>
    <table>
        <thead><tr><th>Name</th><th>Topic</th><th>Lang</th><th>Persona</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($templates as $t)
            <tr>
                <td style="white-space:nowrap"><strong>{{ $t->name }}</strong></td>
                <td style="white-space:nowrap">{{ \App\Services\TopicClassifier::label($t->category, 'en') }}</td>
                <td>{{ strtoupper($t->language) }}</td>
                <td style="font-size:.8rem;color:var(--muted)">{{ \Illuminate\Support\Str::limit($t->template, 160) }}</td>
                <td>{!! $t->is_active ? '<span class="badge b-green">active</span>' : '<span class="badge b-gray">off</span>' !!}</td>
                <td style="white-space:nowrap">
                    <a class="btn btn-sm" href="{{ route('admin.templates.edit', $t) }}">Edit</a>
                    <form class="inline" method="POST" action="{{ route('admin.templates.toggle', $t) }}">@csrf @method('PATCH')<button class="btn btn-sm" type="submit">{{ $t->is_active ? 'Off' : 'On' }}</button></form>
                    <form class="inline" method="POST" action="{{ route('admin.templates.destroy', $t) }}" onsubmit="return confirm('Delete this persona?')">@csrf @method('DELETE')<button class="btn btn-sm btn-d" type="submit">Delete</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;color:var(--muted)">No personas yet. The built-in default will be used.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
