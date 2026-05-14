<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name_snapshot',
        'selling_price_snapshot',
        'cost_price_snapshot',
        'quantity',
        'custom_notes',
    ];

    protected function casts(): array
    {
        return [
            'selling_price_snapshot' => 'decimal:2',
            'cost_price_snapshot' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function addons(): HasMany
    {
        return $this->hasMany(OrderItemAddOn::class);
    }

    public function getLineTotalAttribute(): float
    {
        $addonsUnitTotal = $this->addons->sum(fn ($a) => (float) $a->price_snapshot);

        return ((float) $this->selling_price_snapshot + $addonsUnitTotal) * $this->quantity;
    }

    public function getLineCostAttribute(): float
    {
        return (float) $this->cost_price_snapshot * $this->quantity;
    }
}
