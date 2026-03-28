<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\Category;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = today();
        $month = now();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'today_orders'   => Order::whereDate('created_at', $today)->count(),
                'today_revenue'  => (float) Order::whereDate('created_at', $today)
                    ->whereIn('status', ['paid', 'delivered'])->sum('total'),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'month_revenue'  => (float) Order::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->whereIn('status', ['paid', 'delivered'])->sum('total'),
                'active_products' => Product::where('is_active', true)->count(),
                'total_categories' => Category::count(),
                'subscribers'     => Subscriber::count(),
            ],
            'recent_orders' => Order::with('items')
                ->latest()
                ->take(8)
                ->get()
                ->map(fn ($o) => [
                    'id'           => $o->id,
                    'order_number' => $o->order_number,
                    'customer'     => $o->contact_person,
                    'total'        => (float) $o->total,
                    'status'       => $o->status,
                    'is_read'      => $o->is_read,
                    'items_count'  => $o->items->count(),
                    'created_at'   => $o->created_at->format('Y-m-d H:i'),
                ]),
        ]);
    }
}
