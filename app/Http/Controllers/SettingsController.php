<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        return Setting::all();
    }

    public function show($key)
    {
        if ($key == 'special') {
            $setting = Setting::where('key', $key)->firstOrFail();
            $setting->treatAsRefillable = true;
            return $setting;
        } else {
            return Setting::where('key', $key)->firstOrFail();
        }
    }

    public function update(Request $request, $key)
    {
        $setting = Setting::where('key', $key)->firstOrFail();

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(['text', 'select', 'multiple_select', 'key_value', 'nested_select', 'nested_boolean'])],
            'options' => 'nullable|json',
            'value' => 'required',
            'enabled' => 'required|boolean',
            'affected_users' => 'required|string',
            'affected_areas' => 'required|string',
        ]);

        $setting->update($request->all());

        return $setting;
    }
}