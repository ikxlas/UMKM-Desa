<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with(['category', 'merchant'])->orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'unit' => 'required|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|max:5120',
            'gallery_files.*' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = 'http://127.0.0.1:8000/storage/' . $path;
        } else if (empty($validated['image'])) {
            $validated['image'] = '/images/kripik_main.png'; // Fallback
        }

        if ($request->hasFile('gallery_files')) {
            $galleryUrls = [];
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('products/gallery', 'public');
                $galleryUrls[] = 'http://127.0.0.1:8000/storage/' . $path;
            }
            $validated['gallery_images'] = $galleryUrls;
        }

        $product = Product::create($validated);
        return response()->json($product->load(['category', 'merchant']), 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['category', 'merchant']));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'merchant_id' => 'sometimes|required|exists:merchants,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
            'unit' => 'sometimes|required|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|max:5120',
            'gallery_files.*' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = 'http://127.0.0.1:8000/storage/' . $path;
        }

        if ($request->hasFile('gallery_files')) {
            $galleryUrls = [];
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('products/gallery', 'public');
                $galleryUrls[] = 'http://127.0.0.1:8000/storage/' . $path;
            }
            $validated['gallery_images'] = $galleryUrls;
        }

        $product->update($validated);
        return response()->json($product->load(['category', 'merchant']));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
