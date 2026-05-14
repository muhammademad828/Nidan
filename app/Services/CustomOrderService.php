<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomOrderService
{
    public function create(array $validated): Order
    {
        return DB::transaction(function () use ($validated): Order {
            $shippingCost = (float) ($validated['shipping_cost'] ?? 0);
            $depositAmount = (float) ($validated['deposit_amount'] ?? 0);
            $sellingPrice = (float) $validated['selling_price'];
            $cost = (float) $validated['cost'];
            $orderNumber = 'CUST-' . strtoupper(Str::random(8));

            $order = Order::create([
                'order_number' => $orderNumber,
                'guest_name' => $validated['guest_name'],
                'phone' => $validated['phone'],
                'city' => $validated['city'],
                'address' => $validated['address'],
                'status' => 'pending_confirmation',
                'subtotal' => $sellingPrice,
                'shipping_cost' => $shippingCost,
                'deposit_amount' => $depositAmount,
                'total_price' => $sellingPrice,
                'net_profit' => $sellingPrice - $cost - $shippingCost,
                'notes' => $validated['notes'] ?? null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => null,
                'product_name_snapshot' => 'Custom Order — ' . $validated['guest_name'],
                'selling_price_snapshot' => $sellingPrice,
                'cost_price_snapshot' => $cost,
                'quantity' => 1,
                'custom_notes' => $validated['notes'] ?? null,
            ]);

            return $order;
        });
    }
}
