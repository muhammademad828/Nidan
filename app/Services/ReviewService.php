<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Review;

class ReviewService
{
    public static function averageRating(Product $product): ?float
    {
        $avg = Review::where('product_id', $product->id)
            ->where('status', 'approved')
            ->avg('rating');

        return $avg ? round($avg, 1) : null;
    }

    public static function ratingSummary(Product $product): array
    {
        $reviews = Review::where('product_id', $product->id)
            ->where('status', 'approved')
            ->get();

        $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

        foreach ($reviews as $review) {
            $counts[$review->rating] = ($counts[$review->rating] ?? 0) + 1;
        }

        $total = $reviews->count();

        return [
            'total' => $total,
            'counts' => $counts,
            'average' => self::averageRating($product),
        ];
    }
}