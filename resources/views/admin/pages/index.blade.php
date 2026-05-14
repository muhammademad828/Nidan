@extends('layouts.admin')
@section('title', 'Manage Pages')
@section('page-title', 'Static Pages')
@section('breadcrumb', 'Home / Pages')

@section('page-content')

<div class="mb-6 flex justify-end">
    <a href="{{ route('admin.pages.create') }}" class="px-6 py-2 bg-nidan-text text-white rounded-lg hover:bg-nidan-gold transition-all shadow-sm flex items-center gap-2">
        <i class="fas fa-plus text-xs"></i>
        <span>Create New Page</span>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($pages as $page)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group">
        @if($page->image)
            <div class="h-40 overflow-hidden">
                <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title_en }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
        @else
            <div class="h-40 bg-gray-50 flex items-center justify-center text-gray-200">
                <i class="fas fa-image text-4xl"></i>
            </div>
        @endif
        
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-serif text-lg text-nidan-text">{{ $page->title_en }}</h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">/{{ $page->slug }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.pages.edit', $page) }}"
                        class="p-2 bg-nidan-gold/10 text-nidan-gold rounded-lg hover:bg-nidan-gold/20 transition-colors">
                        <i class="fas fa-edit"></i>
                    </a>
                    @if($page->slug !== 'our-story')
                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 bg-red-50 text-red-400 rounded-lg hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ Str::limit(strip_tags($page->content_en), 100) }}</p>
            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50">
                <span class="px-3 py-1 rounded-full text-[9px] uppercase font-bold {{ $page->is_active ? 'bg-green-50 text-green-500' : 'bg-gray-100 text-gray-400' }}">
                    {{ $page->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-100">
        <p class="text-gray-400 italic">No pages found yet.</p>
    </div>
    @endforelse
</div>

@endsection
