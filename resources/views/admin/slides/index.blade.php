@extends('layouts.admin')

@section('page-title', 'Hero Slides')

@section('page-content')
<div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Manage Slides</h2>
            <p class="text-sm text-gray-400">Add or edit homepage hero slides</p>
        </div>
        <a href="{{ route('admin.slides.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-nidan-gold text-white rounded-lg hover:bg-[#b59660] transition-colors text-sm font-medium">
            <i class="fas fa-plus"></i>
            <span>Add New Slide</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Slide</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Title (EN / AR)</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Order</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="sortable-slides" class="divide-y divide-gray-100">
                @forelse($slides as $slide)
                <tr class="hover:bg-gray-50/50 transition-colors" data-id="{{ $slide->id }}">
                    <td class="px-6 py-4 whitespace-nowrap cursor-move">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-grip-vertical text-gray-300 hover:text-nidan-gold transition-colors"></i>
                            <div class="w-20 h-12 rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ str_starts_with($slide->image, 'http') ? $slide->image : asset('storage/' . $slide->image) }}" 
                                     class="w-full h-full object-cover" alt="slide">
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-800">{{ $slide->title_en }}</div>
                        <div class="text-xs text-gray-400" dir="rtl">{{ $slide->title_ar }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($slide->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500 font-bold sort-index">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap text-sm">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.slides.edit', $slide) }}" class="text-nidan-gold hover:text-[#b59660] transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST" onsubmit="return confirm('Delete this slide?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-images text-4xl mb-3 block opacity-20"></i>
                        <span>No slides found. Click the button above to add your first slide.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('sortable-slides');
        if (!el) return;
        
        Sortable.create(el, {
            handle: '.cursor-move',
            animation: 150,
            ghostClass: 'bg-nidan-gold/5',
            onEnd: function () {
                // Update visible numbers
                const rows = el.querySelectorAll('tr[data-id]');
                const order = [];
                
                rows.forEach((row, index) => {
                    const sortIndexEl = row.querySelector('.sort-index');
                    if (sortIndexEl) sortIndexEl.innerText = index + 1;
                    order.push(row.dataset.id);
                });

                // Send AJAX request
                fetch("{{ route('admin.slides.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Slides reordered successfully');
                    }
                })
                .catch(error => console.error('Error reordering slides:', error));
            }
        });
    });
</script>
@endpush
@endsection
