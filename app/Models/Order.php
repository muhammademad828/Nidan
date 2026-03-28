<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\City;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'region_id', 'city_id', 'status',
        'subtotal', 'delivery_fee', 'tax_amount', 'discount_amount', 'total', 'currency',
        'company_name', 'contact_person', 'contact_phone',
        'is_gift', 'is_surprise', 'notes', 'is_read',
    ];

    protected $casts = [
        'subtotal'         => 'decimal:2',
        'delivery_fee'     => 'decimal:2',
        'tax_amount'       => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'total'            => 'decimal:2',
        'is_gift'          => 'boolean',
        'is_surprise'      => 'boolean',
        'is_read'          => 'boolean',
    ];

    /* ── Relationships ── */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function giftDetail(): HasOne
    {
        return $this->hasOne(GiftDetail::class);
    }

    public function deliveryDetail(): HasOne
    {
        return $this->hasOne(DeliveryDetail::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    /* ── Scopes ── */

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSurpriseGifts($query)
    {
        return $query->where('is_surprise', true);
    }

    /* ── Helpers ── */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function canTransitionTo(string $newStatus): bool
    {
        $allowed = [
            'pending'   => ['paid', 'cancelled'],
            'paid'      => ['delivered', 'cancelled'],
            'delivered' => [],
            'cancelled' => [],
        ];

        return in_array($newStatus, $allowed[$this->status] ?? []);
    }
}
