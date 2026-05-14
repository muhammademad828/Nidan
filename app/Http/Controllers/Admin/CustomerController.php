<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        // Registered Customers with stats
        $registeredCustomers = User::whereHas('orders')
            ->withCount('orders')
            ->withSum('orders', 'total_price')
            ->get()
            ->map(function ($user) {
                $lastOrder = $user->orders()->latest()->first();
                $favProduct = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.user_id', $user->id)
                    ->select('product_name_snapshot', DB::raw('count(*) as total'))
                    ->groupBy('product_name_snapshot')
                    ->orderByDesc('total')
                    ->first();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'orders_count' => $user->orders_count,
                    'total_spent' => $user->orders_sum_total_price,
                    'last_purchase' => $lastOrder ? $lastOrder->created_at : null,
                    'favorite_product' => $favProduct ? $favProduct->product_name_snapshot : 'N/A',
                    'type' => 'Registered'
                ];
            });

        // Guest Customers (grouped by email/phone)
        $guestCustomers = Order::whereNull('user_id')
            ->select('guest_email', 'guest_phone', 'guest_name', 
                DB::raw('count(*) as orders_count'), 
                DB::raw('sum(total_price) as total_spent'),
                DB::raw('max(created_at) as last_purchase')
            )
            ->groupBy('guest_email', 'guest_phone', 'guest_name')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => null,
                    'name' => $order->guest_name,
                    'email' => $order->guest_email,
                    'phone' => $order->guest_phone,
                    'orders_count' => $order->orders_count,
                    'total_spent' => $order->total_spent,
                    'last_purchase' => $order->last_purchase ? \Carbon\Carbon::parse($order->last_purchase) : null,
                    'favorite_product' => 'Guest Purchase',
                    'type' => 'Guest'
                ];
            });

        $customers = $registeredCustomers->concat($guestCustomers)->sortByDesc('total_spent');

        return view('admin.customers.index', compact('customers'));
    }
}
