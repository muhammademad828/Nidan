<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            $user = auth()->user();
            $lastCheck = $user ? $user->last_notif_check : null;

            // Counts for the dropdown (All Pending)
            $pendingOrders = Order::where('status', 'pending_confirmation')->count();
            $pendingReviews = Review::where('status', 'pending')->count();
            $newCustomers = User::where('role', 'user')->where('created_at', '>=', now()->subDays(1))->count();

            // Counts for the badge (Unread / New since last check)
            $queryOrders = Order::where('status', 'pending_confirmation');
            $queryReviews = Review::where('status', 'pending');
            $queryCustomers = User::where('role', 'user')->where('created_at', '>=', now()->subDays(1));

            if ($lastCheck) {
                $queryOrders->where('created_at', '>', $lastCheck);
                $queryReviews->where('created_at', '>', $lastCheck);
                $queryCustomers->where('created_at', '>', $lastCheck);
            }

            $unreadTotal = $queryOrders->count() + $queryReviews->count() + $queryCustomers->count();

            $view->with([
                'notif_pending_orders' => $pendingOrders,
                'notif_pending_reviews' => $pendingReviews,
                'notif_new_customers' => $newCustomers,
                'notif_total' => $unreadTotal,
            ]);
        });
    }
}
