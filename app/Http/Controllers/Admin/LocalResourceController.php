<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LocalResource;
use App\Services\TanzaniaContext;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocalResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = LocalResource::query()->orderBy('region')->orderBy('district')->orderBy('name');

        if ($request->filled('q')) {
            $q = '%' . $request->string('q') . '%';
            $query->where(fn ($w) => $w->where('name', 'like', $q)->orWhere('location', 'like', $q)->orWhere('district', 'like', $q));
        }
        if ($request->filled('region')) {
            $query->where('region', Str::lower($request->string('region')));
        }
        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        return view('admin.resources.index', [
            'resources' => $query->paginate(30)->withQueryString(),
            'regions' => TanzaniaContext::REGIONS,
            'categories' => LocalResource::CATEGORIES,
            'byRegion' => LocalResource::query()->selectRaw('region, count(*) as total')->groupBy('region')->orderByDesc('total')->pluck('total', 'region'),
        ]);
    }

    public function create()
    {
        return view('admin.resources.form', ['resource' => new LocalResource(['category' => 'legal_aid', 'is_active' => true]), 'regions' => TanzaniaContext::REGIONS, 'categories' => LocalResource::CATEGORIES]);
    }

    public function store(Request $request)
    {
        LocalResource::query()->create($this->validated($request));

        return redirect()->route('admin.resources.index')->with('success', 'Resource created.');
    }

    public function edit(LocalResource $resource)
    {
        return view('admin.resources.form', ['resource' => $resource, 'regions' => TanzaniaContext::REGIONS, 'categories' => LocalResource::CATEGORIES]);
    }

    public function update(Request $request, LocalResource $resource)
    {
        $resource->update($this->validated($request));

        return redirect()->route('admin.resources.index')->with('success', 'Resource updated.');
    }

    public function destroy(LocalResource $resource)
    {
        $resource->delete();

        return back()->with('success', 'Resource deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|in:' . implode(',', array_keys(LocalResource::CATEGORIES)),
            'region' => 'required|in:' . implode(',', TanzaniaContext::REGIONS),
            'district' => 'nullable|string|max:40',
            'ward' => 'nullable|string|max:60',
            'location' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:60',
            'email' => 'nullable|email|max:120',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['district'] = $data['district'] ? Str::lower(trim($data['district'])) : null;
        $data['is_active'] = $request->boolean('is_active');
        $data['source'] = 'admin';

        return $data;
    }
}
