<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function success(): Response|RedirectResponse
    {
        $orderNumber = session('last_order_number');
        $orderId     = session('last_order_id');

        if (! $orderNumber) {
            return redirect()->route('home');
        }

        return Inertia::render('Checkout/Success', [
            'orderNumber' => $orderNumber,
            'orderId'     => $orderId,
        ]);
    }
}
