<?php

namespace App\Services;

use App\Models\AddOn;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemAddOn;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderPlacementService
{
    /**
     * @throws \Exception
     */
    public function placeGuestOrder(array $validated, array $cart, ?int $userId = null, ?string $idempotencyKey = null): Order
    {
        return DB::transaction(function () use ($validated, $cart, $userId, $idempotencyKey): Order {
            // 1. Idempotency Check (Client-Side UUID)
            if ($idempotencyKey) {
                $existingOrder = Order::where('idempotency_key', $idempotencyKey)->first();
                if ($existingOrder) {
                    return $existingOrder; // Safe return if already processed
                }
            }

            $orderNumber = 'NID-' . strtoupper(Str::random(8));
            
            // Extract unique add-ons for the whole order
            $allAddonIds = [];
            foreach ($cart as $item) {
                $itemAddons = array_values(array_unique(array_map('intval', array_filter($item['addons'] ?? [], fn ($id) => is_numeric($id)))));
                $allAddonIds = array_merge($allAddonIds, $itemAddons);
            }
            $uniqueAddonIds = array_values(array_unique($allAddonIds));

            $addonsById = \App\Models\AddOn::query()
                ->active()
                ->whereIn('id', $uniqueAddonIds)
                ->get()
                ->keyBy('id');

            // 2. Deadlock Prevention: Deterministic Locking
            $productIds = [];
            foreach ($cart as $item) {
                if (isset($item['product_id'])) {
                    $productIds[] = $item['product_id'];
                }
            }
            $productIds = array_unique($productIds);
            sort($productIds);

            $lockedProducts = [];
            foreach ($productIds as $pid) {
                $product = Product::where('id', $pid)->lockForUpdate()->first();
                if (!$product) {
                    throw new \Exception("Product ID {$pid} not found.");
                }
                $lockedProducts[$pid] = $product;
            }

            $itemsSubtotal = '0.0000';
            $itemsTotalCost = '0.0000';

            foreach ($cart as $index => $item) {
                $quantity = (int) $item['quantity'];
                
                if (isset($item['product_id'])) {
                    $product = $lockedProducts[$item['product_id']];
                    if ($product->stock < $quantity) {
                        throw new \Exception("Insufficient stock for product: " . ($product->name ?? 'Unknown'));
                    }
                    $product->decrement('stock', $quantity);
                    $product->stock -= $quantity; 
                }

                $baseUnitPrice = number_format($item['selling_price'], 4, '.', '');
                $costUnitPrice = number_format($item['cost_price'], 4, '.', '');
                
                $itemTotalLinePrice = bcmul($baseUnitPrice, (string) $quantity, 4);
                $itemTotalLineCost = bcmul($costUnitPrice, (string) $quantity, 4);

                $itemsSubtotal = bcadd($itemsSubtotal, $itemTotalLinePrice, 4);
                $itemsTotalCost = bcadd($itemsTotalCost, $itemTotalLineCost, 4);
            }

            // Calculate Add-ons once per order
            $addonsTotalPrice = '0.0000';
            $addonsTotalCost = '0.0000';
            foreach ($addonsById as $addon) {
                $addonsTotalPrice = bcadd($addonsTotalPrice, number_format($addon->price, 4, '.', ''), 4);
                $addonsTotalCost = bcadd($addonsTotalCost, number_format($addon->cost_price, 4, '.', ''), 4);
            }

            $city = \App\Models\City::findOrFail($validated['city_id']);
            $shippingCost = number_format($city->shipping_price, 4, '.', '');
            
            // Total = Items Subtotal + Addons + Shipping
            $orderSubtotal = bcadd($itemsSubtotal, $addonsTotalPrice, 4);
            $totalPrice = bcadd($orderSubtotal, $shippingCost, 4);
            
            $totalOrderCost = bcadd($itemsTotalCost, $addonsTotalCost, 4);
            $netProfit = bcsub($orderSubtotal, $totalOrderCost, 4);

            $finalSubtotal = number_format(round((float) $orderSubtotal, 2, PHP_ROUND_HALF_UP), 2, '.', '');
            $finalShipping = number_format(round((float) $shippingCost, 2, PHP_ROUND_HALF_UP), 2, '.', '');
            $finalTotal = number_format(round((float) $totalPrice, 2, PHP_ROUND_HALF_UP), 2, '.', '');
            $finalProfit = number_format(round((float) $netProfit, 2, PHP_ROUND_HALF_UP), 2, '.', '');

            $order = Order::create([
                'order_number' => $orderNumber,
                'idempotency_key' => $idempotencyKey,
                'user_id' => $userId,
                'guest_name' => $validated['guest_name'],
                'phone' => $validated['phone'],
                'guest_email' => $validated['guest_email'] ?? null,
                'guest_gender' => $validated['guest_gender'] ?? null,
                'city' => $city->name_en,
                'address' => $validated['address'],
                'status' => 'pending_confirmation',
                'subtotal' => $finalSubtotal,
                'shipping_cost' => $finalShipping,
                'total_price' => $finalTotal,
                'net_profit' => $finalProfit,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Save Add-ons to the new table
            $addonMessages = $validated['addon_messages'] ?? [];
            foreach ($addonsById as $addon) {
                $snapAddonPrice = number_format(round((float) $addon->price, 2, PHP_ROUND_HALF_UP), 2, '.', '');
                $snapAddonCost = number_format(round((float) $addon->cost_price, 2, PHP_ROUND_HALF_UP), 2, '.', '');

                \App\Models\OrderAddOn::create([
                    'order_id' => $order->id,
                    'add_on_id' => $addon->id,
                    'add_on_name_snapshot' => $addon->name,
                    'price_snapshot' => $snapAddonPrice,
                    'cost_price_snapshot' => $snapAddonCost,
                    'customer_message' => $addonMessages[$addon->id] ?? null,
                ]);
            }

            foreach ($cart as $index => $item) {
                $snapSelling = number_format(round((float) $item['selling_price'], 2, PHP_ROUND_HALF_UP), 2, '.', '');
                $snapCost = number_format(round((float) $item['cost_price'], 2, PHP_ROUND_HALF_UP), 2, '.', '');

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'product_name_snapshot' => $item['name'],
                    'selling_price_snapshot' => $snapSelling,
                    'cost_price_snapshot' => $snapCost,
                    'quantity' => $item['quantity'],
                ]);
            }

            // Note: Cart clearing has been moved to the controller for side-effect safety.
            return $order;
        });
    }
}
