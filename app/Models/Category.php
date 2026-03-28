<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class Category extends Model
{
    protected $fillable = [
        'parent_id', 'name_en', 'name_ar', 'slug',
        'description_en', 'description_ar', 'image_path',
        'display_order', 'is_active',
        'meta_title_en', 'meta_title_ar', 'meta_description_en', 'meta_description_ar',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* ── Accessors ── */

    public function getNameAttribute(): string
    {
        return App::getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) return null;
        return str_starts_with($this->image_path, 'http')
            ? $this->image_path
            : asset('storage/' . $this->image_path);
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

    /* ── Relationships ── */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
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

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }
}
