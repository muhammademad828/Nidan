@extends('layouts.app')

@section('content')

<section class="py-16 px-6 md:px-12 relative z-10">
    <div class="max-w-2xl mx-auto">

        @php
        $statusSteps = [
            'pending_confirmation' => 0,
            'confirmed' => 1,
            'in_preparation' => 2,
            'ready_for_shipping' => 3,
            'out_for_delivery' => 4,
            'delivered' => 5,
        ];
        $currentStep = $statusSteps[$order->status] ?? 0;
        $isCancelled = in_array($order->status, ['cancelled', 'refunded']);
        @endphp

        <div class="text-center mb-10">
            <h1 class="text-3xl font-serif text-nidan-text mb-2">Order {{ $order->order_number }}</h1>
            <p class="text-gray-500">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>

        <!-- Status Tracker -->
        @if(!$isCancelled)
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 mb-8">
            <h2 class="font-serif text-xl text-nidan-text mb-6 text-center">Order Status</h2>
            <div class="flex items-center justify-between relative">
                <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-100"></div>
                <div class="absolute top-4 left-0 h-0.5 bg-nidan-gold transition-all" style="width: {{ $currentStep * 20 }}%"></div>
                @foreach(['Pending', 'Confirmed', 'Preparing', 'Ready', 'On Way', 'Delivered'] as $i => $label)
                <div class="flex flex-col items-center z-10">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-colors {{ $i <= $currentStep ? 'bg-nidan-gold text-white' : 'bg-gray-100 text-gray-400' }}">
                        @if($i < $currentStep) ✓ @else {{ $i + 1 }} @endif
                    </div>
                    <span class="text-[10px] mt-2 uppercase tracking-wider {{ $i <= $currentStep ? 'text-nidan-gold font-medium' : 'text-gray-300' }}">{{ $label }}</span>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-6">
                <span class="px-4 py-2 rounded-full text-sm font-medium
                    @if($order->status === 'delivered') bg-green-100 text-green-700
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                    @else bg-yellow-100 text-yellow-700 @endif">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>
        @else
        <div class="bg-red-50 rounded-2xl p-8 text-center mb-8 border border-red-100">
            <i class="fas fa-times-circle text-4xl text-red-400 mb-3"></i>
            <h2 class="font-serif text-xl text-red-700">Order {{ $order->status_label }}</h2>
            <p class="text-sm text-red-500 mt-2">This order has been {{ $order->status === 'cancelled' ? 'cancelled' : 'refunded' }}.</p>
        </div>
        @endif

        <!-- Order Details -->
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 mb-6">
            <h3 class="font-serif text-lg text-nidan-text mb-4">Order Details</h3>
            <div class="space-y-3 text-sm">
                @foreach($order->items as $item)
                <div class="flex justify-between py-2 border-b border-gray-50">
                    <div>
                        <p class="font-medium">{{ $item->product_name_snapshot }}</p>
                        <p class="text-gray-400 text-xs">× {{ $item->quantity }}</p>
                    </div>
                    <span class="font-medium">EGP {{ number_format($item->line_total, 2) }}</span>
                </div>
                @endforeach
                <div class="flex justify-between pt-3">
                    <span class="text-gray-500">Total</span>
                    <span class="font-serif text-lg">EGP {{ number_format($order->total_price, 2) }}</span>
                </div>
                @if($order->deposit_amount > 0)
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Deposit Paid</span>
                    <span>EGP {{ number_format($order->deposit_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm text-nidan-gold font-medium">
                    <span>Remaining</span>
                    <span>EGP {{ number_format($order->remaining_amount, 2) }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Delivery Address -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-serif text-lg text-nidan-text mb-3">Delivery Address</h3>
            <p class="text-sm text-gray-600">{{ $order->address }}</p>
            <p class="text-sm text-gray-500 mt-1"><i class="fas fa-map-marker-alt text-nidan-gold mr-1"></i> {{ $order->city }}</p>
            <p class="text-sm text-gray-500 mt-1"><i class="fas fa-phone text-nidan-gold mr-1"></i> {{ $order->phone }}</p>
        </div>
    </div>
</section>

@endsection
