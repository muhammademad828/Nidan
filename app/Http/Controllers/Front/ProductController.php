<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ReviewService;
use App\Services\SeoService;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $locale, Product $product): View
    {
        $product->load(['category', 'vendor', 'approvedReviews']);

        $summary = ReviewService::ratingSummary($product);

        $locale = app()->getLocale();
        $jsonLd = SeoService::productJsonLd($product, $locale);

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        if ($related->count() < 4) {
            $remaining = 4 - $related->count();
            $moreProducts = Product::active()
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->inRandomOrder()
                ->limit($remaining)
                ->get();
            
            $related = $related->concat($moreProducts);
        }

        return view('front.product.show', compact(
            'product',
            'summary',
            'jsonLd',
            'related',
        ));
    }
}
