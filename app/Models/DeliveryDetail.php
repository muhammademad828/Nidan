<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryDetail extends Model
{
    protected $fillable = [
        'order_id', 'delivery_slot_id', 'preferred_date',
        'preferred_time_from', 'preferred_time_to',
        'special_instructions', 'delivery_area', 'delivered_at', 'delivery_notes',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'delivered_at'   => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(DeliverySlot::class, 'delivery_slot_id');
    }
}
