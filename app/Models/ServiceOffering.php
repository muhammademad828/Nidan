<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ServiceOffering extends Model
{
    protected $fillable = [
        'icon_material',
        'title_en', 'title_ar',
        'description_en', 'description_ar',
        'button_text_en', 'button_text_ar',
        'button_url',
        'display_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getTitleAttribute(): string
    {
        return App::getLocale() === 'ar' && $this->title_ar
            ? $this->title_ar
            : $this->title_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        if (App::getLocale() === 'ar' && $this->description_ar) {
            return $this->description_ar;
        }

        return $this->description_en;
    }

    public function getButtonTextAttribute(): ?string
    {
        if (App::getLocale() === 'ar' && $this->button_text_ar) {
            return $this->button_text_ar;
        }

        return $this->button_text_en;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('title_en');
    }
}
