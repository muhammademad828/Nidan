<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $tagSlug = request('tag');
        $tag = null;
        $taggedProducts = null;

        if ($tagSlug) {
            $tag = \App\Models\Tag::where('slug', $tagSlug)->firstOrFail();
            $taggedProducts = $tag->products()->active()->filtered()->paginate(12);
        }

        $categories = Category::active()
            ->withCount('activeProducts')
            ->orderBy('sort_order')
            ->get();

        $maxPrice = Product::active()->max('selling_price') ?? 5000;

        return view('front.category.index', compact('categories', 'tag', 'taggedProducts', 'maxPrice'));
    }

    public function show(string $locale, Category $category): View
    {
        if (!$category->is_active) {
            abort(404);
        }

        $products = Product::active()
            ->where('category_id', $category->id)
            ->filtered()
            ->paginate(12);

        $maxPrice = Product::active()->where('category_id', $category->id)->max('selling_price') ?? 5000;

        return view('front.category.show', compact('category', 'products', 'maxPrice'));
    }
}
