@extends('layouts.admin')
@section('title', 'Order Details | ' . $order->order_number)
@section('page-title', 'Order Management')
@section('breadcrumb', 'Home / Orders / ' . $order->order_number)

@section('page-content')

<div class="flex flex-col gap-8">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <h2 class="text-2xl font-serif font-bold text-nidan-dark tracking-tight">{{ $order->order_number }}</h2>
            <span class="px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider
                @if($order->status === 'delivered') bg-green-100 text-green-700
                @elseif($order->status === 'cancelled' || $order->status === 'refunded') bg-red-100 text-red-700
                @else bg-amber-100 text-amber-700 @endif">
                {{ $order->status_label }}
            </span>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition-all flex items-center gap-2">
                <i class="fas fa-print"></i> Print Invoice
            </button>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-nidan-gold text-white rounded-lg text-sm font-medium hover:shadow-lg hover:shadow-nidan-gold/30 transition-all flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Order Items -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="font-bold text-nidan-dark flex items-center gap-2">
                        <i class="fas fa-box-open text-nidan-gold"></i> Order Items
                    </h3>
                    <span class="text-xs text-gray-400">{{ count($order->items) }} items total</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-[10px] text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                                <th class="p-6">Product</th>
                                <th class="p-6 text-center">Price</th>
                                <th class="p-6 text-center">Qty</th>
                                <th class="p-6 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($order->items as $item)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}" class="w-16 h-16 rounded-xl object-cover border border-gray-100 shadow-sm group-hover:border-nidan-gold/30 transition-all" alt="">
                                        @else
                                            <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300 border border-gray-100">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                        <div class="flex flex-col">
                                            <div class="flex flex-col mb-1">
                                                <span class="font-bold text-nidan-dark group-hover:text-nidan-gold transition-colors">{{ $item->product_name_snapshot }}</span>
                                                @if($item->product && $item->product->sku)
                                                    <span class="text-[10px] font-mono text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded w-fit mt-0.5">SKU: {{ $item->product->sku }}</span>
                                                @endif
                                            </div>
                                            @if($item->addons->count() > 0)
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($item->addons as $addon)
                                                <span class="px-2 py-0.5 bg-nidan-bg border border-nidan-gold/20 rounded text-[10px] text-nidan-gold font-medium">
                                                    + {{ $addon->add_on_name_snapshot }}
                                                </span>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6 text-center">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold">EGP {{ number_format($item->selling_price_snapshot, 2) }}</span>
                                        <span class="text-[10px] text-gray-400">Cost: {{ number_format($item->cost_price_snapshot, 2) }}</span>
                                    </div>
                                </td>
                                <td class="p-6 text-center">
                                    <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-bold text-gray-600">× {{ $item->quantity }}</span>
                                </td>
                                <td class="p-6 text-right">
                                    <span class="font-bold text-nidan-dark">EGP {{ number_format($item->line_total, 2) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($order->notes)
                <div class="p-6 bg-amber-50/50 border-t border-amber-100">
                    <h4 class="text-[10px] font-bold text-amber-800 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                        <i class="fas fa-sticky-note"></i> Customer Notes
                    </h4>
                    <p class="text-sm text-amber-900 leading-relaxed">{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Detailed Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Customer Profile -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
                    <h3 class="font-bold text-nidan-dark mb-6 flex items-center gap-2">
                        <i class="fas fa-user-circle text-nidan-gold"></i> Customer Profile
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest">Full Name</p>
                                <p class="text-sm font-bold text-nidan-dark">{{ $order->customer_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest">Phone Number</p>
                                <p class="text-sm font-bold text-nidan-dark">{{ $order->phone }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest">Email Address</p>
                                <p class="text-sm font-bold text-nidan-dark">{{ $order->guest_email ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
                    <h3 class="font-bold text-nidan-dark mb-6 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-nidan-gold"></i> Delivery Location
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-city"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest">City / Region</p>
                                <p class="text-sm font-bold text-nidan-dark">{{ $order->city }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-home"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest">Detailed Address</p>
                                <p class="text-sm font-bold text-nidan-dark leading-relaxed">{{ $order->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-8">
            <!-- Order Status Update Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-nidan-gold/10 overflow-hidden">
                <div class="p-6 bg-nidan-dark text-white">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="fas fa-tasks text-nidan-gold"></i> Management
                    </h3>
                    <p class="text-[10px] text-gray-400 mt-1">Control order lifecycle and workflow</p>
                </div>
                <div class="p-6">
                    <form id="statusForm" method="POST" action="{{ route('admin.orders.status', $order) }}">
                        @csrf @method('PATCH')
                        <div class="space-y-3">
                            @foreach(\App\Models\Order::STATUSES as $status)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-50 hover:bg-nidan-bg hover:border-nidan-gold/30 cursor-pointer transition-all group">
                                <div class="relative flex items-center justify-center">
                                    <input type="radio" name="status" value="{{ $status }}" {{ $order->status === $status ? 'checked' : '' }}
                                        class="w-5 h-5 text-nidan-gold border-gray-200 focus:ring-nidan-gold/50 cursor-pointer">
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold group-hover:text-nidan-dark transition-colors
                                        {{ $order->status === $status ? 'text-nidan-gold' : 'text-gray-500' }}">
                                        {{ \App\Models\Order::STATUS_LABELS_EN[$status] }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">{{ \App\Models\Order::STATUS_LABELS_AR[$status] }}</span>
                                </div>
                                @if($order->status === $status)
                                <i class="fas fa-check-circle text-nidan-gold ml-auto"></i>
                                @endif
                            </label>
                            @endforeach
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-gray-50">
                            <button type="submit" id="submitBtn"
                                class="w-full py-4 bg-nidan-gold text-white font-bold rounded-xl shadow-xl shadow-nidan-gold/20 hover:shadow-nidan-gold/40 active:scale-95 transition-all flex items-center justify-center gap-3">
                                <i class="fas fa-save"></i>
                                SAVE STATUS CHANGES
                            </button>
                            <p class="text-[10px] text-center text-gray-400 mt-3 italic">Last updated: {{ $order->updated_at->diffForHumans() }}</p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Financial Summary Card -->
            <div class="bg-nidan-dark rounded-2xl shadow-xl p-8 text-white relative overflow-hidden">
                <!-- Decoration -->
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-nidan-gold/10 rounded-full"></div>
                <div class="absolute -left-4 -top-4 w-16 h-16 bg-white/5 rounded-full"></div>

                <h3 class="font-bold mb-8 flex items-center gap-2 relative">
                    <i class="fas fa-coins text-nidan-gold"></i> Payment Details
                </h3>
                
                <div class="space-y-6 relative">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400">Order Subtotal</span>
                        <span class="font-mono">EGP {{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400">Shipping Fees</span>
                        <span class="font-mono text-nidan-gold">+ EGP {{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    
                    <div class="pt-6 border-t border-white/10">
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400 uppercase tracking-widest">Total Amount</span>
                                <span class="text-3xl font-serif font-bold text-nidan-gold">EGP {{ number_format($order->total_price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($order->deposit_amount > 0)
                    <div class="mt-4 p-4 bg-white/5 rounded-xl border border-white/10">
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-gray-400">Deposit Paid</span>
                            <span class="text-green-400">- EGP {{ number_format($order->deposit_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span class="text-sm">Due at Delivery</span>
                            <span class="text-sm">EGP {{ number_format($order->remaining_amount, 2) }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="pt-6 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-500/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-chart-line text-green-400 text-xs"></i>
                            </div>
                            <span class="text-xs text-gray-400">Net Profit</span>
                        </div>
                        <span class="text-lg font-bold text-green-400">EGP {{ number_format($order->net_profit ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Add some interactivity to the status form
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="status"]');
        const submitBtn = document.getElementById('submitBtn');
        const originalStatus = "{{ $order->status }}";

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value !== originalStatus) {
                    submitBtn.classList.add('animate-pulse');
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> UPDATE STATUS TO ' + this.value.toUpperCase().replace('_', ' ');
                } else {
                    submitBtn.classList.remove('animate-pulse');
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> SAVE STATUS CHANGES';
                }
            });
        });
    });
</script>
@endpush
