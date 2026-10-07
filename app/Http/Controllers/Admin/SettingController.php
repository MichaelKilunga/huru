<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings', [
            'groups' => Settings::groups(),
            'values' => Settings::all(),
        ]);
    }

    public function update(Request $request)
    {
        $rules = Settings::rules();

        // Unchecked checkboxes are absent from the request: treat as "0".
        foreach (Settings::schema() as $key => $def) {
            if ($def['type'] === 'boolean' && ! $request->has($key)) {
                $request->merge([$key => '0']);
            }
        }

        $data = $request->validate($rules);

        foreach ($data as $key => $value) {
            Settings::set($key, $value);
        }

        return back()->with('success', 'Settings saved.');
    }
}
