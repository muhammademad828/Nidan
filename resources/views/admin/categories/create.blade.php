@extends('layouts.admin')

@section('page-title', 'Add New Category')

@section('page-content')
<div class="max-w-4xl">
    <header class="mb-8">
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-400 hover:text-nidan-gold transition-colors flex items-center gap-2 mb-4">
            <i class="fas fa-arrow-left text-[10px]"></i> Back to List
        </a>
        <h1 class="text-3xl font-serif text-nidan-text">Add New Category</h1>
    </header>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="space-y-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">English Content</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name (EN) <span class="text-red-500">*</span></label>
                        <input type="text" name="name_en" value="{{ old('name_en') }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description (EN)</label>
                        <textarea name="description_en" rows="3"
                                  class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">{{ old('description_en') }}</textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest text-right">المحتوى العربي</h3>
                    <div dir="rtl">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الاسم (AR) <span class="text-red-500">*</span></label>
                        <input type="text" name="name_ar" value="{{ old('name_ar') }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                    </div>
                    <div dir="rtl">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الوصف (AR)</label>
                        <textarea name="description_ar" rows="3"
                                  class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">{{ old('description_ar') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-6 mb-10 border-t border-gray-100 pt-8">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Settings & Media</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug (Optional)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Auto-generated if left empty"
                               class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                               class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <input type="file" name="image" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-nidan-gold/10 file:text-nidan-gold hover:file:bg-nidan-gold/20 transition-all">
                        <p class="mt-2 text-xs text-gray-400">Recommended: JPG, PNG, WEBP. Max size: 5MB.</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-nidan-gold"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-nidan-text transition-colors">Category is Active</span>
                    </label>
                    <p class="text-xs text-gray-400 mt-1 ml-14">If unchecked, the category and all its products will be hidden from customers.</p>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-nidan-gold text-white px-8 py-3 rounded-xl font-medium hover:bg-[#b59660] transition-colors shadow-sm">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
