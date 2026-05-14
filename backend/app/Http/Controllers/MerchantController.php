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
            'social_media' => 'nullable|string'
        ]);

        $merchant = Merchant::create($validated);
        return response()->json($merchant, 201);
    }

    public function show(Merchant $merchant)
    {
        return response()->json($merchant);
    }

    public function update(Request $request, Merchant $merchant)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'owner_name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'social_media' => 'nullable|string'
        ]);

        $merchant->update($validated);
        return response()->json($merchant);
    }

    public function destroy(Merchant $merchant)
    {
        $merchant->delete();
        return response()->json(null, 204);
    }
}
