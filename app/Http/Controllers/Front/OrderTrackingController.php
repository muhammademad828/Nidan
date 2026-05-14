<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\TrackOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function index(): View
    {
        if (auth()->check()) {
            $orders = auth()->user()->orders()->latest()->get();
            return view('front.track.index', compact('orders'));
        }
        return view('front.track.index');
    }

    public function track(TrackOrderRequest $request): View|RedirectResponse
    {
        $validated = $request->validated();

        $order = Order::where('order_number', $validated['order_number'])->first();

        if (!$order) {
            return back()->with('error', 'Order not found. Please check your order number.');
        }

        $order->load('items.addons');

        return view('front.track.result', compact('order'));
    }
}
