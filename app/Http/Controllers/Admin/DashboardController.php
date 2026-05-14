<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        // --- Monthly Net Profit ---
        $monthlyProfit = DB::table('orders')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->selectRaw('SUM(net_profit) as total_profit, COUNT(*) as total_orders')
            ->first();

        // --- Best Selling Products (top 5) ---
        $bestSellers = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereYear('orders.created_at', $year)
            ->whereMonth('orders.created_at', $month)
            ->whereNotIn('orders.status', ['cancelled', 'refunded'])
            ->select('products.name_en', 'products.name_ar', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name_en', 'products.name_ar')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // --- Cities Ranked by Order Count ---
        $cityStats = DB::table('orders')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->select('city', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total_price) as total_revenue'))
            ->groupBy('city')
            ->orderByDesc('order_count')
            ->limit(10)
            ->get();

        // --- Cancellation Rate ---
        $totalOrders = DB::table('orders')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $cancelledOrders = DB::table('orders')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereIn('status', ['cancelled', 'refunded'])
            ->count();

        $cancellationRate = $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100, 1) : 0;

        // --- Order Status Breakdown ---
        $statusBreakdown = DB::table('orders')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // --- Recent Orders ---
        $recentOrders = Order::with('items')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // --- Sales Chart Data (Last 30 Days) ---
        $salesData = DB::table('orders')
            ->where('created_at', '>=', now()->subDays(30))
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // --- Year/Month for navigation ---
        $availableYears = range(now()->year - 2, now()->year);

        return view('admin.dashboard', compact(
            'monthlyProfit',
            'bestSellers',
            'cityStats',
            'cancellationRate',
            'totalOrders',
            'cancelledOrders',
            'statusBreakdown',
            'recentOrders',
            'year',
            'month',
            'availableYears',
            'salesData'
        ));
    }
    public function markNotifsRead(): \Illuminate\Http\JsonResponse
    {
        auth()->user()->update(['last_notif_check' => now()]);
        return response()->json(['success' => true]);
    }
}
