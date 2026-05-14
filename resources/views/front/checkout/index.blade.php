@extends('layouts.app')
@php use App\Services\BilingualService; @endphp

@section('content')

@php
    $selectedAddonIds = array_map('intval', old('addons', collect($cart)->flatMap(fn ($item) => $item['addons'] ?? [])->unique()->all()));
    $selectedAddons = $addons->whereIn('id', $selectedAddonIds);
    $baseSubtotal = collect($cart)->sum(fn ($item) => (float) $item['selling_price'] * (int) $item['quantity']);
    $addonsSubtotal = $selectedAddons->sum(fn ($addon) => (float) $addon->price);
    $checkoutSubtotal = $baseSubtotal + $addonsSubtotal;
@endphp

<section class="py-16 px-6 md:px-12 relative z-10">
    <div class="max-w-3xl mx-auto">

        <h1 class="text-4xl font-serif text-nidan-text mb-2">{{ BilingualService::label('checkout') }}</h1>
        <p class="text-sm text-gray-400 mb-8">{{ BilingualService::label('guest_checkout') }}</p>

        @if(session('error'))
            <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('checkout.store') }}" class="space-y-8">
            @csrf
            <input type="hidden" name="idempotency_key" value="{{ old('idempotency_key', Str::uuid()) }}">

            <!-- Contact Info -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <h2 class="font-serif text-xl text-nidan-text mb-6">{{ BilingualService::label('contact_info') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">{{ BilingualService::label('full_name') }} *</label>
                        <input type="text" name="guest_name" required
                            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('guest_name') border-red-400 @enderror"
                            value="{{ old('guest_name', auth()->user()?->name) }}" placeholder="Your full name">
                        @error('guest_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">{{ BilingualService::label('phone') }} *</label>
                        <input type="tel" name="phone" required
                            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('phone') border-red-400 @enderror"
                            value="{{ old('phone', auth()->user()?->phone) }}" placeholder="01XXXXXXXXX">
                        @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">{{ BilingualService::label('email') }}</label>
                        <input type="email" name="guest_email"
                            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold"
                            value="{{ old('guest_email', auth()->user()?->email) }}" placeholder="optional@email.com">
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">{{ BilingualService::label('gender') }}</label>
                        <select name="guest_gender"
                            class="w-full px-5 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold appearance-none bg-no-repeat bg-[length:1em_1em] {{ app()->getLocale() === 'ar' ? 'bg-[left_1rem_center] pl-10' : 'bg-[right_1rem_center] pr-10' }}"
                            style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%232D2319%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E');">
                            <option value="">{{ BilingualService::label('prefer_not_to_say') }}</option>
                            <option value="male" @selected(old('guest_gender', auth()->user()?->gender) === 'male')>{{ BilingualService::label('male') }}</option>
                            <option value="female" @selected(old('guest_gender', auth()->user()?->gender) === 'female')>{{ BilingualService::label('female') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <h2 class="font-serif text-xl text-nidan-text mb-6">{{ BilingualService::label('delivery_details') }}</h2>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">{{ BilingualService::label('governorate') ?? 'Governorate' }} *</label>
                            <select name="governorate_id" id="governorate-select" required
                                class="w-full px-5 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('governorate_id') border-red-400 @enderror appearance-none bg-no-repeat bg-[length:1em_1em] {{ app()->getLocale() === 'ar' ? 'bg-[left_1rem_center] pl-10' : 'bg-[right_1rem_center] pr-10' }}"
                                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%232D2319%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E');">
                                <option value="">{{ BilingualService::label('select_governorate') ?? 'Select Governorate' }}</option>
                                @foreach($governorates as $gov)
                                    <option value="{{ $gov->id }}" @selected(old('governorate_id', auth()->user()?->governorate_id) == $gov->id)>{{ $gov->name }}</option>
                                @endforeach
                            </select>
                            @error('governorate_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">{{ BilingualService::label('city') }} *</label>
                            <select name="city_id" id="city-select" required disabled
                                class="w-full px-5 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('city_id') border-red-400 @enderror appearance-none bg-no-repeat bg-[length:1em_1em] {{ app()->getLocale() === 'ar' ? 'bg-[left_1rem_center] pl-10' : 'bg-[right_1rem_center] pr-10' }}"
                                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%232D2319%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E');">
                                <option value="">{{ BilingualService::label('select_governorate_first') ?? 'Select Governorate First' }}</option>
                            </select>
                            @error('city_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">{{ BilingualService::label('full_address') }} *</label>
                        <textarea name="address" required rows="3"
                            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('address') border-red-400 @enderror"
                            placeholder="Street, building, floor, landmark...">{{ old('address', auth()->user()?->address) }}</textarea>
                        @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">{{ BilingualService::label('order_notes') }}</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold"
                            placeholder="Special instructions...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Add-ons -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <h2 class="font-serif text-xl text-nidan-text mb-2">{{ BilingualService::label('addons') }}</h2>
                <p class="text-xs uppercase tracking-widest text-gray-500 mb-5">{{ app()->getLocale() === 'ar' ? 'تُطبق مرة واحدة على الطلب' : 'Applied once to the whole order' }}</p>

                @if($addons->isEmpty())
                    <p class="text-sm text-gray-500">{{ BilingualService::label('no_addons') }}</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($addons as $addon)
                            <label class="group relative flex items-center gap-4 bg-gray-50/50 border border-gray-100 rounded-2xl p-4 hover:border-nidan-gold hover:bg-white transition-all duration-300 cursor-pointer shadow-sm hover:shadow-md">
                                <div class="relative flex-shrink-0">
                                    <input
                                        type="checkbox"
                                        name="addons[]"
                                        value="{{ $addon->id }}"
                                        data-price="{{ (float) $addon->price }}"
                                        class="addon-checkbox absolute top-0 left-0 opacity-0 w-full h-full cursor-pointer z-10"
                                        @checked(in_array((int) $addon->id, $selectedAddonIds, true))
                                    >
                                    <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center transition-colors group-hover:border-nidan-gold checkbox-custom">
                                        <i class="fas fa-check text-[10px] text-white hidden"></i>
                                    </div>
                                </div>

                                @if($addon->image)
                                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-gray-100 shadow-sm flex-shrink-0">
                                        <img src="{{ asset('storage/' . $addon->image) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                @else
                                    <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center text-gray-300 flex-shrink-0">
                                        <i class="fas fa-image text-xl"></i>
                                    </div>
                                @endif

                                <div class="flex-grow min-w-0">
                                    <h4 class="text-sm font-bold text-nidan-text truncate group-hover:text-nidan-gold transition-colors">{{ $addon->name }}</h4>
                                    @if($addon->description)
                                        <p class="text-[11px] text-gray-500 mt-1 line-clamp-1">{{ $addon->description }}</p>
                                    @endif
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-xs font-bold text-nidan-gold">+ EGP {{ number_format((float) $addon->price, 2) }}</span>
                                    </div>

                                    @if($addon->has_message)
                                        <div class="mt-3 addon-message-container hidden">
                                            <textarea 
                                                name="addon_messages[{{ $addon->id }}]" 
                                                rows="2" 
                                                class="w-full text-xs border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold p-3 bg-white" 
                                                placeholder="{{ $addon->placeholder ?? (app()->getLocale() === 'ar' ? 'اكتب رسالتك هنا...' : 'Write your message here...') }}"
                                            >{{ old("addon_messages.{$addon->id}") }}</textarea>
                                            @error("addon_messages.{$addon->id}")
                                                <p class="mt-1 text-[10px] text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <style>
                        .addon-checkbox:checked + .checkbox-custom {
                            background-color: #c5a367;
                            border-color: #c5a367;
                        }
                        .addon-checkbox:checked + .checkbox-custom i {
                            display: block;
                        }
                        [dir="rtl"] .addon-checkbox {
                            left: auto;
                            right: 0;
                        }
                    </style>
                    @error('addons') <p class="mt-3 text-xs text-red-500">{{ $message }}</p> @enderror
                    @error('addons.*') <p class="mt-3 text-xs text-red-500">{{ $message }}</p> @enderror
                @endif
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <h2 class="font-serif text-xl text-nidan-text mb-6">{{ BilingualService::label('order_summary') }}</h2>
                <div class="space-y-3 mb-6">
                    @foreach($cart as $item)
                        @php
                            $lineBaseTotal = (float) $item['selling_price'] * (int) $item['quantity'];
                        @endphp
                        <div class="flex justify-between text-sm cart-item" 
                             data-base-total="{{ $lineBaseTotal }}" 
                             data-quantity="{{ (int) $item['quantity'] }}">
                            <span class="text-gray-600">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                            <span class="font-medium">EGP {{ number_format($lineBaseTotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 pt-4 space-y-2">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>{{ BilingualService::label('items_subtotal') }}</span>
                        <span>EGP {{ number_format($baseSubtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>{{ BilingualService::label('addons') }}</span>
                        <span id="addons-summary-total">EGP {{ number_format($addonsSubtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>{{ BilingualService::label('shipping') }}</span>
                        <span id="shipping-summary-price">EGP 0.00</span>
                    </div>
                    <div class="flex justify-between font-serif text-lg text-nidan-text pt-2 border-t border-gray-100">
                        <span>{{ BilingualService::label('total') }}</span>
                        <span id="checkout-total">EGP {{ number_format($checkoutSubtotal, 2) }}</span>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" name="terms" id="terms-checkbox" required
                            class="mt-1 w-4 h-4 rounded border-gray-300 text-nidan-gold focus:ring-nidan-gold cursor-pointer transition-colors"
                            @checked(old('terms'))
                        >
                        <span class="text-xs text-gray-500 leading-relaxed group-hover:text-gray-700 transition-colors">
                            {{ BilingualService::label('i_agree_to') }} 
                            <a href="{{ route('policy', ['type' => 'terms', 'locale' => app()->getLocale()]) }}" target="_blank" class="text-nidan-gold font-bold hover:underline">
                                {{ BilingualService::label('terms_and_conditions') }}
                            </a>
                        </span>
                    </label>
                    @error('terms')
                        <p class="mt-2 text-xs text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" id="submit-order-btn"
                    class="w-full mt-6 bg-nidan-btn text-white font-bold py-4 rounded-full shadow-lg hover:bg-[#b59660] transition-all transform hover:scale-[1.01] active:scale-95">
                    {{ BilingualService::label('place_order_cod') }}
                </button>
            </div>
        </form>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const govSelect = document.getElementById('governorate-select');
        const citySelect = document.getElementById('city-select');
        const shippingSummary = document.getElementById('shipping-summary-price');
        const addonCheckboxes = document.querySelectorAll('.addon-checkbox');
        const addonsSummaryTotal = document.getElementById('addons-summary-total');
        const checkoutTotal = document.getElementById('checkout-total');
        const cartItems = document.querySelectorAll('.cart-item');

        let currentShippingPrice = 0;
        const savedCityId = @json(old('city_id', auth()->user()?->city_id));

        function formatPrice(price) {
            return 'EGP ' + price.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function updateTotals() {
            let totalAddonsPrice = 0;
            addonCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    totalAddonsPrice += parseFloat(checkbox.dataset.price);
                }
            });

            let totalItemsPrice = 0;
            cartItems.forEach(item => {
                totalItemsPrice += parseFloat(item.dataset.baseTotal);
            });

            const grandTotal = totalItemsPrice + totalAddonsPrice + currentShippingPrice;

            // Update summary displays
            if (addonsSummaryTotal) {
                addonsSummaryTotal.textContent = formatPrice(totalAddonsPrice);
            }
            if (shippingSummary) {
                shippingSummary.textContent = formatPrice(currentShippingPrice);
            }
            if (checkoutTotal) {
                checkoutTotal.textContent = formatPrice(grandTotal);
            }
        }

        function loadCities(govId, preselectedId = null) {
            citySelect.innerHTML = '<option value="">{{ BilingualService::label('loading') ?? 'Loading...' }}</option>';
            citySelect.disabled = true;
            
            const urlTemplate = "{{ route('locations.cities', ['locale' => app()->getLocale(), 'governorate' => ':id']) }}";
            const fetchUrl = urlTemplate.replace(':id', govId);

            fetch(fetchUrl)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(cities => {
                    citySelect.innerHTML = '<option value="">{{ BilingualService::label('select_city') }}</option>';
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.dataset.price = city.shipping_price;
                        option.textContent = "{{ app()->getLocale() }}" === 'ar' ? city.name_ar : city.name_en;
                        
                        if (preselectedId && preselectedId == city.id) {
                            option.selected = true;
                            currentShippingPrice = parseFloat(city.shipping_price);
                        }
                        
                        citySelect.appendChild(option);
                    });
                    citySelect.disabled = false;
                    updateTotals();
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    citySelect.innerHTML = '<option value="">{{ app()->getLocale() === 'ar' ? "خطأ في تحميل المدن" : "Error loading cities" }}</option>';
                });
        }

        govSelect.addEventListener('change', function() {
            const govId = this.value;
            currentShippingPrice = 0;
            updateTotals();

            if (!govId) {
                citySelect.innerHTML = '<option value="">{{ BilingualService::label('select_governorate_first') ?? 'Select Governorate First' }}</option>';
                citySelect.disabled = true;
                return;
            }

            loadCities(govId);
        });

        citySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            currentShippingPrice = selectedOption ? parseFloat(selectedOption.dataset.price || 0) : 0;
            updateTotals();
        });

        addonCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const label = this.closest('label');
                const container = label.querySelector('.addon-message-container');
                const textarea = label.querySelector('textarea');

                if (container) {
                    if (this.checked) {
                        container.classList.remove('hidden');
                        if (textarea) textarea.required = true;
                    } else {
                        container.classList.add('hidden');
                        if (textarea) textarea.required = false;
                    }
                }
                updateTotals();
            });

            if (checkbox.checked) {
                const label = checkbox.closest('label');
                const container = label.querySelector('.addon-message-container');
                const textarea = label.querySelector('textarea');
                if (container) {
                    container.classList.remove('hidden');
                    if (textarea) textarea.required = true;
                }
            }
        });

        // Terms and Conditions behavior
        const termsCheckbox = document.getElementById('terms-checkbox');
        const submitBtn = document.getElementById('submit-order-btn');

        if (termsCheckbox && submitBtn) {
            const updateSubmitBtn = () => {
                submitBtn.disabled = !termsCheckbox.checked;
                submitBtn.style.opacity = termsCheckbox.checked ? '1' : '0.5';
                submitBtn.style.cursor = termsCheckbox.checked ? 'pointer' : 'not-allowed';
            };

            termsCheckbox.addEventListener('change', updateSubmitBtn);
            updateSubmitBtn();
        }

        // Initial load for logged in users or old input
        if (govSelect.value) {
            loadCities(govSelect.value, savedCityId);
        } else {
            updateTotals();
        }
    });
</script>
@endpush
