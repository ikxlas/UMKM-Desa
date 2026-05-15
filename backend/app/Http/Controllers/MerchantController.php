<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function index()
    {
        return response()->json(Merchant::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'logo_file' => 'nullable|image|max:2048',
            'storefront_image' => 'nullable|string',
            'storefront_file' => 'nullable|image|max:3072',
            'social_media' => 'nullable|string'
        ]);

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('merchants', 'public');
            $validated['logo'] = 'http://127.0.0.1:8000/storage/' . $path;
        } else if (empty($validated['logo'])) {
            $validated['logo'] = '/images/merchant.png'; // Fallback
        }

        if ($request->hasFile('storefront_file')) {
            $path = $request->file('storefront_file')->store('merchants', 'public');
            $validated['storefront_image'] = 'http://127.0.0.1:8000/storage/' . $path;
        }

        $merchant = Merchant::create($validated);
        return response()->json($merchant, 201);
    }

    public function show(Merchant $merchant)
    {
        return response()->json($merchant);
    }

    public function update(Request $request, Merchant $merchant)
    {
        // Gunakan POST _method=PUT untuk menghindari issue parsing multipart di PHP
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'owner_name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'logo_file' => 'nullable|image|max:2048',
            'storefront_image' => 'nullable|string',
            'storefront_file' => 'nullable|image|max:3072',
            'social_media' => 'nullable|string'
        ]);

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('merchants', 'public');
            $validated['logo'] = 'http://127.0.0.1:8000/storage/' . $path;
        }

        if ($request->hasFile('storefront_file')) {
            $path = $request->file('storefront_file')->store('merchants', 'public');
            $validated['storefront_image'] = 'http://127.0.0.1:8000/storage/' . $path;
        }

        $merchant->update($validated);
        return response()->json($merchant);
    }

    public function destroy(Merchant $merchant)
    {
        $merchant->delete();
        return response()->json(null, 204);
    }
}
