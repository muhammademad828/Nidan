<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'email', 'source', 'locale', 'is_active', 'confirmed_at',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'confirmed_at' => 'datetime',
    ];
}
