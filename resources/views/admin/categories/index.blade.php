@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Categories')
@section('breadcrumb', 'Home / Categories')

@section('page-content')

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
        <span class="text-sm text-gray-500">{{ $categories->total() }} categories</span>
        <a href="{{ route('admin.categories.create') }}"
            class="px-4 py-2 bg-nidan-gold text-white text-sm rounded-lg hover:bg-[#b59660] transition-colors">
            <i class="fas fa-plus mr-1"></i> New Category
        </a>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-gray-400 uppercase tracking-widest border-b">
                <th class="p-4 w-16">Image</th>
                <th class="p-4">Name (EN)</th>
                <th class="p-4 text-right">Name (AR)</th>
                <th class="p-4">Slug</th>
                <th class="p-4">Status</th>
                <th class="p-4">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50/50">
                <td class="p-4">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" class="w-10 h-10 rounded-md object-cover">
                    @else
                        <div class="w-10 h-10 rounded-md bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </td>
                <td class="p-4 font-medium">{{ $category->name_en }}</td>
                <td class="p-4 text-right" dir="rtl">{{ $category->name_ar }}</td>
                <td class="p-4 font-mono text-xs text-gray-400">{{ $category->slug }}</td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $category->is_active ? 'Active' : 'Hidden' }}
                    </span>
                </td>
                <td class="p-4">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs px-3 py-1 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete category? This will hide all its products.')">
                            @csrf @method('DELETE')
                            <button class="text-xs px-3 py-1 rounded-lg bg-red-50 text-red-500 hover:bg-red-100">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-8 text-center text-gray-400 italic">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t">{{ $categories->links() }}</div>
</div>

@endsection
