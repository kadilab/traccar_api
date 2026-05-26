<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_logo'    => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'app_favicon' => 'nullable|image|mimes:png,jpg,jpeg,ico|max:512',
        ]);

        // Text/color/boolean settings
        foreach ($request->settings ?? [] as $key => $value) {
            Setting::set($key, $value);
        }

        // Checkbox booleans (unchecked = not submitted)
        $booleans = ['dark_mode_default'];
        foreach ($booleans as $key) {
            if (! $request->has("settings.{$key}")) {
                Setting::set($key, '0');
            }
        }

        // File uploads
        foreach (['app_logo', 'app_favicon'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                // Delete old file
                $old = Setting::get($fileKey);
                if ($old) Storage::disk('public')->delete($old);

                $path = $request->file($fileKey)->store('settings', 'public');
                Setting::set($fileKey, $path);
            }
        }

        return back()->with('success', 'Paramètres mis à jour avec succès.');
    }
}
