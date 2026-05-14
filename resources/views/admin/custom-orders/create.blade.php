@extends('layouts.admin')
@section('title', 'Create Custom Order')
@section('page-title', 'Custom Order')
@section('breadcrumb', 'Home / Orders / Custom Order')

@section('page-content')

<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <h2 class="font-serif text-xl text-nidan-text mb-6">Create Custom Order</h2>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.custom-orders.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Customer Name *</label>
                    <input type="text" name="guest_name" required value="{{ old('guest_name') }}"
                        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('guest_name') border-red-400 @enderror"
                        placeholder="Customer name">
                    @error('guest_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Phone *</label>
                    <input type="text" name="phone" required value="{{ old('phone') }}"
                        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('phone') border-red-400 @enderror"
                        placeholder="01XXXXXXXXX">
                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">City *</label>
                    <input type="text" name="city" required value="{{ old('city') }}"
                        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('city') border-red-400 @enderror"
                        placeholder="City">
                    @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Selling Price (EGP) *</label>
                    <input type="number" name="selling_price" required step="0.01" value="{{ old('selling_price') }}"
                        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('selling_price') border-red-400 @enderror"
                        placeholder="0.00">
                    @error('selling_price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Cost (EGP) *</label>
                    <input type="number" name="cost" required step="0.01" value="{{ old('cost') }}"
                        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('cost') border-red-400 @enderror"
                        placeholder="0.00">
                    @error('cost') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Shipping Cost (EGP)</label>
                    <input type="number" name="shipping_cost" step="0.01" value="{{ old('shipping_cost', 0) }}"
                        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold"
                        placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Deposit Paid (EGP)</label>
                    <input type="number" name="deposit_amount" step="0.01" value="{{ old('deposit_amount', 0) }}"
                        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold"
                        placeholder="0.00">
                </div>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Address *</label>
                <textarea name="address" required rows="2"
                    class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold @error('address') border-red-400 @enderror"
                    placeholder="Full delivery address">{{ old('address') }}</textarea>
                @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Notes</label>
                <textarea name="notes" rows="2"
                    class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold"
                    placeholder="Custom order notes...">{{ old('notes') }}</textarea>
            </div>
            <div class="pt-4 border-t">
                <button type="submit"
                    class="w-full py-4 bg-nidan-btn text-white font-medium rounded-full hover:bg-[#b59660] transition-all transform hover:scale-[1.01]">
                    Create Custom Order
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
