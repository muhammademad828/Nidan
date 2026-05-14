<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    protected $fillable = ['type', 'title_en', 'title_ar', 'tag_id', 'sort_order', 'is_active'];

    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }}
