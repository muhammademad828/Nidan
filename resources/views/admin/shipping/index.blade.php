@extends('layouts.admin')

@section('title', 'Shipping Rates')
@section('page-title', 'Shipping Rates')
@section('breadcrumb', 'Home / Shipping')

@section('page-content')
<div class="max-w-6xl">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-serif font-bold text-nidan-text">Manage Shipping Rates</h2>
            <p class="text-gray-500 text-sm">Set shipping prices for each city and governorate.</p>
        </div>
        <button onclick="document.getElementById('add-city-modal').classList.remove('hidden')" class="px-6 py-3 bg-nidan-gold text-white rounded-full font-bold shadow-lg hover:bg-[#b59660] transition-all transform hover:scale-105 flex items-center gap-2">
            <i class="fas fa-plus"></i> Add New City
        </button>
    </div>

    <div class="space-y-8">
        @foreach($governorates as $gov)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center gap-3">
                        <i class="fas fa-map-marker-alt text-nidan-gold"></i>
                        {{ $gov->name_en }} / {{ $gov->name_ar }}
                    </h3>
                    <span class="text-xs bg-nidan-gold/10 text-nidan-gold px-3 py-1 rounded-full font-medium">
                        {{ $gov->cities->count() }} Cities
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-50">
                                <th class="px-6 py-3">City Name (EN)</th>
                                <th class="px-6 py-3 text-right">أسم المدينة (AR)</th>
                                <th class="px-6 py-3 w-48">Shipping Price (EGP)</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($gov->cities as $city)
                                <tr class="hover:bg-gray-50/30 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-700">{{ $city->name_en }}</td>
                                    <td class="px-6 py-4 text-right text-gray-700" dir="rtl">{{ $city->name_ar }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.shipping.update', $city) }}" method="POST" class="flex items-center gap-2">
                                            @csrf @method('PATCH')
                                            <input type="number" name="shipping_price" value="{{ (float)$city->shipping_price }}" step="0.01" class="w-24 border-gray-200 rounded-lg text-xs focus:ring-nidan-gold focus:border-nidan-gold py-1.5 px-2">
                                            <button type="submit" class="p-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors" title="Save">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.shipping.destroy', $city) }}" method="POST" onsubmit="return confirm('Delete this city?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Add City Modal -->
<div id="add-city-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-2xl p-8">
        <h3 class="text-xl font-serif font-bold text-nidan-text mb-6">Add New City</h3>
        <form action="{{ route('admin.shipping.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Governorate *</label>
                <select name="governorate_id" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
                    <option value="">Select Governorate</option>
                    @foreach($governorates as $gov)
                        <option value="{{ $gov->id }}">{{ $gov->name_en }} / {{ $gov->name_ar }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">City Name (EN) *</label>
                    <input type="text" name="name_en" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold text-right">أسم المدينة (AR) *</label>
                    <input type="text" name="name_ar" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3 text-right" dir="rtl">
                </div>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Shipping Price (EGP) *</label>
                <input type="number" name="shipping_price" step="0.01" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t">
                <button type="button" onclick="this.closest('#add-city-modal').classList.add('hidden')" class="px-6 py-3 text-gray-500 font-bold hover:text-gray-700 transition-colors">Cancel</button>
                <button type="submit" class="px-8 py-3 bg-nidan-gold text-white rounded-full font-bold shadow-lg hover:bg-[#b59660] transition-all transform hover:scale-105">Add City</button>
            </div>
        </form>
    </div>
</div>
@endsection
