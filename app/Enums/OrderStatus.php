<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending   = 'pending';
    case Paid      = 'paid';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending   => 'Pending',
            self::Paid      => 'Paid',
            self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending   => 'warning',
            self::Paid      => 'success',
            self::Delivered => 'info',
            self::Cancelled => 'danger',
        };
    }

    public function allowedTransitions(): array
    {
        return match($this) {
            self::Pending   => [self::Paid, self::Cancelled],
            self::Paid      => [self::Delivered, self::Cancelled],
            self::Delivered => [],
            self::Cancelled => [],
        };
    }
}
