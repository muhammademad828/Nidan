<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\CmsService;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type', 'label'];

    protected static function booted(): void
    {
        static::saved(fn () => CmsService::invalidateForeverCaches());
        static::deleted(fn () => CmsService::invalidateForeverCaches());
    }
}
