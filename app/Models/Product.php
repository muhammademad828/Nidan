<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'region_id', 'name_en', 'name_ar', 'slug',
        'short_description_en', 'short_description_ar',
        'description_en', 'description_ar',
        'base_price', 'compare_at_price', 'sku',
        'primary_image_path',
        'stock_quantity', 'stock_status',
        'is_featured', 'is_giftable', 'requires_delivery_slot', 'has_delivery_time',
        'weight_grams', 'display_order', 'is_active',
        'meta_title_en', 'meta_title_ar', 'meta_description_en', 'meta_description_ar',
    ];

    protected $casts = [
        'base_price'       => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'is_featured'      => 'boolean',
        'is_giftable'      => 'boolean',
        'requires_delivery_slot' => 'boolean',
        'has_delivery_time' => 'boolean',
        'is_active'        => 'boolean',
        'gallery'          => 'array',
    ];

    /* ── Locale-aware Accessors ── */

    public function getNameAttribute(): string
    {
        return App::getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getShortDescriptionAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->short_description_ar : $this->short_description_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function getMetaTitleAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->meta_title_ar : $this->meta_title_en;
    }

    public function getMetaDescriptionAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->meta_description_ar : $this->meta_description_en;
    }

    /**
     * Turn a DB storage path into a full public URL (same logic everywhere).
     */
    public static function publicImageUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    public function getPrimaryImageAttribute(): ?string
    {
        if ($this->primary_image_path) {
            return self::publicImageUrl($this->primary_image_path);
        }

        if (!empty($this->gallery) && is_array($this->gallery)) {
            return self::publicImageUrl($this->gallery[0]);
        }

        $path = $this->images->where('is_primary', true)->first()?->path
             ?? $this->images->first()?->path;

        return self::publicImageUrl($path);
    }

    /* ── Relationships ── */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class)->orderBy('display_order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    public function addons(): HasMany
    {
        return $this->hasMany(ProductAddon::class)->where('is_active', true);
    }

    /* ── Scopes ── */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeForRegion($query, ?int $regionId)
    {
        if ($regionId) {
            return $query->where(function ($q) use ($regionId) {
                $q->whereNull('region_id')->orWhere('region_id', $regionId);
            });
        }
        return $query->whereNull('region_id');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', '!=', 'out_of_stock');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name_en');
    }
}
