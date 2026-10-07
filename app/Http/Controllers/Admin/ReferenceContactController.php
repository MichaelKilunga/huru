<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferenceContact;
use App\Services\TopicClassifier;
use Illuminate\Http\Request;

class ReferenceContactController extends Controller
{
    public const CATEGORIES = ['police', 'legal', 'health', 'family', 'government', 'finance', 'business', 'employment', 'education', 'agriculture', 'technology', 'transport', 'general'];

    public function index(Request $request)
    {
        $query = ReferenceContact::query()->orderBy('sort_order')->orderBy('name');
        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        return view('admin.contacts.index', ['contacts' => $query->get(), 'categories' => self::CATEGORIES]);
    }

    public function create()
    {
        return view('admin.contacts.form', ['contact' => new ReferenceContact(['category' => 'government', 'is_active' => true, 'sort_order' => 100]), 'categories' => self::CATEGORIES]);
    }

    public function store(Request $request)
    {
        ReferenceContact::query()->create($this->validated($request));

        return redirect()->route('admin.contacts.index')->with('success', 'Contact created.');
    }

    public function edit(ReferenceContact $contact)
    {
        return view('admin.contacts.form', ['contact' => $contact, 'categories' => self::CATEGORIES]);
    }

    public function update(Request $request, ReferenceContact $contact)
    {
        $contact->update($this->validated($request));

        return redirect()->route('admin.contacts.index')->with('success', 'Contact updated.');
    }

    public function verify(ReferenceContact $contact)
    {
        $contact->update(['verified_at' => $contact->verified_at ? null : now()]);

        return back()->with('success', $contact->verified_at ? 'Marked as verified.' : 'Verification cleared.');
    }

    public function destroy(ReferenceContact $contact)
    {
        $contact->delete();

        return back()->with('success', 'Contact deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|in:' . implode(',', self::CATEGORIES),
            'phone' => 'nullable|string|max:60',
            'alt_phone' => 'nullable|string|max:60',
            'email' => 'nullable|email|max:120',
            'website' => 'nullable|url|max:200',
            'description_sw' => 'nullable|string|max:500',
            'description_en' => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0|max:1000',
            'is_emergency' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'verified' => 'sometimes|boolean',
        ]);

        $data['is_emergency'] = $request->boolean('is_emergency');
        $data['is_active'] = $request->boolean('is_active');
        $data['verified_at'] = $request->boolean('verified') ? now() : null;
        unset($data['verified']);

        return $data;
    }
}
