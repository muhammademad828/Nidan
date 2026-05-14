@extends('layouts.app')
@php use App\Services\BilingualService; @endphp
@section('content')

<main id="cart-container" class="max-w-7xl mx-auto px-6 md:px-12 py-12 lg:py-24 relative z-10" data-cart-update-template="{{ route('cart.update', ['locale' => app()->getLocale(), 'index' => '__index__', 'delta' => '__delta__']) }}">
    
    <div class="mb-12 relative">
        <h1 class="font-serif text-4xl md:text-5xl text-nidan-text">{{ BilingualService::label('cart') }}</h1>
        <p class="text-gray-500 mt-2 font-light">
            @if(!empty($cart)) 
                {{ app()->getLocale() === 'ar' ? 'لديك ' . count($cart) . ' منتجات في المجموعة.' : 'You have ' . count($cart) . ' items in your collection.' }}
            @endif
        </p>
        
        <!-- Loading Overlay -->
        <div id="cart-loading" class="absolute top-0 right-0 h-full flex items-center hidden">
            <div class="w-6 h-6 border-2 border-nidan-gold border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 px-6 py-4 bg-[#f0eee8] border-l-4 border-nidan-gold text-nidan-text rounded-r-xl shadow-sm text-sm flex items-center gap-3">
            <i class="fas fa-check-circle text-nidan-gold"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-8 px-6 py-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm text-sm flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-500"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(empty($cart))
        <div class="py-32 text-center bg-white/50 border border-dashed border-nidan-gold/30 rounded-[3rem] shadow-sm backdrop-blur-sm">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                <i class="fas fa-shopping-bag text-4xl text-nidan-gold/50"></i>
            </div>
            <h2 class="font-serif text-2xl text-nidan-text mb-4">{{ BilingualService::label('cart_empty') }}</h2>
            <p class="text-gray-400 mb-8 max-w-md mx-auto">{{ app()->getLocale() === 'ar' ? 'يبدو أنك لم تقم بإضافة أي منتجات إلى مجموعتك بعد.' : 'Looks like you haven\'t added anything to your collection yet.' }}</p>
            <a href="{{ route('collections', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center justify-center gap-3 bg-nidan-text text-white px-8 py-4 rounded-full hover:bg-nidan-gold transition-all duration-300 tracking-[0.2em] uppercase text-xs font-bold shadow-lg hover:shadow-nidan-gold/30 flex-row-reverse">
                <span>{{ BilingualService::label('browse_collection') }}</span>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16">
            <!-- Cart Items List -->
            <div class="lg:col-span-2 space-y-6">
                @php $total = 0; @endphp
                @foreach($cart as $index => $item)
                    @php $total += $item['selling_price'] * $item['quantity']; @endphp
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm flex flex-col sm:flex-row gap-6 items-center sm:items-start border border-gray-100 hover:shadow-md transition-shadow relative overflow-hidden group cart-item-card">
                        
                        <!-- Product Image -->
                        <div class="w-24 h-24 sm:w-32 sm:h-32 flex-shrink-0 rounded-[1.5rem] overflow-hidden bg-gray-50 border border-gray-100 relative">
                            <img src="{{ $item['image'] ?? 'https://via.placeholder.com/200x200?text=Nidan' }}"
                                alt="{{ $item['name'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        </div>
                        
                        <!-- Product Details -->
                        <div class="flex-1 w-full flex flex-col justify-between h-full py-2">
                            <div class="flex justify-between items-start mb-4 sm:mb-0">
                                <div>
                                    <h3 class="font-serif text-xl sm:text-2xl text-nidan-text mb-1 group-hover:text-nidan-gold transition-colors">{{ $item['name'] }}</h3>
                                    <p class="text-nidan-gold font-medium">EGP {{ number_format($item['selling_price']) }}</p>
                                </div>
                                
                                <!-- Total Price for this item (Desktop) -->
                                <div class="hidden sm:block {{ app()->getLocale() === 'ar' ? 'text-left' : 'text-right' }}">
                                    <span class="text-[10px] uppercase tracking-widest text-gray-400 block mb-1">{{ app()->getLocale() === 'ar' ? 'المجموع' : 'Total' }}</span>
                                    <p class="font-serif text-lg text-nidan-text">EGP {{ number_format($item['selling_price'] * $item['quantity'], 2) }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50">
                                <!-- Quantity Controls -->
                                <div class="flex items-center border border-gray-200 bg-gray-50 rounded-xl overflow-hidden shadow-inner">
                                    <form method="POST" action="{{ route('cart.update', ['locale' => app()->getLocale(), 'index' => $index, 'delta' => -1]) }}" class="m-0 ajax-cart-form">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-12 h-12 flex items-center justify-center text-gray-400 hover:text-nidan-text hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-minus text-xs pointer-events-none"></i>
                                        </button>
                                    </form>
                                    <span class="w-12 text-center text-sm font-bold text-nidan-text">{{ $item['quantity'] }}</span>
                                    <form method="POST" action="{{ route('cart.update', ['locale' => app()->getLocale(), 'index' => $index, 'delta' => 1]) }}" class="m-0 ajax-cart-form">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-12 h-12 flex items-center justify-center text-gray-400 hover:text-nidan-text hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-plus text-xs pointer-events-none"></i>
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Remove Button -->
                                <form method="POST" action="{{ route('cart.remove', ['locale' => app()->getLocale(), 'index' => $index]) }}" class="m-0 ajax-cart-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex items-center gap-2 text-xs text-red-400 hover:text-red-600 uppercase tracking-widest transition-colors font-semibold">
                                        <i class="far fa-trash-alt pointer-events-none"></i>
                                        <span class="hidden sm:inline pointer-events-none">{{ BilingualService::label('remove') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 sticky top-32">
                    <h3 class="font-serif text-2xl text-nidan-text mb-6">{{ app()->getLocale() === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-gray-600 text-sm">
                            <span>{{ app()->getLocale() === 'ar' ? 'المجموع الفرعي' : 'Subtotal' }}</span>
                            <span class="font-medium text-nidan-text">EGP {{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600 text-sm">
                            <span>{{ app()->getLocale() === 'ar' ? 'الشحن' : 'Shipping' }}</span>
                            <span class="text-[10px] text-nidan-gold uppercase tracking-widest flex items-center gap-1 font-semibold">
                                <i class="fas fa-truck"></i> {{ app()->getLocale() === 'ar' ? 'يُحسب عند الدفع' : 'Calculated at checkout' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="pt-6 border-t border-gray-100 mb-8 flex justify-between items-end">
                        <span class="text-sm font-bold uppercase tracking-widest text-nidan-text">{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</span>
                        <span class="font-serif text-2xl md:text-3xl text-nidan-text">EGP {{ number_format($total, 2) }}</span>
                    </div>

                    <a href="{{ route('checkout', ['locale' => app()->getLocale()]) }}" class="flex items-center justify-center gap-3 w-full bg-nidan-text text-white py-5 rounded-full hover:bg-nidan-gold transition-all duration-300 tracking-[0.2em] uppercase text-xs font-bold shadow-lg hover:shadow-nidan-gold/30">
                        <span>{{ BilingualService::label('proceed_to_checkout') }}</span>
                        <i class="fas fa-lock text-[10px] opacity-70"></i>
                    </a>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('collections', ['locale' => app()->getLocale()]) }}" class="text-[10px] text-gray-400 uppercase tracking-widest hover:text-nidan-gold transition-colors flex items-center justify-center gap-2">
                            <i class="{{ app()->getLocale() === 'ar' ? 'fas fa-arrow-right' : 'fas fa-arrow-left' }}"></i>
                            {{ BilingualService::label('continue_shopping') }}
                        </a>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-gray-100 text-center space-y-4">
                        <div class="flex justify-center items-center gap-6 text-gray-300">
                            <!-- Vodafone Cash -->
                            <div class="flex items-center gap-2 group cursor-pointer grayscale hover:grayscale-0 transition-all duration-300">
                                <svg viewBox="0 0 100 100" class="h-6 w-auto opacity-70 group-hover:opacity-100 transition-opacity" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <circle cx="50" cy="50" r="50" fill="#E60000"/>
                                  <path d="M48.2,25.4c-19,0-34.5,15.5-34.5,34.5c0,15.5,10.2,28.7,24.3,33c-3-8.5-4.8-18-4.8-27.8c0-15.5,10.3-28.7,24.5-33C54.8,28.8,51.6,25.4,48.2,25.4z" fill="#FFFFFF"/>
                                </svg>
                                <span class="font-bold text-xs text-gray-400 group-hover:text-[#E60000] transition-colors">Vodafone Cash</span>
                            </div>
                            
                            <!-- InstaPay -->
                            <div class="flex items-center group cursor-pointer grayscale hover:grayscale-0 transition-all duration-300 opacity-70 hover:opacity-100">
                                <div class="bg-gray-100 px-3 py-1 rounded-md border border-gray-200 group-hover:border-[#571b7e]/30 group-hover:bg-white transition-all shadow-sm">
                                    <span class="font-sans font-black italic tracking-tighter text-gray-500 group-hover:text-[#571b7e] transition-colors text-[13px]">insta<span class="text-gray-400 group-hover:text-[#e91c78] transition-colors">pay</span></span>
                                </div>
                            </div>
                        </div>
                        <p class="text-[9px] uppercase tracking-[0.2em] text-gray-400">{{ app()->getLocale() === 'ar' ? 'دفع إلكتروني آمن وموثوق' : 'Secure & Trusted Payment' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</main>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    function attachAjaxForms() {
        document.querySelectorAll('.ajax-cart-form').forEach(form => {
            // Remove previous event listeners to prevent duplicates
            form.removeEventListener('submit', handleCartAjaxSubmit);
            form.addEventListener('submit', handleCartAjaxSubmit);
        });
    }

    async function handleCartAjaxSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // Show loading state
        const loadingSpinner = document.getElementById('cart-loading');
        if (loadingSpinner) loadingSpinner.classList.remove('hidden');
        document.querySelectorAll('.cart-item-card').forEach(c => c.classList.add('opacity-50', 'pointer-events-none'));
        
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST', // the form uses POST with _method=PATCH/DELETE
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });
            
            // fetch follows redirects automatically
            // The response will be the full HTML of the cart page
            const html = await response.text();
            
            // Parse the HTML
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newCartContent = doc.getElementById('cart-container');
            
            if (newCartContent) {
                document.getElementById('cart-container').innerHTML = newCartContent.innerHTML;
                // Re-attach event listeners to new forms
                attachAjaxForms();
                
                // Also update the mini-cart count in the header if it exists
                const newHeaderHtml = doc.querySelector('header')?.innerHTML;
                if(newHeaderHtml) {
                    const currentHeader = document.querySelector('header');
                    if(currentHeader) currentHeader.innerHTML = newHeaderHtml;
                }
            }
        } catch (error) {
            console.error('Error updating cart:', error);
            // Fallback: just submit the form normally
            form.submit();
        }
    }

    // Initial attach
    attachAjaxForms();
});
</script>
@endpush
