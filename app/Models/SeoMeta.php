<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\Services\CmsService;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'page',
        'meta_title_en', 'meta_title_ar',
        'meta_description_en', 'meta_description_ar',
        'og_title_en', 'og_title_ar',
        'og_description_en', 'og_description_ar',
        'og_image',
    ];

    public function getMetaTitleAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->meta_title_ar : $this->meta_title_en;
    }

    public function getMetaDescriptionAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? $this->meta_description_ar : $this->meta_description_en;
    }

    public function getOgTitleAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? ($this->og_title_ar ?: $this->meta_title_ar) : ($this->og_title_en ?: $this->meta_title_en);
    }

    public function getOgDescriptionAttribute(): ?string
    {
        return App::getLocale() === 'ar' ? ($this->og_description_ar ?: $this->meta_description_ar) : ($this->og_description_en ?: $this->meta_description_en);
    }

    protected static function booted(): void
    {
        static::saved(fn () => CmsService::invalidateForeverCaches());
        static::deleted(fn () => CmsService::invalidateForeverCaches());
    }
}
