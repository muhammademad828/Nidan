@extends('layouts.admin')
@section('title', 'Orders')
@section('page-title', 'Orders Management')
@section('breadcrumb', 'Home / Orders')

@section('page-content')

{{-- Summary Stats --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 group hover:border-nidan-gold transition-all">
        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
            <i class="fas fa-clock text-xl"></i>
        </div>
        <div>
            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Pending</div>
            <div class="text-2xl font-bold text-gray-900">{{ \App\Models\Order::where('status', 'pending_confirmation')->count() }}</div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 group hover:border-nidan-gold transition-all">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
            <i class="fas fa-check-circle text-xl"></i>
        </div>
        <div>
            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Confirmed</div>
            <div class="text-2xl font-bold text-gray-900">{{ \App\Models\Order::where('status', 'confirmed')->count() }}</div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 group hover:border-nidan-gold transition-all">
        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
            <i class="fas fa-truck text-xl"></i>
        </div>
        <div>
            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">In Transit</div>
            <div class="text-2xl font-bold text-gray-900">{{ \App\Models\Order::whereIn('status', ['out_for_delivery', 'ready_for_shipping'])->count() }}</div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 group hover:border-nidan-gold transition-all">
        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
            <i class="fas fa-calendar-check text-xl"></i>
        </div>
        <div>
            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Total Sales</div>
            <div class="text-2xl font-bold text-gray-900">{{ \App\Models\Order::where('status', 'delivered')->count() }}</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
    <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-2 block">Search Orders</label>
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, name, phone..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-nidan-gold transition-all">
            </div>
        </div>
        <div class="w-full md:w-48">
            <label class="text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-2 block">Status</label>
            <select name="status" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-nidan-gold appearance-none">
                <option value="">All Statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                        {{ str_replace('_', ' ', ucfirst($s)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-4 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none px-8 py-3 bg-nidan-gold text-white text-sm font-bold rounded-xl shadow-lg shadow-nidan-gold/20 hover:bg-[#b59660] transition-all transform hover:scale-105 active:scale-95">
                Filter
            </button>
            <a href="{{ route('admin.orders.index') }}" class="px-6 py-3 text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">Reset</a>
        </div>
        <div class="w-full md:w-auto">
            <a href="{{ route('admin.custom-orders.create') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-black transition-all transform hover:scale-105">
                <i class="fas fa-plus-circle"></i>
                <span>Custom Order</span>
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold border-b border-gray-100">
                    <th class="px-6 py-5">Order #</th>
                    <th class="px-6 py-5">Customer</th>
                    <th class="px-6 py-5 text-center">Items</th>
                    <th class="px-6 py-5">Total Amount</th>
                    <th class="px-6 py-5">Current Status</th>
                    <th class="px-6 py-5">Placed At</th>
                    <th class="px-6 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="group hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-6">
                        <div class="flex flex-col">
                            <span class="font-mono text-sm font-bold text-nidan-gold">{{ $order->order_number }}</span>
                            @if($order->deposit_amount > 0)
                                <span class="text-[9px] text-amber-500 font-bold uppercase tracking-tighter">Partially Paid</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-nidan-gold/10 flex items-center justify-center text-nidan-gold font-bold text-xs">
                                {{ substr($order->customer_name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900">{{ $order->customer_name }}</span>
                                <span class="text-xs text-gray-400">{{ $order->city }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-6 text-center">
                        <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold">
                            {{ $order->items->count() }}
                        </span>
                    </td>
                    <td class="px-6 py-6">
                        <div class="text-sm font-bold text-gray-900">EGP {{ number_format($order->total_price, 2) }}</div>
                        <div class="text-[10px] text-gray-400 uppercase tracking-tighter">Net Profit: {{ number_format($order->net_profit ?? 0, 2) }}</div>
                    </td>
                    <td class="px-6 py-6">
                        @php $colors = [
                            'pending_confirmation' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'confirmed' => 'bg-blue-50 text-blue-600 border-blue-100',
                            'in_preparation' => 'bg-orange-50 text-orange-600 border-orange-100',
                            'ready_for_shipping' => 'bg-purple-50 text-purple-600 border-purple-100',
                            'out_for_delivery' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                            'delivered' => 'bg-green-50 text-green-600 border-green-100',
                            'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                            'refunded' => 'bg-gray-100 text-gray-600 border-gray-200',
                        ]; @endphp
                        <span class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $colors[$order->status] ?? 'bg-gray-100' }}">
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-6">
                        <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                        <div class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $order->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-6 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}"
                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-nidan-gold hover:text-white transition-all transform hover:scale-110 active:scale-95 shadow-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-shopping-basket text-3xl text-gray-200"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-400 italic">No heritage pieces discovered yet.</h4>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-6 py-6 border-t border-gray-50">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
