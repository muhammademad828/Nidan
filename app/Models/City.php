<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['governorate_id', 'name_en', 'name_ar', 'shipping_price'];

    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
}
