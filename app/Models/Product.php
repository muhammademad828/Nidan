<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'description_ar',
        'description_en',
        'cost_price',
        'selling_price',
        'sku',
        'category_id',
        'vendor_id',
        'is_flower',
        'is_customizable',
        'is_active',
        'image',
        'images',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'is_flower' => 'boolean',
            'is_customizable' => 'boolean',
            'is_active' => 'boolean',
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'images' => 'array',
            'stock' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($p) {
            if (empty($p->slug)) {
                $name = $p->name_en ?: $p->name_ar;
                $slug = Str::slug($name);
                
                if (empty($slug)) {
                    // If Str::slug returns empty (e.g. only Arabic), use a random string or other logic
                    // But usually name_en is provided.
                    $slug = 'product-' . time();
                }

                $originalSlug = $slug;
                $count = 1;
                while (self::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
                $p->slug = $slug;
            }
            if (empty($p->sku)) {
                do {
                    $sku = 'NID-' . strtoupper(Str::random(6));
                } while (self::where('sku', $sku)->exists());
                $p->sku = $sku;
            }
        });
    }

    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function getProfitAttribute(): float
    {
        return (float) $this->selling_price - (float) $this->cost_price;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('category_id')
                           ->orWhereHas('category', function ($q2) {
                               $q2->where('is_active', true);
                           });
                     });
    }

    public function scopeFlowers($query)
    {
        return $query->where('is_flower', true);
    }

    public function scopeFiltered($query)
    {
        return $query->when(request('min_price'), function ($q) {
            $q->where('selling_price', '>=', request('min_price'));
        })->when(request('max_price'), function ($q) {
            $q->where('selling_price', '<=', request('max_price'));
        })->when(request('sort'), function ($q) {
            switch (request('sort')) {
                case 'price_asc':
                    $q->orderBy('selling_price', 'asc');
                    break;
                case 'price_desc':
                    $q->orderBy('selling_price', 'desc');
                    break;
                case 'newest':
                    $q->orderBy('id', 'desc');
                    break;
                default:
                    $q->orderBy('id', 'desc');
                    break;
            }
        }, function ($q) {
            if (!request('sort')) {
                $q->orderBy('id', 'desc');
            }
        });
    }
}
