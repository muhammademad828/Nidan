<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'title_en', 'title_ar',
        'subtitle_en', 'subtitle_ar',
        'image',
        'media_type',
        'media_url',
        'button_text_en', 'button_text_ar',
        'button_url',
        'secondary_button_text_en', 'secondary_button_text_ar',
        'secondary_button_url',
        'quote_en', 'quote_ar',
        'quote_author_en', 'quote_author_ar',
        'sort_order',
        'is_active'
    ];

    public function getMediaAttribute()
    {
        $path = $this->media_url ?: $this->image;
        if (!$path) return null;

        if (str_starts_with($path, 'http')) {
            return self::convertGoogleDriveUrl($path);
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            return '/' . $path;
        }

        return '/storage/' . $path;
    }

    public static function convertGoogleDriveUrl(string $url): string
    {
        // Pattern: https://drive.google.com/file/d/FILE_ID/view?...
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://drive.google.com/uc?export=view&id=' . $matches[1];
        }
        // Pattern: https://drive.google.com/open?id=FILE_ID
        if (preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://drive.google.com/uc?export=view&id=' . $matches[1];
        }
        // Pattern: https://drive.google.com/uc?id=FILE_ID (already direct)
        if (str_contains($url, 'drive.google.com/uc')) {
            // ensure export=view is set
            if (!str_contains($url, 'export=view')) {
                return $url . (str_contains($url, '?') ? '&' : '?') . 'export=view';
            }
            return $url;
        }
        return $url;
    }

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"title_{$locale}"} ?: $this->title_en;
    }

    public function getSubtitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"subtitle_{$locale}"} ?: $this->subtitle_en;
    }

    public function getButtonTextAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"button_text_{$locale}"} ?: $this->button_text_en;
    }

    public function getSecondaryButtonTextAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"secondary_button_text_{$locale}"} ?: $this->secondary_button_text_en;
    }

    public function getQuoteAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"quote_{$locale}"} ?: $this->quote_en;
    }

    public function getQuoteAuthorAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"quote_author_{$locale}"} ?: $this->quote_author_en;
    }
}
