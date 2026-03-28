<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('items', 'region')
            ->when($request->input('status'), fn ($q, $s) => $q->where('status', $s))
            ->when($request->input('search'), function ($q, $s) {
                $q->where(fn ($q2) => $q2
                    ->where('order_number', 'like', "%{$s}%")
                    ->orWhere('contact_person', 'like', "%{$s}%")
                    ->orWhere('contact_phone', 'like', "%{$s}%")
                );
            })
            ->latest()
            ->paginate(20)
            ->withQueryString()
            ->through(fn ($o) => [
                'id'           => $o->id,
                'order_number' => $o->order_number,
                'customer'     => $o->contact_person,
                'phone'        => $o->contact_phone,
                'total'        => (float) $o->total,
                'currency'     => $o->currency,
                'status'       => $o->status,
                'items_count'  => $o->items->count(),
                'region'       => $o->region?->name_en,
                'is_gift'      => $o->is_gift,
                'is_read'      => $o->is_read,
                'created_at'   => $o->created_at->format('Y-m-d H:i'),
            ]);

        return Inertia::render('Admin/Orders/Index', [
            'orders'  => $orders,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['items', 'region', 'giftDetail', 'deliveryDetail', 'statusHistory']);

        if (! $order->is_read) {
            $order->update(['is_read' => true]);
        }

        return Inertia::render('Admin/Orders/Show', [
            'order' => [
                'id'             => $order->id,
                'order_number'   => $order->order_number,
                'status'         => $order->status,
                'contact_person' => $order->contact_person,
                'contact_phone'  => $order->contact_phone,
                'company_name'   => $order->company_name,
                'subtotal'       => (float) $order->subtotal,
                'delivery_fee'   => (float) $order->delivery_fee,
                'tax_amount'     => (float) $order->tax_amount,
                'total'          => (float) $order->total,
                'currency'       => $order->currency,
                'is_gift'        => $order->is_gift,
                'is_surprise'    => $order->is_surprise,
                'notes'          => $order->notes,
                'region'         => $order->region?->name_en,
                'items'          => $order->items->map(fn ($i) => [
                    'product_name'  => $i->product_name,
                    'product_sku'   => $i->product_sku,
                    'variation_name' => $i->variation_name,
                    'quantity'      => $i->quantity,
                    'unit_price'    => (float) $i->unit_price,
                    'total_price'   => (float) $i->total_price,
                    'addons'        => $i->addons,
                ]),
                'gift_detail'    => $order->giftDetail ? [
                    'recipient_name'  => $order->giftDetail->recipient_name,
                    'recipient_phone' => $order->giftDetail->recipient_phone,
                    'gift_message'    => $order->giftDetail->gift_message,
                    'is_anonymous'    => $order->giftDetail->is_anonymous,
                ] : null,
                'delivery_detail' => $order->deliveryDetail ? [
                    'preferred_date'       => $order->deliveryDetail->preferred_date,
                    'preferred_time_from'  => $order->deliveryDetail->preferred_time_from,
                    'preferred_time_to'    => $order->deliveryDetail->preferred_time_to,
                    'delivery_area'        => $order->deliveryDetail->delivery_area,
                    'special_instructions' => $order->deliveryDetail->special_instructions,
                ] : null,
                'status_history' => $order->statusHistory->map(fn ($h) => [
                    'from'       => $h->from_status,
                    'to'         => $h->to_status,
                    'notes'      => $h->notes,
                    'changed_by' => $h->changed_by,
                    'date'       => $h->created_at->format('Y-m-d H:i'),
                ]),
                'created_at'     => $order->created_at->format('Y-m-d H:i'),
            ],
        ]);
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => Order::where('is_read', false)->count(),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:pending,paid,delivered,cancelled'],
            'notes'  => ['nullable', 'string', 'max:500'],
        ]);

        $newStatus = $request->input('status');

        if (! $order->canTransitionTo($newStatus)) {
            return back()->with('error', 'لا يمكن تغيير الحالة إلى هذه القيمة');
        }

        $oldStatus = $order->status;
        $order->update(['status' => $newStatus]);

        OrderStatusHistory::create([
            'order_id'    => $order->id,
            'from_status' => $oldStatus,
            'to_status'   => $newStatus,
            'notes'       => $request->input('notes'),
            'changed_by'  => 'admin:' . Auth::user()->name,
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}
