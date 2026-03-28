<?php

namespace App\Enums;

enum StockStatus: string
{
    case InStock    = 'in_stock';
    case LowStock   = 'low_stock';
    case OutOfStock = 'out_of_stock';

    public function label(): string
    {
        return match($this) {
            self::InStock    => 'In Stock',
            self::LowStock   => 'Low Stock',
            self::OutOfStock => 'Out of Stock',
        };
    }

    public static function fromQuantity(int $qty): self
    {
        return match(true) {
            $qty === 0  => self::OutOfStock,
            $qty <= 5   => self::LowStock,
            default     => self::InStock,
        };
    }
}
