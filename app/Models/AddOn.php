<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddOn extends Model
{
    use HasFactory;

    protected $table = 'add_ons';

    protected $fillable = [
        'name_ar',
        'name_en',
        'price',
        'cost_price',
        'description_ar',
        'description_en',
        'image',
        'has_message',
        'placeholder_ar',
        'placeholder_en',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'has_message' => 'boolean',
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
        ];
    }

    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? ($this->name_ar ?? $this->name_en) : ($this->name_en ?? $this->name_ar);
    }

    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? ($this->description_ar ?? $this->description_en) : ($this->description_en ?? $this->description_ar);
    }

    public function getPlaceholderAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? ($this->placeholder_ar ?? $this->placeholder_en) : ($this->placeholder_en ?? $this->placeholder_ar);
    }

    public function orderItemAddons(): HasMany
    {
        return $this->hasMany(OrderItemAddOn::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
