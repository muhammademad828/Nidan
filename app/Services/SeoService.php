<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class SeoService
{
    public static function productJsonLd(Product $product, string $locale = 'en'): array
    {
        $price = $locale === 'ar'
            ? $product->selling_price . ' EGP'
            : $product->selling_price . ' EGP';

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $locale === 'ar' ? $product->name_ar : $product->name_en,
            'description' => $locale === 'ar' ? $product->description_ar : $product->description_en,
            'image' => $product->image,
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->selling_price,
                'priceCurrency' => 'EGP',
                'availability' => $product->stock > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
            ],
        ];
    }

    public static function localBusinessJsonLd(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => 'Nidan Atelier',
            'description' => 'Luxury bilingual premium gifting platform in Egypt',
            'url' => config('app.url'),
            'areaServed' => [
                '@type' => 'State',
                'name' => 'Menoufia, Egypt',
            ],
        ];
    }

    public static function getProductTitle(Product $product): string
    {
        return ($product->seo_title ?? $product->name) . ' - شراء أونلاين | نيدان أتيليه';
    }

    public static function getProductDescription(Product $product): string
    {
        $desc = $product->seo_description ?? Str::limit($product->description, 150);
        return $desc . ' - توصيل للمنوفية وجميع محافظات مصر';
    }

    public static function getCategoryTitle(Category $category): string
    {
        return ($category->seo_title ?? $category->name) . ' - شراء أونلاين | نيدان أتيليه';
    }

    public static function getCategoryDescription(Category $category): string
    {
        $desc = $category->seo_description ?? Str::limit($category->description, 150);
        return $desc . ' - توصيل للمنوفية وجميع محافظات مصر';
    }

    public static function metaTags(array $data): string
    {
        $title = e($data['title'] ?? '');
        $description = e($data['description'] ?? '');
        $image = e($data['image'] ?? '');

        $tags = "<title>{$title}</title>";
        $tags .= "<meta name=\"description\" content=\"{$description}\">";
        $tags .= "<meta property=\"og:title\" content=\"{$title}\">";
        $tags .= "<meta property=\"og:description\" content=\"{$description}\">";

        if ($image) {
            $tags .= "<meta property=\"og:image\" content=\"{$image}\">";
        }

        return $tags;
    }
}