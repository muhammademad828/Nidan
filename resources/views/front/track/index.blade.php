@extends('layouts.app')
@php use App\Services\BilingualService; @endphp

@section('content')

<section class="py-24 px-6 md:px-12 relative z-10 min-h-[70vh] flex items-center">
    <div class="max-w-4xl mx-auto w-full">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-serif text-nidan-text mb-3">
                {{ auth()->check() ? BilingualService::label('your_orders') : BilingualService::label('track_order') }}
            </h1>
            <p class="text-gray-500">
                {{ auth()->check() ? BilingualService::label('track_logged_desc') : BilingualService::label('track_desc') }}
            </p>
        </div>

        @if(session('error'))
            <div class="max-w-xl mx-auto mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm text-center">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="max-w-xl mx-auto mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm text-center">{{ session('success') }}</div>
        @endif

        @if(auth()->check())
            @if(isset($orders) && $orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-6">
                                <div class="w-12 h-12 bg-nidan-gold/10 rounded-xl flex items-center justify-center text-nidan-gold">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="font-mono font-bold text-lg text-nidan-text">{{ $order->order_number }}</h3>
                                    <p class="text-xs text-gray-400">{{ BilingualService::label('order_date') }}: {{ $order->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-4 md:gap-8">
                                <div class="text-center md:text-right">
                                    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">{{ BilingualService::label('total') }}</p>
                                    <p class="font-bold text-nidan-text">{{ number_format($order->total_price, 2) }} {{ app()->getLocale() == 'ar' ? 'ج.م' : 'EGP' }}</p>
                                </div>
                                
                                <div class="px-4 py-1.5 rounded-full text-xs font-medium 
                                    @if($order->status == 'delivered') bg-green-100 text-green-700
                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-700
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ $order->status_label }}
                                </div>

                                <form method="POST" action="{{ route('track.search') }}">
                                    @csrf
                                    <input type="hidden" name="order_number" value="{{ $order->order_number }}">
                                    <button type="submit" class="px-6 py-2 bg-nidan-btn text-white text-sm rounded-full hover:bg-opacity-90 transition-all">
                                        {{ BilingualService::label('view_details') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <p class="text-gray-500 mb-8">{{ BilingualService::label('no_orders_found') }}</p>
                    <a href="{{ route('collections') }}" class="inline-block px-8 py-3 bg-nidan-gold text-white rounded-full hover:bg-opacity-90 transition-all">
                        {{ BilingualService::label('browse_collection') }}
                    </a>
                </div>
            @endif
        @else
            <div class="max-w-xl mx-auto">
                <form method="POST" action="{{ route('track.search') }}" class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">{{ BilingualService::label('order_number') }}</label>
                            <input type="text" name="order_number" required
                                class="w-full px-4 py-3 border rounded-xl text-center font-mono text-lg focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('order_number') border-red-400 @enderror"
                                placeholder="NID-XXXXXXXX"
                                value="{{ old('order_number') }}">
                            @error('order_number') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit"
                            class="w-full bg-nidan-btn text-white font-medium py-4 rounded-full hover:bg-[#b59660] transition-all">
                            {{ BilingualService::label('track_button') }}
                        </button>
                    </div>
                </form>
                <p class="text-xs text-gray-400 mt-6 text-center">{{ BilingualService::label('need_help') }} <a href="mailto:hello@nidanatelier.com" class="text-nidan-gold underline">{{ BilingualService::label('contact') }}</a></p>
            </div>
        @endif
    </div>
</section>

@endsection
