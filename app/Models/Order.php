<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'guest_name',
        'guest_phone',
        'guest_email',
        'guest_gender',
        'city',
        'address',
        'phone',
        'status',
        'subtotal',
        'shipping_cost',
        'deposit_amount',
        'total_price',
        'net_profit',
        'notes',
        'confirmed_at',
        'delivered_at',
        'idempotency_key',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'total_price' => 'decimal:2',
            'net_profit' => 'decimal:2',
            'confirmed_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public const STATUSES = [
        'pending_confirmation',
        'confirmed',
        'in_preparation',
        'ready_for_shipping',
        'out_for_delivery',
        'delivered',
        'cancelled',
        'refunded',
    ];

    public const STATUS_LABELS_AR = [
        'pending_confirmation' => 'في انتظار التأكيد',
        'confirmed' => 'مؤكد',
        'in_preparation' => 'قيد التجهيز',
        'ready_for_shipping' => 'جاهز للشحن',
        'out_for_delivery' => 'خارج للتسليم',
        'delivered' => 'تم التسليم',
        'cancelled' => 'ملغي',
        'refunded' => 'تم رد المبالغ',
    ];

    public const STATUS_LABELS_EN = [
        'pending_confirmation' => 'Pending Confirmation',
        'confirmed' => 'Confirmed',
        'in_preparation' => 'In Preparation',
        'ready_for_shipping' => 'Ready for Shipping',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded',
    ];

    public function getStatusLabelAttribute(): string
    {
        $labels = app()->getLocale() === 'ar' ? self::STATUS_LABELS_AR : self::STATUS_LABELS_EN;
        return $labels[$this->status] ?? $this->status;
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float) $this->total_price - (float) $this->deposit_amount);
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'N/A';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Validates if the order can transition from current status to a new status.
     */
    public function canTransitionTo(string $newStatus): bool
    {
        $transitions = [
            'pending_confirmation' => ['confirmed', 'cancelled'],
            'confirmed' => ['in_preparation', 'cancelled'],
            'in_preparation' => ['ready_for_shipping', 'cancelled'],
            'ready_for_shipping' => ['out_for_delivery', 'cancelled'],
            'out_for_delivery' => ['delivered', 'cancelled'],
            'delivered' => ['refunded'], // Terminal state, only refund possible
            'cancelled' => [], // Terminal state
            'refunded' => [], // Terminal state
        ];

        return in_array($newStatus, $transitions[$this->status] ?? []);
    }
}
