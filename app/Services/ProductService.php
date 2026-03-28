<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function getFeaturedProducts(?int $regionId = null, int $limit = 8): Collection
    {
        return Product::active()
            ->featured()
            ->forRegion($regionId)
            ->with(['images', 'category'])
            ->ordered()
            ->limit($limit)
            ->get();
    }

    /**
     * Home sections (Best Sellers / category strips) — active products in a category by slug.
     */
    public function getProductsByCategorySlug(?int $regionId, string $categorySlug, int $limit = 6): Collection
    {
        return Product::active()
            ->forRegion($regionId)
            ->whereHas('category', fn ($q) => $q->where('slug', $categorySlug))
            ->with(['images', 'category'])
            ->inStock()
            ->ordered()
            ->limit($limit)
            ->get();
    }

    public function getFilteredProducts(
        ?string $category = null,
        ?string $occasion = null,
        ?string $priceRange = null,
        string $sort = 'featured',
        ?int $regionId = null,
        int $perPage = 16,
        ?string $search = null,
    ): LengthAwarePaginator {
        $query = Product::active()
            ->forRegion($regionId)
            ->with(['images', 'category'])
            ->withoutTrashed();

        $search = $search !== null ? trim($search) : '';
        if ($search !== '') {
            $term = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('name_en', 'like', $term)
                    ->orWhere('name_ar', 'like', $term)
                    ->orWhere('slug', 'like', $term)
                    ->orWhere('sku', 'like', $term)
                    ->orWhere('short_description_en', 'like', $term)
                    ->orWhere('short_description_ar', 'like', $term)
                    ->orWhere('description_en', 'like', $term)
                    ->orWhere('description_ar', 'like', $term);
            });
        }

        if ($category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if ($priceRange) {
            [$min, $max] = explode('-', $priceRange.'-');
            if ($min) {
                $query->where('base_price', '>=', (float) $min);
            }
            if ($max) {
                $query->where('base_price', '<=', (float) $max);
            }
        }

        $query = match ($sort) {
            'newest' => $query->latest(),
            'price_asc' => $query->orderBy('base_price'),
            'price_desc' => $query->orderByDesc('base_price'),
            default => $query->orderByDesc('is_featured')->ordered(),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    public function getProductBySlug(string $slug): ?Product
    {
        return Product::active()
            ->with(['images', 'variations', 'addons', 'category', 'region'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function toCardArray(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'short_description' => $product->short_description,
            'base_price' => (float) $product->base_price,
            'compare_at_price' => $product->compare_at_price ? (float) $product->compare_at_price : null,
            'primary_image' => $product->primary_image,
            'stock_status' => $product->stock_status,
            'category' => $product->category?->name,
            'is_giftable' => $product->is_giftable,
        ];
    }

    /**
     * Normalize paginator / array / model rows for Blade product cards (avoids wrong field access).
     *
     * @return array{id:int,name:string,slug:string,sku:?string,short_description:?string,base_price:float,compare_at_price:?float,primary_image:?string,stock_status:?string,category:?string,is_giftable:bool}
     */
    public function normalizeProductCard(mixed $row): array
    {
        if ($row instanceof Product) {
            return $this->toCardArray($row);
        }

        if (is_array($row)) {
            return [
                'id' => (int) ($row['id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
                'slug' => (string) ($row['slug'] ?? ''),
                'sku' => isset($row['sku']) ? (string) $row['sku'] : null,
                'short_description' => isset($row['short_description']) ? (string) $row['short_description'] : null,
                'base_price' => isset($row['base_price']) ? (float) $row['base_price'] : 0.0,
                'compare_at_price' => isset($row['compare_at_price']) && $row['compare_at_price'] !== null
                    ? (float) $row['compare_at_price'] : null,
                'primary_image' => isset($row['primary_image']) ? $row['primary_image'] : null,
                'stock_status' => isset($row['stock_status']) ? (string) $row['stock_status'] : null,
                'category' => isset($row['category']) ? (is_string($row['category']) ? $row['category'] : null) : null,
                'is_giftable' => (bool) ($row['is_giftable'] ?? false),
            ];
        }

        return [
            'id' => 0,
            'name' => '',
            'slug' => '',
            'sku' => null,
            'short_description' => null,
            'base_price' => 0.0,
            'compare_at_price' => null,
            'primary_image' => null,
            'stock_status' => null,
            'category' => null,
            'is_giftable' => false,
        ];
    }
}
