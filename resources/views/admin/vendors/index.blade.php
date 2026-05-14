@extends('layouts.admin')
@section('title', 'Vendors Ecosystem')
@section('page-title', 'Vendors')
@section('breadcrumb', 'Admin / Ecosystem / Vendors')

@section('page-content')

{{-- Stats Overview --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
    <div class="bg-white p-6 rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-nidan-gold/10 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-nidan-gold/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-14 h-14 bg-nidan-text rounded-2xl flex items-center justify-center text-nidan-gold shadow-lg shadow-nidan-text/20">
                <i class="fas fa-handshake text-xl"></i>
            </div>
            <div>
                <div class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold mb-1">Total Partners</div>
                <div class="text-3xl font-serif text-nidan-text">{{ $totalVendors }}</div>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-nidan-gold/10 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-600/20">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <div class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold mb-1">Active Network</div>
                <div class="text-3xl font-serif text-nidan-text">{{ $activeVendors }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-nidan-gold/10 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-14 h-14 bg-nidan-gold rounded-2xl flex items-center justify-center text-nidan-text shadow-lg shadow-nidan-gold/20">
                <i class="fas fa-gem text-xl"></i>
            </div>
            <div>
                <div class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold mb-1">Total Products</div>
                <div class="text-3xl font-serif text-nidan-text">{{ $totalProducts }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Main Container --}}
<div class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
    {{-- Header & Search --}}
    <div class="p-8 border-b border-gray-50 flex flex-col lg:flex-row justify-between items-center gap-6 bg-gradient-to-r from-white to-nidan-bg/20">
        <div class="relative w-full lg:w-[450px] group">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-nidan-gold transition-colors"></i>
            <input type="text" id="vendorSearch" placeholder="Filter by artisan name, city, or specialty..." 
                class="w-full pl-14 pr-6 py-4 bg-nidan-bg/30 border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/20 transition-all placeholder:text-gray-300">
        </div>
        
        <button onclick="toggleModal('create-modal')"
            class="w-full lg:w-auto px-10 py-4 bg-nidan-gold text-nidan-text text-[11px] uppercase tracking-[0.2em] font-bold rounded-2xl hover:bg-nidan-text hover:text-nidan-gold transition-all shadow-xl shadow-nidan-gold/20 flex items-center justify-center gap-3 active:scale-95 transform">
            <i class="fas fa-plus-circle text-lg"></i>
            <span>Onboard New Partner</span>
        </button>
    </div>

    {{-- Luxury Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-nidan-bg/10 text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black border-b border-gray-50">
                    <th class="px-8 py-6">The Artisan Profile</th>
                    <th class="px-8 py-6">Connectivity</th>
                    <th class="px-8 py-6">Origin & Digital</th>
                    <th class="px-8 py-6 text-center">Curated Items</th>
                    <th class="px-8 py-6 text-center">Status</th>
                    <th class="px-8 py-6 text-right">Operations</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50" id="vendorTableBody">
                @forelse($vendors as $vendor)
                <tr class="hover:bg-nidan-bg/10 transition-all duration-500 group border-b border-transparent hover:border-nidan-gold/10">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-5">
                            <div class="relative w-14 h-14 shrink-0">
                                <div class="absolute inset-0 border-2 border-nidan-gold/20 rounded-2xl -rotate-6 group-hover:rotate-0 transition-transform duration-500"></div>
                                <div class="absolute inset-0 w-full h-full rounded-2xl bg-white shadow-sm overflow-hidden border border-gray-100 flex items-center justify-center relative z-10">
                                    @if($vendor->logo)
                                        <img src="{{ asset('storage/' . $vendor->logo) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xl font-serif font-black text-nidan-gold">{{ substr($vendor->name, 0, 1) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="font-serif text-nidan-text font-black text-lg group-hover:text-nidan-gold transition-colors">{{ $vendor->name }}</div>
                                <div class="text-[9px] text-gray-400 uppercase tracking-[0.3em] mt-1 italic">{{ $vendor->description ? Str::limit($vendor->description, 40) : 'Master Artisan Partner' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 text-xs text-gray-600 font-medium">
                                <span class="w-6 h-6 rounded-lg bg-gray-50 flex items-center justify-center text-[10px] text-gray-400"><i class="fas fa-phone"></i></span>
                                {{ $vendor->phone ?? 'Unlisted' }}
                            </div>
                            <div class="flex items-center gap-3 text-[11px] text-gray-400">
                                <span class="w-6 h-6 rounded-lg bg-gray-50 flex items-center justify-center text-[10px] text-gray-300"><i class="fas fa-envelope"></i></span>
                                {{ $vendor->email ?? 'no-reply@nidan.com' }}
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-[10px] text-nidan-gold"></i>
                                <span class="text-xs font-bold text-nidan-text">{{ $vendor->address ?? 'Egypt' }}</span>
                            </div>
                            @if($vendor->website)
                                <a href="{{ $vendor->website }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-nidan-gold/10 text-nidan-gold text-[9px] font-black uppercase tracking-widest hover:bg-nidan-gold hover:text-white transition-all">
                                    <i class="fas fa-link"></i>
                                    Explore Site
                                </a>
                            @endif
                        </div>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <div class="relative inline-block">
                            <span class="relative z-10 px-5 py-2 rounded-2xl bg-white border border-nidan-gold/20 text-nidan-text text-[10px] font-black uppercase tracking-widest shadow-sm group-hover:shadow-nidan-gold/10 transition-shadow">
                                {{ $vendor->products_count }} Creations
                            </span>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-center">
                            <form method="POST" action="{{ route('admin.vendors.update', $vendor) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="is_active" value="{{ $vendor->is_active ? 0 : 1 }}">
                                <button type="submit" class="flex items-center gap-3 px-4 py-2 rounded-2xl transition-all duration-500 {{ $vendor->is_active ? 'bg-green-50 text-green-600 hover:bg-green-600 hover:text-white' : 'bg-gray-50 text-gray-400 hover:bg-amber-600 hover:text-white' }}">
                                    <span class="w-2 h-2 rounded-full {{ $vendor->is_active ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $vendor->is_active ? 'Active' : 'Offline' }}</span>
                                </button>
                            </form>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-end gap-3 translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-500">
                            <button onclick='openEditModal(@json($vendor))'
                                class="w-10 h-10 rounded-2xl bg-white border border-gray-100 text-nidan-gold hover:bg-nidan-gold hover:text-white flex items-center justify-center shadow-sm transition-all hover:scale-110">
                                <i class="fas fa-pen-fancy text-sm"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.vendors.destroy', $vendor) }}" onsubmit="return confirm('Archive this partner?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-2xl bg-white border border-gray-100 text-red-300 hover:bg-red-500 hover:text-white flex items-center justify-center shadow-sm transition-all hover:scale-110">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-32 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-nidan-bg rounded-[2rem] flex items-center justify-center text-nidan-gold mb-6 shadow-inner">
                                <i class="fas fa-leaf text-3xl opacity-30"></i>
                            </div>
                            <p class="text-nidan-text/40 font-serif italic text-xl">The partner circle is currently quiet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($vendors->hasPages())
        <div class="p-8 border-t border-gray-50 bg-gray-50/30">{{ $vendors->links() }}</div>
    @endif
</div>

{{-- Modals with Premium Styling --}}
@push('modals')
<div id="create-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-12">
        <div class="fixed inset-0 bg-nidan-text/80 backdrop-blur-md transition-opacity" onclick="toggleModal('create-modal')"></div>
        <div class="relative bg-nidan-bg rounded-[3rem] shadow-2xl w-full max-w-3xl overflow-hidden transform transition-all border border-nidan-gold/20">
            <div class="bg-nidan-text p-10 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-nidan-gold/10 rounded-full"></div>
                <h3 class="font-serif text-nidan-gold text-3xl mb-2 relative z-10">Onboard Artisan</h3>
                <p class="text-white/40 text-xs uppercase tracking-[0.3em] relative z-10">Expand the Nidan Ecosystem</p>
                <button onclick="toggleModal('create-modal')" class="absolute top-8 right-8 text-white/30 hover:text-nidan-gold transition-colors"><i class="fas fa-times text-2xl"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.vendors.store') }}" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2 flex flex-col items-center gap-4">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-[2rem] bg-white border-2 border-dashed border-nidan-gold/20 flex flex-col items-center justify-center text-nidan-gold/40 group-hover:border-nidan-gold transition-all overflow-hidden shadow-inner">
                                <i class="fas fa-camera text-3xl mb-2"></i>
                                <span class="text-[9px] uppercase font-black tracking-widest">Add Logo</span>
                                <img id="create-preview" class="absolute inset-0 w-full h-full object-cover hidden">
                            </div>
                            <input type="file" name="logo" onchange="previewImage(this, 'create-preview')" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Legal / Brand Name *</label>
                        <input type="text" name="name" required class="w-full px-6 py-4 bg-white border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Primary Phone</label>
                        <input type="text" name="phone" class="w-full px-6 py-4 bg-white border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Business Email</label>
                        <input type="email" name="email" class="w-full px-6 py-4 bg-white border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Digital Presence (URL)</label>
                        <input type="url" name="website" placeholder="https://" class="w-full px-6 py-4 bg-white border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Headquarters Address</label>
                        <input type="text" name="address" class="w-full px-6 py-4 bg-white border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Artisan Story / Bio</label>
                        <textarea name="description" rows="4" class="w-full px-6 py-4 bg-white border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm" placeholder="Tell the story behind this partner..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-6 pt-6">
                    <button type="button" onclick="toggleModal('create-modal')" class="text-[10px] uppercase tracking-widest font-black text-gray-400 hover:text-nidan-text transition-colors">Discard</button>
                    <button type="submit" class="px-12 py-4 bg-nidan-text text-nidan-gold text-[10px] uppercase tracking-[0.2em] font-black rounded-2xl shadow-2xl hover:bg-black transition-all transform active:scale-95">Integrate Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-12">
        <div class="fixed inset-0 bg-nidan-text/80 backdrop-blur-md transition-opacity" onclick="toggleModal('edit-modal')"></div>
        <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-3xl overflow-hidden transform transition-all border border-nidan-gold/10">
            <div class="bg-nidan-gold p-10 relative overflow-hidden">
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full"></div>
                <h3 class="font-serif text-nidan-text text-3xl mb-2 relative z-10">Refine Profile</h3>
                <p class="text-nidan-text/40 text-[10px] uppercase tracking-[0.3em] font-black relative z-10">Crafting Excellence</p>
                <button onclick="toggleModal('edit-modal')" class="absolute top-8 right-8 text-nidan-text/30 hover:text-white transition-colors"><i class="fas fa-times text-2xl"></i></button>
            </div>
            <form id="edit-form" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2 flex flex-col items-center gap-4">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-[2rem] bg-nidan-bg/50 border-2 border-dashed border-nidan-gold/30 flex flex-col items-center justify-center text-nidan-gold transition-all overflow-hidden shadow-inner">
                                <img id="edit-preview" class="absolute inset-0 w-full h-full object-cover">
                                <div id="edit-placeholder" class="relative z-10 flex flex-col items-center bg-white/80 p-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-camera text-xl mb-1"></i>
                                    <span class="text-[8px] uppercase font-black tracking-tighter">Update Logo</span>
                                </div>
                            </div>
                            <input type="file" name="logo" onchange="previewImage(this, 'edit-preview')" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Artisan Name</label>
                        <input type="text" name="name" id="edit-name" required class="w-full px-6 py-4 bg-nidan-bg/30 border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Direct Contact</label>
                        <input type="text" name="phone" id="edit-phone" class="w-full px-6 py-4 bg-nidan-bg/30 border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Business Email</label>
                        <input type="email" name="email" id="edit-email" class="w-full px-6 py-4 bg-nidan-bg/30 border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Website</label>
                        <input type="url" name="website" id="edit-website" class="w-full px-6 py-4 bg-nidan-bg/30 border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Physical Presence</label>
                        <input type="text" name="address" id="edit-address" class="w-full px-6 py-4 bg-nidan-bg/30 border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-black">Artisan Story</label>
                        <textarea name="description" id="edit-description" rows="4" class="w-full px-6 py-4 bg-nidan-bg/30 border-none rounded-2xl text-sm focus:ring-2 focus:ring-nidan-gold/30 shadow-sm"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-6 pt-6">
                    <button type="button" onclick="toggleModal('edit-modal')" class="text-[10px] uppercase tracking-widest font-black text-gray-400 hover:text-nidan-text transition-colors">Discard</button>
                    <button type="submit" class="px-12 py-4 bg-nidan-gold text-nidan-text text-[10px] uppercase tracking-[0.2em] font-black rounded-2xl shadow-2xl hover:bg-nidan-text hover:text-nidan-gold transition-all transform active:scale-95">Synchronize Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
        document.body.style.overflow = modal.classList.contains('hidden') ? 'auto' : 'hidden';
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (document.getElementById('edit-placeholder')) {
                    document.getElementById('edit-placeholder').classList.add('hidden');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openEditModal(vendor) {
        const form = document.getElementById('edit-form');
        form.action = `/admin/vendors/${vendor.id}`;
        
        document.getElementById('edit-name').value = vendor.name;
        document.getElementById('edit-phone').value = vendor.phone || '';
        document.getElementById('edit-email').value = vendor.email || '';
        document.getElementById('edit-address').value = vendor.address || '';
        document.getElementById('edit-website').value = vendor.website || '';
        document.getElementById('edit-description').value = vendor.description || '';
        
        const preview = document.getElementById('edit-preview');
        const placeholder = document.getElementById('edit-placeholder');
        
        if (vendor.logo) {
            preview.src = `/storage/${vendor.logo}`;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
        
        toggleModal('edit-modal');
    }

    // Search Logic
    document.getElementById('vendorSearch').addEventListener('keyup', function() {
        const term = this.value.toLowerCase();
        const rows = document.querySelectorAll('#vendorTableBody tr');
        
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });
</script>
@endpush
