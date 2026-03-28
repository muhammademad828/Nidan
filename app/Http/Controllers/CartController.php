<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    /* ── All cart mutations return JSON so Vue can update reactively ── */

    public function add(AddToCartRequest $request): JsonResponse
    {
        $this->cartService->add(
            productId:   $request->integer('product_id'),
            variationId: $request->integer('variation_id') ?: null,
            qty:         $request->integer('quantity', 1),
            addons:      $request->input('addons', []),
        );

        return response()->json($this->cartService->getCartData());
    }

    public function update(Request $request, int $cartItemId): JsonResponse
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:99']);
        $this->cartService->update($cartItemId, $request->integer('quantity'));
        return response()->json($this->cartService->getCartData());
    }

    public function remove(int $cartItemId): JsonResponse
    {
        $this->cartService->remove($cartItemId);
        return response()->json($this->cartService->getCartData());
    }

    public function addBundle(Request $request): JsonResponse
    {
        $request->validate(['bundle_id' => 'required|exists:bundles,id']);
        $this->cartService->addBundle($request->integer('bundle_id'));
        return response()->json($this->cartService->getCartData());
    }

    public function get(): JsonResponse
    {
        return response()->json($this->cartService->getCartData());
    }
}
