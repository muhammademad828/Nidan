<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class Bundle extends Model
{
    protected $fillable = [
        'name_en', 'name_ar', 'slug', 'description_en', 'description_ar',
        'bundle_price', 'compare_at_price', 'image_path', 'is_featured', 'is_active',
    ];

    protected $casts = [
        'bundle_price'     => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'is_featured'      => 'boolean',
        'is_active'        => 'boolean',
    ];

    public function getNameAttribute(): string
    {
        return App::getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function items(): HasMany
    {
        return $this->hasMany(BundleItem::class)->orderBy('display_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
