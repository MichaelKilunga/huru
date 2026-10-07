@extends('layouts.admin', ['title' => $contact->exists ? 'Edit contact' : 'New contact'])

@section('content')
<div class="card" style="max-width:860px">
    <form method="POST" action="{{ $contact->exists ? route('admin.contacts.update', $contact) : route('admin.contacts.store') }}">
        @csrf
        @if($contact->exists) @method('PUT') @endif
        <div class="field"><label>Name</label><input type="text" name="name" value="{{ old('name', $contact->name) }}" required maxlength="150"></div>
        <div class="grid g3">
            <div class="field"><label>Category</label><select name="category">@foreach($categories as $c)<option value="{{ $c }}" @selected(old('category', $contact->category) === $c)>{{ ucfirst($c) }}</option>@endforeach</select></div>
            <div class="field"><label>Phone</label><input type="text" name="phone" value="{{ old('phone', $contact->phone) }}" maxlength="60"></div>
            <div class="field"><label>Alternative phone</label><input type="text" name="alt_phone" value="{{ old('alt_phone', $contact->alt_phone) }}" maxlength="60"></div>
        </div>
        <div class="grid g2">
            <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email', $contact->email) }}"></div>
            <div class="field"><label>Website</label><input type="url" name="website" value="{{ old('website', $contact->website) }}" placeholder="https://"></div>
        </div>
        <div class="field"><label>Description (Swahili)</label><textarea name="description_sw" rows="2">{{ old('description_sw', $contact->description_sw) }}</textarea></div>
        <div class="field"><label>Description (English)</label><textarea name="description_en" rows="2">{{ old('description_en', $contact->description_en) }}</textarea></div>
        <div class="grid g3">
            <div class="field"><label>Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order', $contact->sort_order ?? 100) }}" min="0" max="1000"></div>
            <div class="field" style="padding-top:26px"><label class="check"><input type="checkbox" name="is_emergency" value="1" @checked(old('is_emergency', $contact->is_emergency))> Emergency number</label></div>
            <div class="field" style="padding-top:26px"><label class="check"><input type="checkbox" name="verified" value="1" @checked(old('verified', (bool) $contact->verified_at))> Verified by operator</label></div>
        </div>
        <div class="field"><label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $contact->is_active))> Active</label></div>
        <button class="btn btn-p" type="submit">Save</button>
        <a class="btn" href="{{ route('admin.contacts.index') }}">Cancel</a>
    </form>
</div>
@endsection
