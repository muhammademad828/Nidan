<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value_en', 'value_ar', 'group'];

    public function getValueAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"value_{$locale}"} ?? $this->value_en;
    }

    public static function getByKey($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
