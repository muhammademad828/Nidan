@extends('layouts.admin')

@section('title', 'Product Tags')
@section('page-title', 'Product Tags')

@section('page-content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-1">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Add New Tag</h3>
            <form action="{{ route('admin.tags.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Name (EN)</label>
                    <input type="text" name="name_en" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold" required>
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Name (AR)</label>
                    <input type="text" name="name_ar" dir="rtl" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold" required>
                </div>
                <button type="submit" class="w-full py-3 bg-nidan-gold text-white rounded-lg font-bold hover:bg-[#b59660] transition-colors">
                    Create Tag
                </button>
            </form>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Name (EN)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Name (AR)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Slug</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($tags as $tag)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $tag->name_en }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800" dir="rtl">{{ $tag->name_ar }}</td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $tag->slug }}</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Delete this tag?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
