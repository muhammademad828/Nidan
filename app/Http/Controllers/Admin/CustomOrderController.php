<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomOrderRequest;
use App\Services\CustomOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomOrderController extends Controller
{
    public function __construct(private readonly CustomOrderService $customOrderService)
    {
    }

    public function create(): View
    {
        return view('admin.custom-orders.create');
    }

    public function store(StoreCustomOrderRequest $request): RedirectResponse
    {
        $order = $this->customOrderService->create($request->validated());

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', "Custom order [{$order->order_number}] created. Remaining: EGP " . number_format($order->remaining_amount, 2));
    }
}
