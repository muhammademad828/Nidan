@extends('layouts.admin')

@section('title', 'Home Page Sections')
@section('page-title', 'Home Page Sections')

@section('page-content')
<div class="mb-8 flex justify-end">
    <a href="{{ route('admin.home-sections.create') }}" class="px-6 py-3 bg-nidan-gold text-white rounded-full font-bold shadow-lg hover:bg-[#b59660] transition-all transform hover:scale-105">
        <i class="fas fa-plus mr-2"></i> New Section
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-16">Order</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Section Title</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Linked Tag</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                <th class="px-6 py-4"></th>
            </tr>
        </thead>
        <tbody id="sortable-sections" class="divide-y divide-gray-50">
            @foreach($sections as $section)
            <tr class="hover:bg-gray-50/50 transition-colors" data-id="{{ $section->id }}">
                <td class="px-6 py-4 cursor-move">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-grip-vertical text-gray-300 hover:text-nidan-gold transition-colors"></i>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-500 font-bold text-xs sort-index">{{ $loop->iteration }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800">{{ $section->title_en }}</div>
                    <div class="text-xs text-gray-400" dir="rtl">{{ $section->title_ar }}</div>
                </td>
                <td class="px-6 py-4">
                    @if($section->type === 'collection' && $section->tag)
                        <span class="px-3 py-1 rounded-full bg-nidan-gold/10 text-nidan-gold text-xs font-bold">
                            #{{ $section->tag->name_en }}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-bold uppercase tracking-widest">
                            {{ str_replace('_', ' ', $section->type) }}
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.home-sections.toggle', $section) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 group">
                            <div class="w-10 h-5 rounded-full relative transition-colors {{ $section->is_active ? 'bg-green-400' : 'bg-gray-200' }}">
                                <div class="absolute top-1 w-3 h-3 rounded-full bg-white transition-all {{ $section->is_active ? 'left-6' : 'left-1' }}"></div>
                            </div>
                            <span class="text-xs font-bold {{ $section->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $section->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.home-sections.edit', $section) }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 transition-colors">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($section->type === 'collection')
                        <form action="{{ route('admin.home-sections.destroy', $section) }}" method="POST" onsubmit="return confirm('Delete this section?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @else
                            <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-50 text-gray-300" title="Static sections cannot be deleted">
                                <i class="fas fa-lock"></i>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('sortable-sections');
        if (!el) return;
        
        Sortable.create(el, {
            handle: '.cursor-move',
            animation: 150,
            ghostClass: 'bg-nidan-gold/5',
            onEnd: function () {
                // Update visible numbers
                const rows = el.querySelectorAll('tr');
                const order = [];
                
                rows.forEach((row, index) => {
                    row.querySelector('.sort-index').innerText = index + 1;
                    order.push(row.dataset.id);
                });

                // Send AJAX request
                fetch("{{ route('admin.home-sections.reorder') }}", {
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
                        // Optional: Show a small toast notification for success
                        console.log('Reordered successfully');
                    }
                })
                .catch(error => console.error('Error reordering:', error));
            }
        });
    });
</script>
@endpush
@endsection
