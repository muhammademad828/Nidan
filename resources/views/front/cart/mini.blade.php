<div class="h-full flex flex-col">
    <div class="px-6 py-8 border-b border-gray-100 flex justify-between items-center bg-nidan-bg">
        <h2 class="text-2xl font-serif text-nidan-text">{{ \App\Services\BilingualService::label('cart') }} (<span id="mini-cart-count">{{ count($cart) }}</span>)</h2>
        <button id="close-cart-btn" class="text-gray-400 hover:text-nidan-gold transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-6 bg-white">
        @if(empty($cart))
            <div class="flex flex-col items-center justify-center h-full text-center">
                <i class="fas fa-shopping-bag text-6xl text-gray-200 mb-4"></i>
                <p class="text-gray-400">{{ \App\Services\BilingualService::label('cart_empty') ?? 'Your cart is empty' }}</p>
            </div>
        @else
            <div class="space-y-6">
                @php $total = 0; @endphp
                @foreach($cart as $index => $item)
                    @php $total += $item['selling_price'] * $item['quantity']; @endphp
                    <div class="flex gap-4 items-center">
                        <img src="{{ $item['image'] ?? 'https://via.placeholder.com/150' }}" class="w-20 h-20 object-cover rounded-xl border border-gray-100">
                        <div class="flex-1">
                            <h4 class="font-serif text-nidan-text text-sm mb-1">{{ $item['name'] }}</h4>
                            <p class="text-nidan-gold text-xs font-bold mb-2">EGP {{ number_format($item['selling_price'], 2) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Qty: {{ $item['quantity'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if(!empty($cart))
        <div class="p-6 bg-nidan-bg border-t border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <span class="text-gray-500 text-sm font-semibold uppercase tracking-widest">{{ \App\Services\BilingualService::label('subtotal') }}</span>
                <span class="text-xl font-serif text-nidan-text">EGP {{ number_format($total, 2) }}</span>
            </div>
            <a href="{{ route('cart', ['locale' => app()->getLocale()]) }}" class="block w-full py-4 text-center bg-nidan-text text-white rounded-full text-xs font-bold tracking-[0.2em] uppercase hover:bg-nidan-gold transition-colors mb-3 shadow-md">
                {{ \App\Services\BilingualService::label('view_cart') }}
            </a>
            <a href="{{ route('checkout', ['locale' => app()->getLocale()]) }}" class="block w-full py-4 text-center bg-nidan-gold text-white rounded-full text-xs font-bold tracking-[0.2em] uppercase hover:bg-[#b59660] transition-colors shadow-md">
                {{ \App\Services\BilingualService::label('checkout') }}
            </a>
        </div>
    @endif
</div>
