<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class Region extends Model
{
    protected $fillable = [
        'name_en', 'name_ar', 'slug', 'delivery_fee', 'is_active', 'display_order',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'is_active'    => 'boolean',
    ];

    /* ── Accessors ── */

    public function getNameAttribute(): string
    {
        return App::getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /* ── Relationships ── */

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function deliverySlots(): HasMany
    {
        return $this->hasMany(DeliverySlot::class);
    }

    /* ── Scopes ── */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name_en');
    }

    /* ── Cache invalidation ── */

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('regions:active'));
    }
}
