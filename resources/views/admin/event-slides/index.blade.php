@extends('layouts.admin')

@section('page-title', 'Event Slider Management')

@section('page-content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-serif text-nidan-text">Event Slider</h1>
        <p class="text-gray-500 mt-1">Manage videos and images for the event experience section.</p>
    </div>
    <a href="{{ route('admin.event-slides.create') }}" class="bg-nidan-gold hover:bg-nidan-btn text-white px-6 py-3 rounded-full font-medium transition-all shadow-lg flex items-center gap-2">
        <i class="fas fa-plus text-xs"></i> Add New Slide
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-bottom border-gray-100">
            <tr>
                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Media</th>
                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Titles</th>
                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Order</th>
                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
            </tr>
        </thead>
        <tbody id="sortable-event-slides" class="divide-y divide-gray-50">
            @forelse($slides as $slide)
            <tr class="hover:bg-gray-50/50 transition-colors" data-id="{{ $slide->id }}">
                <td class="px-8 py-6 cursor-move">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-grip-vertical text-gray-300 hover:text-nidan-gold transition-colors"></i>
                        @if($slide->media_type === 'video')
                            <div class="w-24 h-16 rounded-xl bg-gray-900 flex items-center justify-center relative overflow-hidden">
                                <video class="absolute inset-0 w-full h-full object-cover opacity-50" muted>
                                    <source src="{{ $slide->media_url }}" type="video/mp4">
                                </video>
                                <i class="fas fa-play text-white text-xs relative z-10"></i>
                            </div>
                        @else
                            <img src="{{ $slide->media_url }}" alt="" class="w-24 h-16 rounded-xl object-cover shadow-sm">
                        @endif
                    </div>
                </td>
                <td class="px-8 py-6">
                    <div class="font-medium text-nidan-text">{{ $slide->title_en }}</div>
                    <div class="text-xs text-gray-400 font-serif mt-1">{{ $slide->title_ar }}</div>
                </td>
                <td class="px-8 py-6">
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold sort-index">#{{ $loop->iteration }}</span>
                </td>
                <td class="px-8 py-6">
                    @if($slide->is_active)
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-100 text-green-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Active
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Inactive
                    </span>
                    @endif
                </td>
                <td class="px-8 py-6 text-right">
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.event-slides.edit', $slide) }}" class="p-2 text-gray-400 hover:text-nidan-gold transition-colors">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.event-slides.destroy', $slide) }}" method="POST" onsubmit="return confirm('Delete this slide?');">
                            @csrf @method('DELETE')
                            <button class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-8 py-12 text-center text-gray-400 italic">No event slides found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('sortable-event-slides');
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
                    if (sortIndexEl) sortIndexEl.innerText = '#' + (index + 1);
                    order.push(row.dataset.id);
                });

                // Send AJAX request
                fetch("{{ route('admin.event-slides.reorder') }}", {
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
                        console.log('Event slides reordered successfully');
                    }
                })
                .catch(error => console.error('Error reordering event slides:', error));
            }
        });
    });
</script>
@endpush
@endsection
