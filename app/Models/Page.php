<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'image',
        'is_active',
        'meta_title_ar',
        'meta_title_en',
        'meta_desc_ar',
        'meta_desc_en',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getContentAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->content_ar : $this->content_en;
    }

    public function getMetaTitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        return ($locale === 'ar' ? $this->meta_title_ar : $this->meta_title_en) ?? $this->title;
    }

    public function getMetaDescAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->meta_desc_ar : $this->meta_desc_en;
    }
}
