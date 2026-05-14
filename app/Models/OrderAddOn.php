<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAddOn extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'add_on_id',
        'add_on_name_snapshot',
        'price_snapshot',
        'cost_price_snapshot',
        'customer_message',
    ];

    protected function casts(): array
    {
        return [
            'price_snapshot' => 'decimal:2',
            'cost_price_snapshot' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function addOn(): BelongsTo
    {
        return $this->belongsTo(AddOn::class);
    }
}
