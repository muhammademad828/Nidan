<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiftDetail extends Model
{
    protected $fillable = [
        'order_id', 'sender_name', 'sender_phone', 'sender_email',
        'recipient_name', 'recipient_phone', 'recipient_address', 'recipient_city',
        'gift_message', 'is_anonymous', 'gift_wrap_type', 'gift_card_design',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];

    protected $hidden = ['sender_phone', 'recipient_phone'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
