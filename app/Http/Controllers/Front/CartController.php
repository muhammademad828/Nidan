<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\AddToCartRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(Request $request): View
    {
        $cart = $this->cartService->get();
        return view('front.cart.index', compact('cart'));
    }

    public function add(AddToCartRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $product = Product::active()->findOrFail($validated['product_id']);
        $quantity = (int) ($validated['quantity'] ?? 1);

        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'ar' ? 'عفواً، هذا المنتج غير متوفر حالياً.' : 'Sorry, this product is out of stock.'
            ], 422);
        }

        $cart = $this->cartService->addProduct($product, $quantity);
        $html = view('front.cart.mini', ['cart' => $cart])->render();

        return response()->json([
            'success' => true,
            'count' => count($cart),
            'html' => $html
        ]);
    }

    public function update(string $locale, int $index, int $delta): RedirectResponse
    {
        if (!$this->cartService->updateQuantityByDelta($index, $delta)) {
            return back()->with('error', 'Cart item not found.');
        }

        return back();
    }

    public function remove(string $locale, int $index): RedirectResponse
    {
        $this->cartService->remove($index);

        return back()->with('success', 'Item removed from cart.');
    }
}
