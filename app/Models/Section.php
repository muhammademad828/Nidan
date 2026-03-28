<?php

namespace App\Models;

use App\Services\CmsService;
use Illuminate\Database\Eloquent\Model;

/**
 * Home (and future) layout sections: ordering & visibility for Blade includes.
 *
 * @property string $page
 * @property string $name
 * @property string $component_name
 * @property bool $is_visible
 * @property int $order
 */
class Section extends Model
{
    protected $table = 'site_sections';

    protected $fillable = [
        'page', 'name', 'component_name', 'is_visible', 'order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function scopeForPage($query, string $page)
    {
        return $query->where('page', $page);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }

    protected static function booted(): void
    {
        static::saved(fn () => CmsService::invalidateForeverCaches());
        static::deleted(fn () => CmsService::invalidateForeverCaches());
    }
}
