<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\City;
use App\Models\DeliverySlot;
use App\Models\GiftDetail;
use App\Models\DeliveryDetail;
use App\Models\Governorate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Region;
use App\Services\CartService;
use App\Services\LocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private LocationService $locationService,
    ) {}

    public function index(): Response|RedirectResponse
    {
        $cartData = $this->cartService->getCartData();

        if (empty($cartData['items'])) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        $regions       = $this->locationService->getRegions();
        $currentRegion = $this->locationService->getCurrentRegion();

        $slots = DeliverySlot::with('region')
            ->where('is_active', true)
            ->where('date', '>=', today())
            ->where('date', '<=', today()->addDays(14))
            ->whereColumn('booked_count', '<', 'capacity')
            ->when($currentRegion, fn ($q) => $q->where('region_id', $currentRegion->id))
            ->orderBy('date')
            ->orderBy('time_from')
            ->get()
            ->map(fn ($s) => [
                'id'         => $s->id,
                'region_id'  => $s->region_id,
                'date'       => $s->date->format('Y-m-d'),
                'date_label' => $s->date->format('D, d M'),
                'time'       => $s->time_from . ' – ' . $s->time_to,
                'time_from'  => $s->time_from,
                'time_to'    => $s->time_to,
                'available'  => $s->remaining_capacity,
            ]);

        $governorates = Governorate::active()
            ->with(['activeCities' => fn ($q) => $q->orderBy('display_order')->orderBy('name_en')])
            ->orderBy('display_order')
            ->get()
            ->map(fn ($g) => [
                'id'      => $g->id,
                'name_ar' => $g->name_ar,
                'name_en' => $g->name_en,
                'cities'  => $g->activeCities->map(fn ($c) => [
                    'id'           => $c->id,
                    'name_ar'      => $c->name_ar,
                    'name_en'      => $c->name_en,
                    'delivery_fee' => (float) $c->delivery_fee,
                ]),
            ]);

        return Inertia::render('Checkout/Index', [
            'cart'          => $cartData,
            'regions'       => $regions,
            'currentRegion' => $currentRegion?->only(['id', 'name', 'slug', 'delivery_fee']),
            'deliverySlots' => $slots,
            'governorates'  => $governorates,
        ]);
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $cartData = $this->cartService->getCartData();

        if (empty($cartData['items'])) {
            return back()->with('error', 'Your cart is empty.');
        }

        $region = Region::findOrFail($request->integer('region_id'));
        $city = $request->integer('city_id') ? City::find($request->integer('city_id')) : null;

        // Resolve time slot times
        $slot      = null;
        $timeFrom  = '09:00';
        $timeTo    = '21:00';

        if ($request->integer('delivery_slot_id')) {
            $slot     = DeliverySlot::find($request->integer('delivery_slot_id'));
            $timeFrom = $slot?->time_from ?? '09:00';
            $timeTo   = $slot?->time_to   ?? '21:00';
        }

        if ($request->filled('preferred_delivery_time')) {
            $timeFrom = $request->input('preferred_delivery_time');
            $timeTo   = null;
        }

        $orderId = null;

        DB::transaction(function () use ($request, $cartData, $region, $city, $slot, $timeFrom, $timeTo, &$orderId) {
            $subtotal    = $cartData['subtotal'];
            $deliveryFee = $city ? (float) $city->delivery_fee : (float) $region->delivery_fee;
            $taxAmount   = round($subtotal * 0.15, 2);
            $total       = $subtotal + $deliveryFee + $taxAmount;

            $order = Order::create([
                'order_number'    => 'ND-' . strtoupper(substr(uniqid(), -6)),
                'user_id'         => Auth::id(),
                'region_id'       => $region->id,
                'city_id'         => $city?->id,
                'status'          => 'pending',
                'subtotal'        => $subtotal,
                'delivery_fee'    => $deliveryFee,
                'tax_amount'      => $taxAmount,
                'discount_amount' => 0,
                'total'           => $total,
                'currency'        => config('app.currency', 'EGP'),
                'company_name'    => $request->input('company_name'),
                'contact_person'  => $request->input('contact_person'),
                'contact_phone'   => $request->input('contact_phone'),
                'is_gift'         => $request->boolean('is_gift'),
                'is_surprise'     => $request->boolean('is_surprise'),
                'notes'           => $request->input('notes'),
            ]);

            // Order items (snapshot)
            foreach ($cartData['items'] as $item) {
                OrderItem::create([
                    'order_id'       => $order->id,
                    'product_id'     => $item['product_id'],
                    'variation_id'   => $item['variation_id'],
                    'product_name'   => $item['product_name'],
                    'product_sku'    => $item['product_sku'],
                    'variation_name' => $item['variation_name'] ?? null,
                    'quantity'       => $item['quantity'],
                    'unit_price'     => $item['unit_price'],
                    'total_price'    => $item['total_price'],
                    'addons'         => $item['addons'] ?: null,
                ]);
            }

            // Gift detail
            if ($request->boolean('is_gift')) {
                GiftDetail::create([
                    'order_id'          => $order->id,
                    'sender_name'       => $request->input('contact_person'),
                    'sender_phone'      => $request->input('contact_phone'),
                    'recipient_name'    => $request->input('recipient_name'),
                    'recipient_phone'   => $request->input('recipient_phone'),
                    'recipient_address' => $request->input('recipient_address'),
                    'recipient_city'    => $region->name,
                    'gift_message'      => $request->input('gift_message'),
                    'is_anonymous'      => $request->boolean('is_surprise'),
                ]);
            }

            // Delivery detail
            DeliveryDetail::create([
                'order_id'             => $order->id,
                'delivery_slot_id'     => $slot?->id,
                'preferred_date'       => $request->input('delivery_date'),
                'preferred_time_from'  => $timeFrom,
                'preferred_time_to'    => $timeTo,
                'special_instructions' => $request->input('notes'),
                'delivery_area'        => $request->input('address'),
            ]);

            // Increment slot booking
            $slot?->increment('booked_count');

            // Initial status history
            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'from_status' => null,
                'to_status'   => 'pending',
                'notes'       => 'Order placed by customer.',
                'changed_by'  => 'customer',
            ]);

            $this->cartService->clear();

            session([
                'last_order_number' => $order->order_number,
                'last_order_id'     => $order->id,
            ]);

            $orderId = $order->id;
        });

        return redirect()->route('checkout.success');
    }
}
