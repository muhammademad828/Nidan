<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemAddOn extends Model
{
    use HasFactory;

    protected $table = 'order_item_addons';

    protected $fillable = [
        'order_item_id',
        'add_on_id',
        'add_on_name_snapshot',
        'price_snapshot',
        'cost_price_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'price_snapshot' => 'decimal:2',
            'cost_price_snapshot' => 'decimal:2',
        ];
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function addOn(): BelongsTo
    {
        return $this->belongsTo(AddOn::class);
    }
}
