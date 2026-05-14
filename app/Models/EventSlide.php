<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en',
        'title_ar',
        'subtitle_en',
        'subtitle_ar',
        'media_type',
        'media_url',
        'sort_order',
        'is_active',
    ];

    public function getMediaUrlAttribute()
    {
        $path = $this->attributes['media_url'] ?? null;
        if (!$path) return null;

        if (str_starts_with($path, 'http')) {
            return $this->convertGoogleDriveUrl($path);
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            return '/' . $path;
        }

        return '/storage/' . $path;
    }

    private function convertGoogleDriveUrl(string $url): string
    {
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://drive.google.com/uc?export=view&id=' . $matches[1];
        }
        if (preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://drive.google.com/uc?export=view&id=' . $matches[1];
        }
        if (str_contains($url, 'drive.google.com/uc')) {
            if (!str_contains($url, 'export=view')) {
                return $url . (str_contains($url, '?') ? '&' : '?') . 'export=view';
            }
            return $url;
        }
        return $url;
    }

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getSubtitleAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->subtitle_ar : $this->subtitle_en;
    }
}
