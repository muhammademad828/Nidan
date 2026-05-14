<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    public function get(): array
    {
        return session()->get('cart', []);
    }

    public function addProduct(Product $product, int $quantity = 1): array
    {
        $quantity = max(1, min(99, $quantity));
        $cart = $this->get();

        foreach ($cart as $index => $item) {
            if (($item['product_id'] ?? null) === $product->id) {
                $cart[$index]['quantity'] = min(99, ((int) $cart[$index]['quantity']) + $quantity);
                $this->put($cart);

                return $cart;
            }
        }

        $cart[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => $product->image,
            'selling_price' => (float) $product->selling_price,
            'cost_price' => (float) $product->cost_price,
            'quantity' => $quantity,
            'is_flower' => (bool) $product->is_flower,
            'addons' => [],
        ];

        $this->put($cart);

        return $cart;
    }

    public function updateQuantityByDelta(int $index, int $delta): bool
    {
        $cart = $this->get();

        if (!isset($cart[$index])) {
            return false;
        }

        $cart[$index]['quantity'] = max(1, min(99, ((int) $cart[$index]['quantity']) + $delta));
        $this->put($cart);

        return true;
    }

    public function remove(int $index): bool
    {
        $cart = $this->get();

        if (!isset($cart[$index])) {
            return false;
        }

        unset($cart[$index]);
        $this->put(array_values($cart));

        return true;
    }

    public function clear(): void
    {
        session()->forget('cart');
    }

    public function hasFlowerItems(?array $cart = null): bool
    {
        $cart ??= $this->get();

        return collect($cart)->contains(fn ($item) => (bool) ($item['is_flower'] ?? false));
    }

    private function put(array $cart): void
    {
        session()->put('cart', $cart);
    }
}
