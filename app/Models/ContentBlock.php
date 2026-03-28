<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\Services\CmsService;

class ContentBlock extends Model
{
    protected $fillable = ['page', 'key', 'value_en', 'value_ar', 'type', 'is_rich_text', 'label'];

    protected $casts = ['is_rich_text' => 'boolean'];

    public function getValueAttribute(): ?string
    {
        if ($this->type === 'image') {
            $path = $this->value_en;
            if (! $path) return null;
            return str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
        }
        return App::getLocale() === 'ar' ? ($this->value_ar ?: $this->value_en) : ($this->value_en ?? '');
    }

    protected static function booted(): void
    {
        static::saved(fn () => CmsService::invalidateForeverCaches());
        static::deleted(fn () => CmsService::invalidateForeverCaches());
    }
}
