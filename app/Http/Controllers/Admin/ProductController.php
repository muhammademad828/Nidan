<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'vendor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('name_en', 'like', "%$search%")
                ->orWhere('name_ar', 'like', "%$search%"));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $products = $query->orderBy('id', 'desc')->paginate(20);
        $categories = Category::active()->orderBy('name_en')->get();
        $vendors = Vendor::active()->orderBy('name')->get();
        $tags = \App\Models\Tag::orderBy('name_en')->get();

        return view('admin.products.index', compact('products', 'categories', 'vendors', 'tags'));
    }

    public function show(Product $product): RedirectResponse
    {
        return redirect()->route('admin.products.index');
    }

    public function store(UpsertProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = ($validated['slug'] ?? null) ?: Str::slug($validated['name_en']);
        
        // Handle Main Image
        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('products', 'public');
        }

        // Handle Gallery Images
        $gallery = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("gallery_file_$i")) {
                $gallery[] = $request->file("gallery_file_$i")->store('products/gallery', 'public');
            } elseif ($request->filled("gallery_url_$i")) {
                $gallery[] = $request->input("gallery_url_$i");
            }
        }
        $validated['images'] = $gallery;

        $product = Product::create($validated);
        
        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        }

        return back()->with('success', 'Product created with images.');
    }

    public function update(UpsertProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        if (!isset($validated['name_en'])) {
            $validated['name_en'] = $product->name_en;
        }

        $validated['slug'] = ($validated['slug'] ?? null) ?: Str::slug($validated['name_en']);

        // Handle Main Image
        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('products', 'public');
        } elseif ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        // Handle Gallery Images
        $gallery = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("gallery_file_$i")) {
                $gallery[] = $request->file("gallery_file_$i")->store('products/gallery', 'public');
            } elseif ($request->filled("gallery_url_$i")) {
                $gallery[] = $request->input("gallery_url_$i");
            }
        }
        
        if (!empty($gallery)) {
            $validated['images'] = $gallery;
        } else {
            $validated['images'] = $product->images;
        }

        $product->update($validated);

        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

}
