<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'cart_data',
    ];

    protected function casts(): array
    {
        return ['cart_data' => 'array'];
    }
}
