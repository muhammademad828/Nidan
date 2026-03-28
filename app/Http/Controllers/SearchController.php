<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $q = trim($request->string('q'));

        if (strlen($q) < 2) {
            return response()->json(['products' => [], 'categories' => []]);
        }

        // SKU exact match (highest priority)
        $skuMatch = Product::active()
            ->with(['images'])
            ->where('sku', 'LIKE', $q . '%')
            ->limit(3)
            ->get();

        // Name/description search
        $nameMatches = Product::active()
            ->with(['images'])
            ->where(function ($query) use ($q) {
                $query->where('name_en', 'LIKE', '%' . $q . '%')
                      ->orWhere('name_ar', 'LIKE', '%' . $q . '%')
                      ->orWhere('short_description_en', 'LIKE', '%' . $q . '%');
            })
            ->whereNotIn('id', $skuMatch->pluck('id'))
            ->limit(9)
            ->get();

        $products = $skuMatch->concat($nameMatches)->map(fn ($p) => [
            'id'            => $p->id,
            'name'          => $p->name,
            'slug'          => $p->slug,
            'sku'           => $p->sku,
            'base_price'    => (float) $p->base_price,
            'primary_image' => $p->primary_image,
        ])->values();

        // Category search
        $categories = Category::active()
            ->where(function ($q2) use ($q) {
                $q2->where('name_en', 'LIKE', '%' . $q . '%')
                   ->orWhere('name_ar', 'LIKE', '%' . $q . '%');
            })
            ->limit(4)
            ->get()
            ->map(fn ($c) => [
                'id'   => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
            ]);

        return response()->json(compact('products', 'categories'));
    }
}
