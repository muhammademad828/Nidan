@extends('layouts.admin')
@section('title', 'Add-ons')
@section('page-title', 'Add-ons')
@section('breadcrumb', 'Home / Add-ons')

@section('page-content')

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
        <span class="text-sm text-gray-500">{{ $addons->total() }} add-ons</span>
        <button onclick="toggleCreateModal()"
            class="px-4 py-2 bg-nidan-gold text-white text-sm rounded-lg hover:bg-[#b59660] transition-colors">
            <i class="fas fa-plus mr-1"></i> New Add-on
        </button>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-gray-400 uppercase tracking-widest border-b">
                <th class="p-4">Image</th>
                <th class="p-4">Name (EN)</th>
                <th class="p-4">Name (AR)</th>
                <th class="p-4">Selling Price</th>
                <th class="p-4">Cost Price</th>
                <th class="p-4">Profit</th>
                <th class="p-4">Status</th>
                <th class="p-4">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($addons as $addon)
            <tr class="hover:bg-gray-50/50">
                <td class="p-4">
                    @if($addon->image)
                        <img src="{{ asset('storage/' . $addon->image) }}" class="w-10 h-10 object-cover rounded-lg border">
                    @else
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </td>
                <td class="p-4 font-medium">{{ $addon->name_en }}</td>
                <td class="p-4" dir="rtl">{{ $addon->name_ar }}</td>
                <td class="p-4 font-mono text-nidan-gold">EGP {{ number_format($addon->price, 2) }}</td>
                <td class="p-4 font-mono text-gray-400">EGP {{ number_format($addon->cost_price, 2) }}</td>
                <td class="p-4 font-mono text-green-600 font-bold">EGP {{ number_format($addon->price - $addon->cost_price, 2) }}</td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $addon->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $addon->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="p-4">
                    <div class="flex gap-2">
                        <button onclick="openEditModal(this)" 
                            data-addon="{{ json_encode($addon) }}"
                            class="text-xs px-3 py-1 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <form method="POST" action="{{ route('admin.addons.update', $addon) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_active" value="{{ $addon->is_active ? 0 : 1 }}">
                            <button class="text-xs px-3 py-1 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200">
                                {{ $addon->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.addons.destroy', $addon) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-xs px-3 py-1 rounded-lg bg-red-50 text-red-500 hover:bg-red-100">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="p-8 text-center text-gray-400 italic">No add-ons yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t">{{ $addons->links() }}</div>
</div>

<!-- Create Modal -->
<div id="create-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleCreateModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-white rounded-2xl shadow-2xl p-8">
        <h3 class="text-xl font-serif font-bold text-nidan-text mb-6">Create New Add-on</h3>
        <form method="POST" action="{{ route('admin.addons.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Name (EN) *</label>
                    <input type="text" name="name_en" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold text-right">الأسم بالعربية *</label>
                    <input type="text" name="name_ar" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3 text-right" dir="rtl">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Selling Price (EGP) *</label>
                    <input type="number" name="price" step="0.01" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Cost Price (EGP) *</label>
                    <input type="number" name="cost_price" step="0.01" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Description (EN)</label>
                    <textarea name="description_en" rows="3" class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3"></textarea>
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold text-right">الوصف بالعربية</label>
                    <textarea name="description_ar" rows="3" class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3 text-right" dir="rtl"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Add-on Image</label>
                    <input type="file" name="image" class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
                </div>
                <div class="md:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="has_message" id="create_has_message" value="1" class="rounded border-gray-300 text-nidan-gold focus:ring-nidan-gold">
                    <label for="create_has_message" class="text-xs uppercase tracking-widest text-gray-400 font-bold">Requires Customer Message</label>
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Placeholder (EN)</label>
                    <input type="text" name="placeholder_en" class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold text-right">Placeholder (AR)</label>
                    <input type="text" name="placeholder_ar" class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3 text-right" dir="rtl">
                </div>
                <div class="md:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="create_is_active" value="1" checked class="rounded border-gray-300 text-nidan-gold focus:ring-nidan-gold">
                    <label for="create_is_active" class="text-xs uppercase tracking-widest text-gray-400 font-bold">Active Status</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t">
                <button type="button" onclick="toggleCreateModal()" class="px-6 py-3 text-gray-500 font-bold hover:text-gray-700 transition-colors">Cancel</button>
                <button type="submit" class="px-8 py-3 bg-nidan-gold text-white rounded-full font-bold shadow-lg hover:bg-[#b59660] transition-all transform hover:scale-105">Create Add-on</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-white rounded-2xl shadow-2xl p-8">
        <h3 class="text-xl font-serif font-bold text-nidan-text mb-6">Edit Add-on</h3>
        <form id="edit-form" method="POST" action="" class="space-y-6" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Name (EN) *</label>
                    <input type="text" name="name_en" id="edit_name_en" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold text-right">الأسم بالعربية *</label>
                    <input type="text" name="name_ar" id="edit_name_ar" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3 text-right" dir="rtl">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Selling Price (EGP) *</label>
                    <input type="number" name="price" id="edit_price" step="0.01" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Cost Price (EGP) *</label>
                    <input type="number" name="cost_price" id="edit_cost_price" step="0.01" required class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Description (EN)</label>
                    <textarea name="description_en" id="edit_description_en" rows="3" class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3"></textarea>
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold text-right">الوصف بالعربية</label>
                    <textarea name="description_ar" id="edit_description_ar" rows="3" class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3 text-right" dir="rtl"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Change Image (optional)</label>
                    <input type="file" name="image" class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
                </div>
                <div class="md:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="has_message" id="edit_has_message" value="1" class="rounded border-gray-300 text-nidan-gold focus:ring-nidan-gold">
                    <label for="edit_has_message" class="text-xs uppercase tracking-widest text-gray-400 font-bold">Requires Customer Message</label>
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold">Placeholder (EN)</label>
                    <input type="text" name="placeholder_en" id="edit_placeholder_en" class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2 font-bold text-right">Placeholder (AR)</label>
                    <input type="text" name="placeholder_ar" id="edit_placeholder_ar" class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold px-4 py-3 text-right" dir="rtl">
                </div>
                <div class="md:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded border-gray-300 text-nidan-gold focus:ring-nidan-gold">
                    <label for="edit_is_active" class="text-xs uppercase tracking-widest text-gray-400 font-bold">Active Status</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t">
                <button type="button" onclick="closeEditModal()" class="px-6 py-3 text-gray-500 font-bold hover:text-gray-700 transition-colors">Cancel</button>
                <button type="submit" class="px-8 py-3 bg-nidan-gold text-white rounded-full font-bold shadow-lg hover:bg-[#b59660] transition-all transform hover:scale-105">Update Add-on</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleCreateModal() {
        document.getElementById('create-modal').classList.toggle('hidden');
    }

    function openEditModal(btn) {
        const addon = JSON.parse(btn.getAttribute('data-addon'));
        const modal = document.getElementById('edit-modal');
        const form = document.getElementById('edit-form');
        
        // Set values
        document.getElementById('edit_name_en').value = addon.name_en;
        document.getElementById('edit_name_ar').value = addon.name_ar;
        document.getElementById('edit_price').value = addon.price;
        document.getElementById('edit_cost_price').value = addon.cost_price || 0;
        document.getElementById('edit_description_en').value = addon.description_en || '';
        document.getElementById('edit_description_ar').value = addon.description_ar || '';
        document.getElementById('edit_placeholder_en').value = addon.placeholder_en || '';
        document.getElementById('edit_placeholder_ar').value = addon.placeholder_ar || '';
        document.getElementById('edit_has_message').checked = !!addon.has_message;
        document.getElementById('edit_is_active').checked = !!addon.is_active;
        
        // Update form action
        form.action = `/admin/addons/${addon.id}`;
        
        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }
</script>
@endpush

@endsection
