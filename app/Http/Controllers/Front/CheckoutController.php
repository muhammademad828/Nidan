<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreCheckoutRequest;
use App\Models\AddOn;
use App\Services\CartService;
use App\Services\OrderPlacementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderPlacementService $orderPlacementService,
    ) {
    }

    public function index(Request $request): View|RedirectResponse
    {
        $cart = $this->cartService->get();
        $addons = AddOn::active()->orderBy('sort_order')->get();
        $governorates = \App\Models\Governorate::orderBy('name_en')->get();

        if (empty($cart)) {
            return redirect()->route('cart');
        }

        return view('front.checkout.index', compact('cart', 'addons', 'governorates'));
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $cart = $this->cartService->get();

        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $validated = $request->validated();

        $city = \App\Models\City::with('governorate')->findOrFail($validated['city_id']);

        if ($this->cartService->hasFlowerItems($cart) && strtolower($city->governorate->name_en) !== 'menoufia') {
            return back()->with('error', 'Flower delivery is only available in Menoufia.')->withInput();
        }

        $requestedAddonIds = array_values(array_unique(array_map('intval', array_filter($validated['addons'] ?? [], fn ($id) => is_numeric($id)))));

        $selectedAddonIds = AddOn::query()
            ->active()
            ->whereIn('id', $requestedAddonIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        foreach ($cart as $index => $item) {
            $cart[$index]['addons'] = $selectedAddonIds;
        }

        // Robust Idempotency: Expect a Client-Side UUID
        $idempotencyKey = $request->input('idempotency_key');
        
        if (empty($idempotencyKey)) {
            return back()->with('error', 'Security constraint failed: Missing idempotency key. Please refresh the page.')->withInput();
        }

        try {
            $order = $this->orderPlacementService->placeGuestOrder($validated, $cart, auth()->id(), $idempotencyKey);
            
            // Auto-save address to user profile if logged in
            if (auth()->check()) {
                auth()->user()->update([
                    'name' => $validated['guest_name'],
                    'phone' => $validated['phone'],
                    'gender' => $validated['guest_gender'],
                    'address' => $validated['address'],
                    'governorate_id' => $validated['governorate_id'],
                    'city_id' => $validated['city_id'],
                ]);
            }

            // Session & Side-Effect Safety: Clear cart ONLY after DB transaction commits successfully
            $this->cartService->clear();
            
        } catch (\Exception $e) {
            // Cart remains intact in the session if an error occurs (e.g., Deadlock, Insufficient Stock)
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()
            ->route('track')
            ->with('success', "Order [{$order->order_number}] placed successfully!");
    }
}
