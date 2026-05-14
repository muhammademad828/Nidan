@extends('layouts.admin')
@section('title', 'Create Page')
@section('page-title', 'Create New Page')
@section('breadcrumb', 'Home / Pages / Create')

@section('page-content')

<div class="max-w-5xl">
    <form method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-serif text-lg mb-6 text-nidan-text border-b pb-4">General Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Title (EN) *</label>
                            <input type="text" name="title_en" value="{{ old('title_en') }}" required
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-nidan-gold focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Title (AR) *</label>
                            <input type="text" name="title_ar" value="{{ old('title_ar') }}" required dir="rtl"
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-nidan-gold focus:outline-none">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">URL Slug *</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" required placeholder="e.g., our-story"
                            class="w-full px-4 py-3 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-nidan-gold focus:outline-none">
                    </div>

                    <div class="mb-6">
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Content (EN) *</label>
                        <textarea name="content_en" rows="12" required
                            class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-nidan-gold focus:outline-none">{{ old('content_en') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Content (AR) *</label>
                        <textarea name="content_ar" rows="12" required dir="rtl"
                            class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-nidan-gold focus:outline-none">{{ old('content_ar') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-serif text-lg mb-6 text-nidan-text border-b pb-4">SEO Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Meta Title (EN)</label>
                            <input type="text" name="meta_title_en" value="{{ old('meta_title_en') }}"
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-nidan-gold focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Meta Title (AR)</label>
                            <input type="text" name="meta_title_ar" value="{{ old('meta_title_ar') }}" dir="rtl"
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-nidan-gold focus:outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Meta Description (EN)</label>
                            <textarea name="meta_desc_en" rows="3"
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-nidan-gold focus:outline-none">{{ old('meta_desc_en') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Meta Description (AR)</label>
                            <textarea name="meta_desc_ar" rows="3" dir="rtl"
                                class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-nidan-gold focus:outline-none">{{ old('meta_desc_ar') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-serif text-lg mb-6 text-nidan-text border-b pb-4">Media</h3>
                    <div class="mb-4">
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Featured Image</label>
                        <input type="file" name="image" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-nidan-gold/10 file:text-nidan-gold hover:file:bg-nidan-gold/20">
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-serif text-lg mb-6 text-nidan-text border-b pb-4">Status</h3>
                    <div class="flex items-center justify-between mb-8">
                        <span class="text-xs text-gray-500">Visible on website</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-nidan-gold"></div>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-4 bg-nidan-text text-white rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-nidan-gold transition-all shadow-lg shadow-nidan-text/10">
                        Create Page
                    </button>
                    <a href="{{ route('admin.pages.index') }}" class="block text-center mt-4 text-[10px] uppercase tracking-widest text-gray-400 font-bold hover:text-nidan-text transition-colors">
                        Cancel & Go Back
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
