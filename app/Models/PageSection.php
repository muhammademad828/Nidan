<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\Services\CmsService;

class PageSection extends Model
{
    protected $fillable = [
        'page', 'section_key',
        'title_en', 'title_ar', 'subtitle_en', 'subtitle_ar',
        'description_en', 'description_ar',
        'button_text_en', 'button_text_ar', 'button_url',
        'background_image', 'extra_data', 'display_order', 'is_active',
    ];

    protected $casts = [
        'extra_data' => 'array',
        'is_active'  => 'boolean',
    ];

    public function getTitleAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getSubtitleAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->subtitle_ar : $this->subtitle_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function getButtonTextAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->button_text_ar : $this->button_text_en;
    }

    public function getBackgroundImageUrlAttribute(): ?string
    {
        if (! $this->background_image) return null;
        return str_starts_with($this->background_image, 'http')
            ? $this->background_image
            : asset('storage/' . $this->background_image);
    }

    protected static function booted(): void
    {
        static::saved(fn () => CmsService::invalidateForeverCaches());
        static::deleted(fn () => CmsService::invalidateForeverCaches());
    }
}
