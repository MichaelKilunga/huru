@extends('layouts.admin', ['title' => 'Settings'])

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <div class="grid g2">
        @foreach($groups as $group => $fields)
            <div class="card">
                <div class="card-h"><h2>{{ $group }}</h2></div>
                @foreach($fields as $key => $def)
                    @php $val = old($key, $values[$key]); @endphp
                    <div class="field">
                        @if($def['type'] === 'boolean')
                            <label class="check"><input type="checkbox" name="{{ $key }}" value="1" @checked((string) $val === '1')> {{ $def['label'] }}</label>
                        @else
                            <label for="{{ $key }}">{{ $def['label'] }}</label>
                            @if($def['type'] === 'textarea')
                                <textarea id="{{ $key }}" name="{{ $key }}" rows="2">{{ $val }}</textarea>
                            @elseif($def['type'] === 'select')
                                <select id="{{ $key }}" name="{{ $key }}">@foreach($def['options'] as $ov => $ol)<option value="{{ $ov }}" @selected((string) $val === (string) $ov)>{{ $ol }}</option>@endforeach</select>
                            @elseif($def['type'] === 'number')
                                <input type="number" step="any" id="{{ $key }}" name="{{ $key }}" value="{{ $val }}">
                            @else
                                <input type="text" id="{{ $key }}" name="{{ $key }}" value="{{ $val }}">
                            @endif
                        @endif
                        @if(!empty($def['hint']))<div class="hint">{{ $def['hint'] }}</div>@endif
                        @error($key)<div class="hint" style="color:var(--danger)">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    <button class="btn btn-p" type="submit">Save all settings</button>
</form>
@endsection
