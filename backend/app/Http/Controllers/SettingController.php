<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        // Loop through all provided keys in the request
        foreach ($request->all() as $key => $value) {
            
            // Skip the HTTP method override field if present
            if ($key === '_method') continue;

            // Handle file uploads
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $path = $file->store('banners', 'public');
                $value = 'http://127.0.0.1:8000/storage/' . $path;
            }

            // Update or create the setting
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json([
            'message' => 'Settings updated successfully',
            'data' => Setting::all()->pluck('value', 'key')
        ]);
    }
}
