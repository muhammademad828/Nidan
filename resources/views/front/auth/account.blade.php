@extends('layouts.luxury')

@section('meta')
    <title>My Account — Nidan Atelier</title>
@endsection

@section('content')
<nav class="bg-[#fbf9f3]/80 dark:bg-stone-950/80 backdrop-blur-xl docked full-width top-0 sticky z-50 transition-opacity flex justify-between items-center w-full px-6 md:px-12 py-6 max-w-[1920px] mx-auto">
    <div class="flex items-center gap-12">
        <a href="{{ route('home') }}" class="text-2xl font-serif tracking-[0.2em] text-[#775a19] dark:text-[#c5a059]">NIDAN ATELIER</a>
    </div>
    <div class="flex items-center gap-8">
        <span class="text-[10px] tracking-widest uppercase text-secondary font-medium hidden md:inline">{{ $user->email }}</span>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="group flex items-center gap-2 text-[10px] tracking-widest uppercase text-primary hover:text-on-primary-fixed-variant transition-all font-bold">
                Logout
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform" data-icon="logout">logout</span>
            </button>
        </form>
    </div>
</nav>

<main class="min-h-screen bg-[#fbf9f3] dark:bg-stone-950">
    <!-- Header Banner -->
    <div class="relative py-24 bg-[#775a19] overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('{{ asset('heritage_pattern_gold.png') }}')] bg-repeat bg-[length:200px]"></div>
        <div class="max-w-[1440px] mx-auto px-6 md:px-12 relative z-10">
            <span class="text-[10px] tracking-[0.4em] uppercase text-[#e9dab9] font-bold block mb-4">Welcome back</span>
            <h1 class="font-headline text-5xl md:text-7xl text-white">{{ explode(' ', $user->name)[0] }}'s Portal</h1>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-6 md:px-12 -mt-16 relative z-20 pb-32">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Sidebar: Profile Details -->
            <div class="lg:col-span-4">
                <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-2xl shadow-primary/5 p-10 border border-outline-variant/10 sticky top-32">
                    <div class="flex items-center gap-6 mb-10 pb-10 border-b border-outline-variant/10">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center text-primary font-headline text-2xl uppercase">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="font-headline text-2xl text-on-background">{{ $user->name }}</h2>
                            <p class="text-[10px] tracking-widest uppercase text-secondary mt-1">Premium Client</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary text-xl" data-icon="mail">mail</span>
                            <div>
                                <p class="text-[10px] tracking-widest uppercase text-outline mb-1">Email</p>
                                <p class="text-sm font-medium text-on-background">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary text-xl" data-icon="phone">phone</span>
                            <div>
                                <p class="text-[10px] tracking-widest uppercase text-outline mb-1">Phone</p>
                                <p class="text-sm font-medium text-on-background">{{ $user->phone }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary text-xl" data-icon="calendar_today">calendar_today</span>
                            <div>
                                <p class="text-[10px] tracking-widest uppercase text-outline mb-1">Joined</p>
                                <p class="text-sm font-medium text-on-background">{{ $user->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 pt-10 border-t border-outline-variant/10">
                        <span class="text-[10px] tracking-[0.4em] uppercase text-primary font-bold block mb-6">Security Settings</span>
                        
                        @if(session('success'))
                            <div class="mb-4 p-3 bg-green-50 text-green-600 text-xs rounded-lg border border-green-100">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('password.change') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-[9px] tracking-widest uppercase text-outline block mb-1">Current Password</label>
                                <input type="password" name="current_password" required class="w-full px-3 py-3 rounded-lg border border-outline-variant/30 text-xs focus:ring-0 focus:border-primary">
                                @error('current_password')
                                    <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="text-[9px] tracking-widest uppercase text-outline block mb-1">New Password</label>
                                <input type="password" name="password" required class="w-full px-3 py-3 rounded-lg border border-outline-variant/30 text-xs focus:ring-0 focus:border-primary">
                                @error('password')
                                    <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="text-[9px] tracking-widest uppercase text-outline block mb-1">Confirm New Password</label>
                                <input type="password" name="password_confirmation" required class="w-full px-3 py-3 rounded-lg border border-outline-variant/30 text-xs focus:ring-0 focus:border-primary">
                            </div>
                            <button type="submit" class="w-full py-3 bg-primary text-white rounded-lg text-[9px] tracking-widest uppercase font-bold hover:bg-on-primary-fixed-variant transition-all">
                                Update Password
                            </button>
                        </form>
                    </div>

                    <div class="mt-12 pt-10 border-t border-outline-variant/10">
                        <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center gap-2 py-4 border border-primary/30 text-primary rounded-xl text-[10px] tracking-widest uppercase font-bold hover:bg-primary/5 transition-all">
                            Explore Collections
                            <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content: Orders History -->
            <div class="lg:col-span-8">
                <div class="bg-white/50 dark:bg-stone-900/50 backdrop-blur-md rounded-2xl p-10 border border-outline-variant/10">
                    <div class="flex justify-between items-end mb-12">
                        <div>
                            <span class="text-[10px] tracking-[0.3em] uppercase text-primary font-bold block mb-2">Heritage Tracking</span>
                            <h3 class="font-headline text-4xl text-on-background">Order History</h3>
                        </div>
                        <span class="text-[10px] tracking-widest uppercase text-secondary bg-surface-container-high px-4 py-2 rounded-full">
                            {{ $user->orders->count() }} Total Orders
                        </span>
                    </div>

                    <div class="space-y-6">
                        @forelse($user->orders->sortByDesc('created_at') as $order)
                            <div class="group relative bg-white dark:bg-stone-900 p-8 rounded-2xl border border-outline-variant/10 hover:border-primary/30 transition-all duration-300 shadow-sm hover:shadow-xl hover:shadow-primary/5">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                    <div class="flex items-center gap-6">
                                        <div class="w-12 h-12 bg-surface-container-high rounded-full flex items-center justify-center text-secondary group-hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined" data-icon="package">package</span>
                                        </div>
                                        <div>
                                            <p class="font-headline text-xl text-on-background mb-1">Order #{{ $order->order_number ?? $order->id }}</p>
                                            <p class="text-[10px] tracking-widest uppercase text-secondary">
                                                {{ $order->created_at->format('d M Y') }} • 
                                                <span class="text-primary font-bold">EGP {{ number_format($order->total_price, 2) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end">
                                        <div class="text-right">
                                            @php
                                                $statusColors = [
                                                    'pending_confirmation' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                    'confirmed' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'delivered' => 'bg-green-50 text-green-600 border-green-100',
                                                    'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                                ];
                                                $statusClass = $statusColors[$order->status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                            @endphp
                                            <span class="text-[9px] tracking-[0.2em] uppercase px-4 py-2 rounded-full font-bold border {{ $statusClass }}">
                                                {{ str_replace('_', ' ', $order->status) }}
                                            </span>
                                        </div>
                                        <a href="{{ route('track') }}?order_number={{ $order->order_number }}" class="p-3 rounded-full hover:bg-primary/10 text-outline hover:text-primary transition-all">
                                            <span class="material-symbols-outlined" data-icon="visibility">visibility</span>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Items Preview -->
                                @if($order->items && $order->items->count() > 0)
                                <div class="mt-8 pt-8 border-t border-outline-variant/10 flex gap-4 overflow-x-auto no-scrollbar">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center gap-3 bg-surface-container-lowest p-2 rounded-lg pr-4 min-w-max">
                                            <div class="w-10 h-10 bg-surface-container-high rounded-md overflow-hidden">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ $item->product->image }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-[8px] text-outline uppercase">Nidan</div>
                                                @endif
                                            </div>
                                            <span class="text-[10px] font-medium text-secondary">{{ $item->product_name_snapshot }} (x{{ $item->quantity }})</span>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-32 text-center bg-white dark:bg-stone-900 rounded-2xl border border-dashed border-outline-variant/30">
                                <div class="w-20 h-20 bg-surface-container-low rounded-full flex items-center justify-center mx-auto mb-6">
                                    <span class="material-symbols-outlined text-4xl text-outline" data-icon="shopping_basket">shopping_basket</span>
                                </div>
                                <h4 class="font-headline text-2xl text-on-background mb-2">No heritage pieces yet</h4>
                                <p class="text-sm text-secondary mb-8">Start your collection with Nidan Atelier's finest creations.</p>
                                <a href="{{ route('collections') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white rounded-xl text-[10px] tracking-widest uppercase font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-transform">
                                    Begin Shopping
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="bg-stone-900 py-12 text-center">
    <span class="text-[10px] font-['Manrope'] tracking-[0.3em] uppercase text-stone-500">© {{ date('Y') }} NIDAN ATELIER — CURATING THE HERITAGE OF TOMORROW</span>
</footer>
@endsection
