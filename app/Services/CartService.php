<?php

namespace App\Services;

use App\Models\Bundle;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getOrCreateCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['expires_at' => now()->addDays(30)]
            );
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['expires_at' => now()->addDays(7)]
        );
    }

    public function getCart(): Cart
    {
        return $this->getOrCreateCart()->load(['items.product.images', 'items.variation', 'items.bundle']);
    }

    public function add(int $productId, ?int $variationId = null, int $qty = 1, array $addons = []): CartItem
    {
        $cart    = $this->getOrCreateCart();
        $product = Product::findOrFail($productId);

        $unitPrice = (float) $product->base_price;
        if ($variationId) {
            $variation  = ProductVariation::findOrFail($variationId);
            $unitPrice += (float) $variation->price_modifier;
        }

        $existing = $cart->items()
            ->where('product_id', $productId)
            ->where('variation_id', $variationId)
            ->whereNull('bundle_id')
            ->first();

        if ($existing) {
            $existing->increment('quantity', $qty);
            return $existing;
        }

        return $cart->items()->create([
            'product_id'   => $productId,
            'variation_id' => $variationId,
            'quantity'     => $qty,
            'unit_price'   => $unitPrice,
            'addons'       => $addons ?: null,
        ]);
    }

    public function addBundle(int $bundleId): void
    {
        $cart   = $this->getOrCreateCart();
        $bundle = Bundle::with('items.product')->findOrFail($bundleId);

        foreach ($bundle->items as $item) {
            $cart->items()->create([
                'product_id' => $item->product_id,
                'bundle_id'  => $bundleId,
                'quantity'   => $item->quantity,
                'unit_price' => (float) $item->product->base_price,
            ]);
        }
    }

    public function update(int $cartItemId, int $qty): CartItem
    {
        $item = CartItem::findOrFail($cartItemId);
        if ($qty <= 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $qty]);
        }
        return $item;
    }

    public function remove(int $cartItemId): void
    {
        CartItem::where('id', $cartItemId)->delete();
    }

    public function clear(): void
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();
    }

    public function getCartData(): array
    {
        $cart = $this->getCart();

        return [
            'items'      => $cart->items->map(fn ($item) => [
                'id'           => $item->id,
                'product_id'   => $item->product_id,
                'product_slug' => $item->product?->slug,
                'product_name' => $item->product?->name,
                'product_sku'  => $item->product?->sku,
                'product_image' => $item->product?->primary_image,
                'requires_delivery_slot' => (bool) $item->product?->requires_delivery_slot,
                'has_delivery_time' => (bool) $item->product?->has_delivery_time,
                'variation_id' => $item->variation_id,
                'variation_name' => $item->variation?->name,
                'bundle_id'    => $item->bundle_id,
                'quantity'     => $item->quantity,
                'unit_price'   => (float) $item->unit_price,
                'total_price'  => (float) $item->unit_price * $item->quantity,
                'addons'       => $item->addons,
            ])->toArray(),
            'subtotal'   => $cart->subtotal,
            'item_count' => $cart->item_count,
            'requires_delivery_slot' => $cart->items->contains(fn ($item) => $item->product?->requires_delivery_slot),
            'requires_delivery_time' => $cart->items->contains(fn ($item) => $item->product?->has_delivery_time),
        ];
    }

    public function mergeGuestCart(int $userId): void
    {
        $sessionId   = Session::getId();
        $guestCart   = Cart::where('session_id', $sessionId)->first();
        $userCart    = Cart::firstOrCreate(['user_id' => $userId], ['expires_at' => now()->addDays(30)]);

        if (! $guestCart) return;

        foreach ($guestCart->items as $item) {
            $existing = $userCart->items()
                ->where('product_id', $item->product_id)
                ->where('variation_id', $item->variation_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $item->quantity);
            } else {
                $item->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }
}
