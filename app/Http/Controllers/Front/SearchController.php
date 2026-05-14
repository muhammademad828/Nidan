<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if (empty(trim($query))) {
            return response()->json(['products' => []]);
        }

        $products = Product::active()
            ->with('category')
            ->where(function ($q) use ($query) {
                $q->where('name_en', 'LIKE', "%{$query}%")
                  ->orWhere('name_ar', 'LIKE', "%{$query}%")
                  ->orWhere('description_en', 'LIKE', "%{$query}%")
                  ->orWhere('description_ar', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%")
                  ->orWhereHas('tags', function ($q2) use ($query) {
                      $q2->where('name_en', 'LIKE', "%{$query}%")
                         ->orWhere('name_ar', 'LIKE', "%{$query}%");
                  })
                  ->orWhereHas('category', function ($q3) use ($query) {
                      $q3->where('name_en', 'LIKE', "%{$query}%")
                         ->orWhere('name_ar', 'LIKE', "%{$query}%");
                  });
            })
            ->filtered()
            ->take(20)
            ->get();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'products' => $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'url' => route('product.show', $product),
                        'image' => $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : null,
                        'price' => number_format($product->selling_price, 2),
                        'sku' => $product->sku,
                        'category' => $product->category ? $product->category->name : null
                    ];
                })
            ]);
        }

        $maxPrice = Product::active()->max('selling_price') ?? 5000;

        // Optional: fallback for non-AJAX if we ever implement a full search results page
        return view('front.search.index', compact('products', 'query', 'maxPrice'));
    }
}
