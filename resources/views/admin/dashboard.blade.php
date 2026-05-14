@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Command Center')
@section('breadcrumb', 'Home / Dashboard')

@section('page-content')

{{-- 1. COMMAND CENTER (Quick Actions) --}}
<div class="mb-10">
    <h3 class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-black mb-5">Artisan Control Panel</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <a href="{{ route('admin.products.create') }}" class="group bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center gap-3 hover:bg-nidan-gold transition-all duration-500 hover:-translate-y-1">
            <div class="w-12 h-12 bg-nidan-gold/10 rounded-2xl flex items-center justify-center text-nidan-gold group-hover:bg-white group-hover:text-nidan-gold transition-colors">
                <i class="fas fa-plus"></i>
            </div>
            <span class="text-[10px] uppercase font-black tracking-widest text-nidan-text group-hover:text-white">New Product</span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending_confirmation']) }}" class="group bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center gap-3 hover:bg-amber-600 transition-all duration-500 hover:-translate-y-1">
            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 group-hover:bg-white group-hover:text-amber-600 transition-colors">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <span class="text-[10px] uppercase font-black tracking-widest text-nidan-text group-hover:text-white">Verify Orders</span>
        </a>
        <a href="{{ route('admin.custom-orders.create') }}" class="group bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center gap-3 hover:bg-indigo-600 transition-all duration-500 hover:-translate-y-1">
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-white group-hover:text-indigo-600 transition-colors">
                <i class="fas fa-magic"></i>
            </div>
            <span class="text-[10px] uppercase font-black tracking-widest text-nidan-text group-hover:text-white">Custom Order</span>
        </a>
        <a href="{{ route('admin.settings.announcement') }}" class="group bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center gap-3 hover:bg-purple-600 transition-all duration-500 hover:-translate-y-1">
            <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 group-hover:bg-white group-hover:text-purple-600 transition-colors">
                <i class="fas fa-bullhorn"></i>
            </div>
            <span class="text-[10px] uppercase font-black tracking-widest text-nidan-text group-hover:text-white">Broadcast</span>
        </a>
        <a href="{{ route('admin.shipping.index') }}" class="group bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center gap-3 hover:bg-teal-600 transition-all duration-500 hover:-translate-y-1">
            <div class="w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-600 group-hover:bg-white group-hover:text-teal-600 transition-colors">
                <i class="fas fa-truck"></i>
            </div>
            <span class="text-[10px] uppercase font-black tracking-widest text-nidan-text group-hover:text-white">Shipping</span>
        </a>
        <a href="{{ route('admin.vendors.index') }}" class="group bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col items-center gap-3 hover:bg-nidan-text transition-all duration-500 hover:-translate-y-1">
            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-nidan-gold group-hover:text-nidan-text transition-colors">
                <i class="fas fa-handshake"></i>
            </div>
            <span class="text-[10px] uppercase font-black tracking-widest text-nidan-text group-hover:text-white">Partners</span>
        </a>
    </div>
</div>

{{-- 2. KPI MATRIX --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
    {{-- Net Profit --}}
    <div class="bg-white p-8 rounded-[2.5rem] shadow-[0_15px_50px_rgba(0,0,0,0.02)] border border-gray-50 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Net Profit</span>
                <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-500/20">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="text-3xl font-serif text-nidan-text">EGP {{ number_format($monthlyProfit->total_profit ?? 0, 0) }}</div>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-green-500 uppercase tracking-widest">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $monthlyProfit->total_orders ?? 0 }} Orders</span>
            </div>
        </div>
    </div>

    {{-- Total Orders --}}
    <div class="bg-white p-8 rounded-[2.5rem] shadow-[0_15px_50px_rgba(0,0,0,0.02)] border border-gray-50 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Total Orders</span>
                <div class="w-10 h-10 bg-nidan-gold rounded-xl flex items-center justify-center text-white shadow-lg shadow-nidan-gold/20">
                    <i class="fas fa-shopping-basket"></i>
                </div>
            </div>
            <div class="text-3xl font-serif text-nidan-text">{{ $totalOrders }}</div>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-amber-500 uppercase tracking-widest">
                <i class="fas fa-clock"></i>
                <span>{{ $cancelledOrders }} Cancelled</span>
            </div>
        </div>
    </div>

    {{-- Revenue --}}
    <div class="bg-nidan-text p-8 rounded-[2.5rem] shadow-[0_30px_60px_rgba(0,0,0,0.12)] border border-nidan-gold/10 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] uppercase tracking-[0.2em] font-black text-nidan-gold/50">Gross Revenue</span>
                <div class="w-10 h-10 bg-nidan-gold rounded-xl flex items-center justify-center text-nidan-text shadow-lg shadow-nidan-gold/30">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
            <div class="text-3xl font-serif text-white">EGP {{ number_format($cityStats->sum('total_revenue') ?? 0, 0) }}</div>
            <div class="mt-4 text-[10px] font-bold text-nidan-gold/40 uppercase tracking-widest">Growth Analytics</div>
        </div>
    </div>

    {{-- Cancellation Rate --}}
    <div class="bg-white p-8 rounded-[2.5rem] shadow-[0_15px_50px_rgba(0,0,0,0.02)] border border-gray-50 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] uppercase tracking-[0.2em] font-black text-gray-400">Health Score</span>
                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-500/20">
                    <i class="fas fa-heartbeat"></i>
                </div>
            </div>
            <div class="text-3xl font-serif {{ $cancellationRate > 10 ? 'text-red-500' : 'text-nidan-text' }}">{{ 100 - $cancellationRate }}%</div>
            <div class="mt-4 text-[10px] font-bold text-gray-300 uppercase tracking-widest">Efficiency Rate</div>
        </div>
    </div>
</div>

{{-- 3. ANALYTICS & INTELLIGENCE --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    {{-- Sales Chart --}}
    <div class="lg:col-span-2 bg-white p-8 rounded-[3rem] shadow-sm border border-gray-50">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="font-serif text-nidan-text text-xl">Revenue Velocity</h3>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mt-1">Last 30 Days Performance</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-nidan-gold"></span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Daily Revenue</span>
            </div>
        </div>
        <div class="h-[300px]">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- Order Status Breakdown --}}
    <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-50 flex flex-col">
        <h3 class="font-serif text-nidan-text text-xl mb-6">Fulfillment Hub</h3>
        <div class="flex-1 space-y-5 overflow-y-auto pr-2 custom-scrollbar">
            @php
                $statusMeta = [
                    'pending_confirmation' => ['label' => 'New Orders', 'icon' => 'fa-clock', 'color' => '#EAB308'],
                    'confirmed' => ['label' => 'Confirmed', 'icon' => 'fa-check', 'color' => '#3B82F6'],
                    'in_preparation' => ['label' => 'At Atelier', 'icon' => 'fa-scissors', 'color' => '#F97316'],
                    'ready_for_shipping' => ['label' => 'Packed', 'icon' => 'fa-box', 'color' => '#A855F7'],
                    'out_for_delivery' => ['label' => 'On Road', 'icon' => 'fa-motorcycle', 'color' => '#6366F1'],
                    'delivered' => ['label' => 'Completed', 'icon' => 'fa-star', 'color' => '#22C55E'],
                ];
            @endphp
            @foreach($statusMeta as $statusKey => $meta)
                <div class="flex items-center gap-4 group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm transition-transform group-hover:scale-110" style="background: {{ $meta['color'] }}15; color: {{ $meta['color'] }}">
                        <i class="fas {{ $meta['icon'] }}"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">{{ $meta['label'] }}</span>
                            <span class="text-xs font-serif font-black text-nidan-text">{{ $statusBreakdown[$statusKey] ?? 0 }}</span>
                        </div>
                        <div class="h-1 bg-gray-50 rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-1000" style="width: {{ $totalOrders > 0 ? (($statusBreakdown[$statusKey] ?? 0) / $totalOrders) * 100 : 0 }}%; background: {{ $meta['color'] }}"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- 4. RECENT ACTIVITY --}}
<div class="bg-white rounded-[3rem] shadow-sm border border-gray-50 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gradient-to-r from-white to-nidan-bg/20">
        <div>
            <h3 class="font-serif text-nidan-text text-xl">Recent Commissions</h3>
            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mt-1">Live Order Stream</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="px-6 py-2 bg-nidan-text text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-nidan-gold transition-all">Archival Search</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50 text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                    <th class="px-8 py-4">ID</th>
                    <th class="px-8 py-4">Patron</th>
                    <th class="px-8 py-4 text-center">Value</th>
                    <th class="px-8 py-4 text-center">Progress</th>
                    <th class="px-8 py-4 text-right">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentOrders as $order)
                <tr class="hover:bg-nidan-bg/10 transition-colors group">
                    <td class="px-8 py-5">
                        <a href="{{ route('admin.orders.show', $order) }}" class="font-serif font-black text-nidan-gold hover:underline">#{{ $order->order_number }}</a>
                    </td>
                    <td class="px-8 py-5">
                        <div class="font-serif text-nidan-text font-bold">{{ $order->customer_name }}</div>
                        <div class="text-[9px] text-gray-400 uppercase tracking-widest">{{ $order->city }}</div>
                    </td>
                    <td class="px-8 py-5 text-center font-serif font-black text-nidan-text">EGP {{ number_format($order->total_price, 0) }}</td>
                    <td class="px-8 py-5">
                        <div class="flex justify-center">
                            @php $statusColors = [
                                'pending_confirmation' => 'bg-yellow-500',
                                'confirmed' => 'bg-blue-500',
                                'in_preparation' => 'bg-orange-500',
                                'ready_for_shipping' => 'bg-purple-500',
                                'out_for_delivery' => 'bg-indigo-500',
                                'delivered' => 'bg-green-500',
                                'cancelled' => 'bg-red-500',
                                'refunded' => 'bg-gray-400',
                            ]; @endphp
                            <span class="flex items-center gap-2 px-3 py-1 rounded-full border border-gray-100 text-[9px] font-black uppercase tracking-widest text-gray-500">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-200' }}"></span>
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-right text-[10px] text-gray-300 font-bold uppercase tracking-tighter">{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($salesData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.map(d => d.date),
            datasets: [{
                label: 'Daily Revenue',
                data: salesData.map(d => d.revenue),
                borderColor: '#D4B87E',
                backgroundColor: 'rgba(212, 184, 198, 0.1)',
                borderWidth: 4,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#D4B87E',
                pointRadius: 0,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#1b1c19',
                    titleFont: { family: 'Noto Serif', size: 12 },
                    bodyFont: { family: 'Manrope', size: 11 },
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return ' EGP ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                    ticks: { font: { family: 'Manrope', size: 10 }, color: '#9ca3af' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Manrope', size: 10 }, color: '#9ca3af' }
                }
            }
        }
    });
</script>
@endpush
