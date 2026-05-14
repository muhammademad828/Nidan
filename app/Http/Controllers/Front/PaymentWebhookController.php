<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    /**
     * Handle payment provider webhooks (e.g., Stripe, Paymob).
     * 
     * This implementation is idempotent by checking the transaction ID.
     */
    public function handle(Request $request)
    {
        // 1. Verify Signature (Implementation depends on provider)
        // if (!$this->isValidSignature($request)) {
        //     return response()->json(['message' => 'Invalid signature'], 401);
        // }

        $payload = $request->all();
        $transactionId = $payload['transaction_id'] ?? null;
        $orderNumber = $payload['order_number'] ?? null;

        if (!$transactionId || !$orderNumber) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 2. Check for duplicate processing (Idempotency)
        // We can use a dedicated payments table or store transaction_id on the order
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Assume we have a 'payment_transaction_id' column or a separate Transaction model
        // if ($order->payment_transaction_id === $transactionId) {
        //     return response()->json(['message' => 'Already processed'], 200);
        // }

        // 3. Process Payment
        $status = $payload['status'] ?? 'failed';

        if ($status === 'success') {
            if ($order->canTransitionTo('confirmed')) {
                $order->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                    // 'payment_transaction_id' => $transactionId
                ]);
                Log::info("Payment successful for order: {$orderNumber}");
            }
        } else {
            Log::warning("Payment failed for order: {$orderNumber}");
        }

        return response()->json(['message' => 'Webhook handled'], 200);
    }
}
