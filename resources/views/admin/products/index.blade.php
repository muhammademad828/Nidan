@extends('layouts.admin')
@section('title', 'Products')
@section('page-title', 'Products')
@section('breadcrumb', 'Home / Products')

@section('page-content')

@if ($errors->any())
    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 shadow-sm">
        <div class="flex items-center gap-2 mb-2 font-bold">
            <i class="fas fa-exclamation-circle"></i>
            <span>Please fix the following errors:</span>
        </div>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Filters --}}
<form method="GET" class="mb-6 flex flex-wrap gap-3 items-end">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
        class="text-sm border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nidan-gold">
    <select name="category_id" class="text-sm border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nidan-gold">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_en }}</option>
        @endforeach
    </select>
    <select name="is_active" class="text-sm border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-nidan-gold">
        <option value="">All Status</option>
        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-nidan-gold text-white text-sm rounded-lg hover:bg-[#b59660]">Filter</button>
    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-sm text-gray-400 hover:text-gray-600">Reset</a>
    <button type="button" onclick="document.getElementById('create-form').classList.toggle('hidden')"
        class="ml-auto px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors">
        <i class="fas fa-plus mr-1"></i> New Product
    </button>
</form>

{{-- Create Form --}}
<div id="create-form" class="{{ $errors->any() && old('_method') !== 'PATCH' ? '' : 'hidden' }} mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h3 class="font-serif text-lg mb-4">New Product</h3>
    <form method="POST" action="{{ route('admin.products.store') }}" class="space-y-4" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Name (EN) *</label>
                <input type="text" name="name_en" value="{{ old('name_en') }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Name (AR) *</label>
                <input type="text" name="name_ar" value="{{ old('name_ar') }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold" dir="rtl">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Cost Price (EGP) *</label>
                <input type="number" name="cost_price" value="{{ old('cost_price') }}" step="0.01" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Selling Price (EGP) *</label>
                <input type="number" name="selling_price" value="{{ old('selling_price') }}" step="0.01" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Category</label>
                <select name="category_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold">
                    <option value="">— None —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_en }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Vendor</label>
                <select name="vendor_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold">
                    <option value="">— None —</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <label class="block text-[10px] uppercase tracking-widest text-nidan-gold mb-4 font-bold">Main Product Image</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] text-gray-400 mb-1">Upload from Device</label>
                        <input type="file" name="image_file" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-nidan-gold/10 file:text-nidan-gold hover:file:bg-nidan-gold/20">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-400 mb-1">Or Paste URL</label>
                        <input type="text" name="image" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold" placeholder="https://...">
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <label class="block text-[10px] uppercase tracking-widest text-nidan-gold mb-4 font-bold">Gallery Images (Max 4)</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="p-3 bg-white rounded-lg border border-gray-100">
                        <label class="block text-[9px] uppercase tracking-widest text-gray-400 mb-2">Image {{ $i }}</label>
                        <div class="space-y-2">
                            <input type="file" name="gallery_file_{{ $i }}" class="w-full text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200">
                            <input type="text" name="gallery_url_{{ $i }}" class="w-full border rounded-lg px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-nidan-gold" placeholder="URL {{ $i }}">
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Product Code (SKU)</label>
                <input type="text" name="sku" value="{{ old('sku') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold" placeholder="e.g. NID-001">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Stock Quantity</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Product Labels / Tags</label>
                <div class="flex flex-wrap gap-4 p-4 border rounded-lg bg-gray-50">
                    @foreach($tags as $tag)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }} class="rounded text-nidan-gold focus:ring-nidan-gold">
                            <span class="text-sm text-gray-600 group-hover:text-nidan-gold transition-colors">{{ $tag->name_en }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="mt-1 text-[10px] text-gray-400 italic">Select multiple tags to categorize this product in dynamic home sections.</p>
            </div>
        </div>
        <div class="flex gap-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_flower" value="1" {{ old('is_flower') ? 'checked' : '' }} class="text-nidan-gold focus:ring-nidan-gold">
                <span class="text-gray-600">Flower product</span>
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_customizable" value="1" {{ old('is_customizable') ? 'checked' : '' }} class="text-nidan-gold focus:ring-nidan-gold">
                <span class="text-gray-600">Customizable</span>
            </label>
        </div>
        <div>
            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Description (EN)</label>
            <textarea name="description_en" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold">{{ old('description_en') }}</textarea>
        </div>
        <div>
            <label class="block text-xs uppercase tracking-widest text-gray-500 mb-1">Description (AR)</label>
            <textarea name="description_ar" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-nidan-gold" dir="rtl">{{ old('description_ar') }}</textarea>
        </div>
        <div class="flex justify-end gap-4 mt-6 border-t pt-4">
            <button type="button" onclick="document.getElementById('create-form').classList.add('hidden')" class="px-6 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
            <button type="submit" class="px-6 py-2 bg-nidan-gold text-white text-sm rounded-lg hover:bg-[#b59660] transition-colors">Create Product</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-gray-400 uppercase tracking-widest border-b">
                <th class="p-4">Product</th>
                <th class="p-4">Category</th>
                <th class="p-4">Vendor</th>
                <th class="p-4">Cost</th>
                <th class="p-4">Selling</th>
                <th class="p-4">Flags</th>
                <th class="p-4">Tags</th>
                <th class="p-4">Status</th>
                <th class="p-4">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50/50">
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/40x40?text=N' }}"
                            class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                        <div>
                            <p class="font-medium text-gray-800 text-xs">{{ $product->name_en }}</p>
                            <p class="text-gray-400 text-[10px] mb-1" dir="rtl">{{ $product->name_ar }}</p>
                            @if($product->sku)
                                <span class="text-[9px] font-mono text-gray-400 bg-gray-50 px-1 py-0.5 rounded">#{{ $product->sku }}</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="p-4 text-gray-500 text-xs">{{ $product->category?->name_en ?? '—' }}</td>
                <td class="p-4 text-gray-500 text-xs">{{ $product->vendor?->name ?? '—' }}</td>
                <td class="p-4 font-mono text-xs">EGP {{ number_format($product->cost_price, 2) }}</td>
                <td class="p-4 font-mono text-xs text-nidan-gold">EGP {{ number_format($product->selling_price, 2) }}</td>
                <td class="p-4">
                    @if($product->is_flower)
                        <span class="inline-block px-1.5 py-0.5 bg-purple-100 text-purple-600 text-[10px] rounded mb-0.5">Flower</span>
                    @endif
                    @if($product->is_customizable)
                        <span class="inline-block px-1.5 py-0.5 bg-blue-100 text-blue-600 text-[10px] rounded">Custom</span>
                    @endif
                </td>
                <td class="p-4">
                    <div class="flex flex-wrap gap-1 max-w-[150px]">
                        @forelse($product->tags as $tag)
                            <span class="px-1.5 py-0.5 bg-nidan-gold/10 text-nidan-gold text-[9px] rounded font-bold">#{{ $tag->name_en }}</span>
                        @empty
                            <span class="text-gray-300 text-[10px] italic">No tags</span>
                        @endforelse
                    </div>
                    <button type="button" onclick="openTagModal({{ $product->id }}, {{ $product->tags->pluck('id') }})" class="mt-1 text-[10px] text-nidan-gold hover:underline">Edit Tags</button>
                </td>
                <td class="p-4 text-center">
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-2">
                        <button type="button" 
                            onclick='openEditModal({!! json_encode($product->only(["id","name_ar","name_en","description_ar","description_en","cost_price","selling_price","category_id","vendor_id","is_flower","is_customizable","image","stock","sku"])) !!})'
                            class="text-xs px-2 py-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        
                        <form method="POST" action="{{ route('admin.products.update', $product) }}" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_active" value="{{ $product->is_active ? 0 : 1 }}">
                            @foreach(['name_ar','name_en','description_ar','description_en','cost_price','selling_price','category_id','vendor_id','is_flower','is_customizable','image','stock','sku'] as $field)
                                <input type="hidden" name="{{ $field }}" value="{{ $product->$field ?? '' }}">
                            @endforeach
                            <button class="text-xs px-2 py-1 {{ $product->is_active ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} rounded transition-colors">
                                {{ $product->is_active ? 'Hide' : 'Show' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf @method('DELETE')
                            <button class="text-xs px-2 py-1 bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="p-8 text-center text-gray-400 italic">No products yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t">{{ $products->withQueryString()->links() }}</div>
</div>

@endsection

@push('modals')
<div id="tag-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTagModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
        <h3 class="text-xl font-serif mb-6 text-gray-800">Edit Product Labels</h3>
        <form id="tag-modal-form" method="POST" action="">
            @csrf @method('PATCH')
            <div class="flex flex-wrap gap-4 p-4 border rounded-xl bg-gray-50 mb-8">
                @foreach($tags as $tag)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" id="modal-tag-{{ $tag->id }}" class="rounded text-nidan-gold focus:ring-nidan-gold">
                        <span class="text-sm text-gray-600 group-hover:text-nidan-gold transition-colors">{{ $tag->name_en }}</span>
                    </label>
                @endforeach
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeTagModal()" class="px-6 py-2 text-gray-400 font-bold hover:text-gray-600">Cancel</button>
                <button type="submit" class="px-8 py-2 bg-nidan-gold text-white rounded-full font-bold shadow-lg hover:bg-[#b59660] transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div id="edit-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl bg-white rounded-3xl shadow-2xl p-10 max-h-[90vh] overflow-y-auto">
        <h3 class="text-2xl font-serif mb-8 text-gray-800">Edit Product</h3>
        <form id="edit-modal-form" method="POST" action="" class="space-y-6" enctype="multipart/form-data">
            @csrf @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 font-bold">Name (EN) *</label>
                    <input type="text" name="name_en" id="edit-name_en" required class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 font-bold">Name (AR) *</label>
                    <input type="text" name="name_ar" id="edit-name_ar" required class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all text-right" dir="rtl">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 font-bold">Cost Price (EGP) *</label>
                    <input type="number" name="cost_price" id="edit-cost_price" step="0.01" required class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 font-bold">Selling Price (EGP) *</label>
                    <input type="number" name="selling_price" id="edit-selling_price" step="0.01" required class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 font-bold">Category</label>
                    <select name="category_id" id="edit-category_id" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 font-bold">Vendor</label>
                    <select name="vendor_id" id="edit-vendor_id" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                        <option value="">— None —</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                    <label class="block text-[11px] uppercase tracking-[0.2em] text-nidan-gold mb-6 font-bold">Image Management</label>
                    
                    <div class="mb-8">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-3">Main Image</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="file" name="image_file" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-nidan-gold/10 file:text-nidan-gold hover:file:bg-nidan-gold/20">
                            <input type="text" name="image" id="edit-image" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all" placeholder="Or Paste URL">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm">
                            <label class="block text-[9px] uppercase tracking-widest text-gray-400 mb-2 font-bold">Gallery Image {{ $i }}</label>
                            <div class="space-y-3">
                                <input type="file" name="gallery_file_{{ $i }}" class="w-full text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200">
                                <input type="text" name="gallery_url_{{ $i }}" id="edit-gallery_url_{{ $i }}" class="w-full border-gray-100 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-nidan-gold" placeholder="Gallery URL {{ $i }}">
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 font-bold">Product Code (SKU)</label>
                    <input type="text" name="sku" id="edit-sku" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 font-bold">Stock Quantity</label>
                    <input type="number" name="stock" id="edit-stock" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                </div>
            </div>
            
            <div class="flex gap-10 py-4 border-y border-gray-50">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_flower" id="edit-is_flower" value="1" class="rounded text-nidan-gold focus:ring-nidan-gold w-5 h-5 transition-all">
                    <span class="text-sm text-gray-600 group-hover:text-nidan-gold transition-colors font-medium">Flower Product</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_customizable" id="edit-is_customizable" value="1" class="rounded text-nidan-gold focus:ring-nidan-gold w-5 h-5 transition-all">
                    <span class="text-sm text-gray-600 group-hover:text-nidan-gold transition-colors font-medium">Customizable</span>
                </label>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 font-bold">Description (EN)</label>
                    <textarea name="description_en" id="edit-description_en" rows="4" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 font-bold">Description (AR)</label>
                    <textarea name="description_ar" id="edit-description_ar" rows="4" class="w-full border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-nidan-gold focus:ring-nidan-gold transition-all text-right" dir="rtl"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-6">
                <button type="button" onclick="closeEditModal()" class="px-8 py-3 text-gray-400 font-bold hover:text-gray-600 transition-colors">CANCEL</button>
                <button type="submit" class="px-12 py-3 bg-nidan-gold text-white rounded-full font-bold shadow-xl shadow-nidan-gold/20 hover:bg-[#b59660] transition-all transform active:scale-95">
                    SAVE CHANGES
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
function openTagModal(productId, currentTagIds) {
    const modal = document.getElementById('tag-modal');
    const form = document.getElementById('tag-modal-form');
    form.action = `/admin/products/${productId}`;
    
    // Reset all checkboxes
    document.querySelectorAll('#tag-modal input[type="checkbox"]').forEach(cb => cb.checked = false);
    
    // Check current tags
    currentTagIds.forEach(id => {
        const cb = document.getElementById(`modal-tag-${id}`);
        if (cb) cb.checked = true;
    });
    
    modal.classList.remove('hidden');
}

function closeTagModal() {
    document.getElementById('tag-modal').classList.add('hidden');
}

function openEditModal(product) {
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-modal-form');
    form.action = `/admin/products/${product.id}`;

    // Populate fields
    document.getElementById('edit-name_en').value = product.name_en || '';
    document.getElementById('edit-name_ar').value = product.name_ar || '';
    document.getElementById('edit-cost_price').value = product.cost_price || '';
    document.getElementById('edit-selling_price').value = product.selling_price || '';
    document.getElementById('edit-category_id').value = product.category_id || '';
    document.getElementById('edit-vendor_id').value = product.vendor_id || '';
    document.getElementById('edit-image').value = product.image || '';
    document.getElementById('edit-sku').value = product.sku || '';
    document.getElementById('edit-stock').value = product.stock || 0;
    document.getElementById('edit-description_en').value = product.description_en || '';
    document.getElementById('edit-description_ar').value = product.description_ar || '';

    // Gallery URLs
    const gallery = product.images || [];
    for (let i = 1; i <= 4; i++) {
        const input = document.getElementById(`edit-gallery_url_${i}`);
        if (input) input.value = gallery[i-1] || '';
    }

    // Checkboxes
    document.getElementById('edit-is_flower').checked = !!product.is_flower;
    document.getElementById('edit-is_customizable').checked = !!product.is_customizable;

    modal.classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}
</script>
@endpush