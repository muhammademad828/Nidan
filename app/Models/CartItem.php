<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id', 'product_id', 'variation_id', 'bundle_id',
        'quantity', 'unit_price', 'addons',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'addons'     => 'array',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->with('images');
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
    }

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }

    public function getTotalPriceAttribute(): float
    {
        return (float) $this->unit_price * $this->quantity;
    }
}
