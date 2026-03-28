<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $isAr = app()->getLocale() === 'ar';
        $today         = today();
        $todayOrders   = Order::whereDate('created_at', $today)->count();
        $todayRevenue  = Order::whereDate('created_at', $today)->whereIn('status', ['paid', 'delivered'])->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalProducts = Product::where('is_active', true)->count();
        $monthRevenue  = Order::whereMonth('created_at', now()->month)
            ->whereIn('status', ['paid', 'delivered'])
            ->sum('total');

        return [
            Stat::make(
                $isAr ? 'طلبات اليوم' : 'Today\'s Orders',
                $todayOrders,
            )
                ->description($isAr ? 'طلبات جديدة اليوم' : 'New orders placed today')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),

            Stat::make(
                $isAr ? 'إيرادات اليوم' : 'Today\'s Revenue',
                number_format($todayRevenue, 2) . ' ' . ($isAr ? 'ج.م' : 'EGP'),
            )
                ->description($isAr ? 'الطلبات المدفوعة اليوم' : 'Paid orders today')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make(
                $isAr ? 'طلبات معلقة' : 'Pending Orders',
                $pendingOrders,
            )
                ->description($isAr ? 'بانتظار التأكيد' : 'Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 10 ? 'danger' : 'warning'),

            Stat::make(
                $isAr ? 'إيرادات الشهر' : 'Month Revenue',
                number_format($monthRevenue, 2) . ' ' . ($isAr ? 'ج.م' : 'EGP'),
            )
                ->description(($isAr ? 'هذا الشهر ' : 'This month ') . now()->format('M Y'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),

            Stat::make(
                $isAr ? 'منتجات نشطة' : 'Active Products',
                $totalProducts,
            )
                ->description($isAr ? 'في المتجر' : 'Live in catalog')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('gray'),
        ];
    }
}
