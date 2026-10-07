@extends('layouts.admin', ['title' => $resource->exists ? 'Edit service' : 'New service'])

@section('content')
<div class="card" style="max-width:860px">
    <form method="POST" action="{{ $resource->exists ? route('admin.resources.update', $resource) : route('admin.resources.store') }}">
        @csrf
        @if($resource->exists) @method('PUT') @endif
        <div class="field"><label>Name</label><input type="text" name="name" value="{{ old('name', $resource->name) }}" required maxlength="150"></div>
        <div class="grid g3">
            <div class="field"><label>Category</label><select name="category">@foreach($categories as $k => $l)<option value="{{ $k }}" @selected(old('category', $resource->category) === $k)>{{ $l }}</option>@endforeach</select></div>
            <div class="field"><label>Region</label><select name="region">@foreach($regions as $r)<option value="{{ $r }}" @selected(old('region', $resource->region) === $r)>{{ ucwords($r) }}</option>@endforeach</select></div>
            <div class="field"><label>District</label><input type="text" name="district" value="{{ old('district', $resource->district) }}" maxlength="40" placeholder="e.g. kinondoni"></div>
        </div>
        <div class="field"><label>Ward / area</label><input type="text" name="ward" value="{{ old('ward', $resource->ward) }}" maxlength="60" placeholder="e.g. Sinza"></div>
        <div class="field"><label>Address / directions</label><textarea name="location" rows="2">{{ old('location', $resource->location) }}</textarea></div>
        <div class="grid g2">
            <div class="field"><label>Phone</label><input type="text" name="phone" value="{{ old('phone', $resource->phone) }}" maxlength="60"></div>
            <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email', $resource->email) }}"></div>
        </div>
        <div class="field"><label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $resource->is_active))> Active</label></div>
        <button class="btn btn-p" type="submit">Save</button>
        <a class="btn" href="{{ route('admin.resources.index') }}">Cancel</a>
    </form>
</div>
@endsection
