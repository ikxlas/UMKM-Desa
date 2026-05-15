<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon_type' => 'required|string|in:preset,custom',
            'icon_value' => 'nullable|string',
            'icon_file' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('icon_file')) {
            $path = $request->file('icon_file')->store('categories', 'public');
            $validated['icon_value'] = 'http://127.0.0.1:8000/storage/' . $path;
        } else if (empty($validated['icon_value']) && $validated['icon_type'] === 'custom') {
            $validated['icon_value'] = '/images/kripik_main.png'; // Fallback default
        }

        $category = Category::create($validated);
        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        // When using PUT/PATCH with FormData, PHP sometimes doesn't parse multipart data well.
        // It's usually better to use POST with _method=PUT, which Laravel handles transparently.
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'icon_type' => 'sometimes|required|string|in:preset,custom',
            'icon_value' => 'nullable|string',
            'icon_file' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('icon_file')) {
            $path = $request->file('icon_file')->store('categories', 'public');
            $validated['icon_value'] = 'http://127.0.0.1:8000/storage/' . $path;
        }

        $category->update($validated);
        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
