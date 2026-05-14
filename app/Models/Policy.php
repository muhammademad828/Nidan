<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'is_active',
        'sort_order',
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

    public function getContentAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->content_ar : $this->content_en;
    }

    public static function getTypes(): array
    {
        return ['return', 'custom_order', 'shipping', 'terms'];
    }
}
