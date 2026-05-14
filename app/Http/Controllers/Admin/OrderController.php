<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('order_number', 'like', "%$search%")
                ->orWhere('guest_name', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%"));
        }
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $orders = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $statuses = Order::STATUSES;
        $cities = Order::distinct()->pluck('city')->sort()->filter();

        return view('admin.orders.index', compact('orders', 'statuses', 'cities'));
    }

    public function show(Order $order): View
    {
        $order->load('items.addons');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        $oldStatus = $order->status;
        $newStatus = $request->validated('status');

        if ($oldStatus !== $newStatus && !$order->canTransitionTo($newStatus)) {
            return back()->with('error', "Cannot transition order from [{$oldStatus}] to [{$newStatus}]. Invalid workflow step.");
        }

        if ($newStatus === 'confirmed' && !$order->confirmed_at) {
            $order->confirmed_at = now();
        }
        if ($newStatus === 'delivered' && !$order->delivered_at) {
            $order->delivered_at = now();
        }

        $order->status = $newStatus;
        $order->save();

        return back()->with('success', "Order status updated from [{$oldStatus}] to [{$newStatus}].");
    }
}
