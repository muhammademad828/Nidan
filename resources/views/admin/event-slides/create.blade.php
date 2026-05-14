@extends('layouts.admin')

@section('page-title', 'Add Event Slide')

@section('page-content')
<div class="max-w-4xl">
    <header class="mb-8">
        <a href="{{ route('admin.event-slides.index') }}" class="text-sm text-gray-400 hover:text-nidan-gold transition-colors flex items-center gap-2 mb-4">
            <i class="fas fa-arrow-left text-[10px]"></i> Back to List
        </a>
        <h1 class="text-3xl font-serif text-nidan-text">Add New Event Slide</h1>
    </header>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.event-slides.store') }}" method="POST" enctype="multipart/form-data">
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
                {{-- Titles --}}
                <div class="space-y-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">English Content</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title (EN)</label>
                        <input type="text" name="title_en" value="{{ old('title_en') }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle (EN)</label>
                        <input type="text" name="subtitle_en" value="{{ old('subtitle_en') }}"
                               class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest text-right">المحتوى العربي</h3>
                    <div dir="rtl">
                        <label class="block text-sm font-medium text-gray-700 mb-2">العنوان (AR)</label>
                        <input type="text" name="title_ar" value="{{ old('title_ar') }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                    </div>
                    <div dir="rtl">
                        <label class="block text-sm font-medium text-gray-700 mb-2">العنوان الفرعي (AR)</label>
                        <input type="text" name="subtitle_ar" value="{{ old('subtitle_ar') }}"
                               class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                    </div>
                </div>
            </div>

            <div class="space-y-6 mb-10">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Media & Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Media Type</label>
                        <select name="media_type" class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Media Source</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Option 1: Remote URL</label>
                            <input type="text" name="media_url" value="{{ old('media_url') }}" placeholder="https://..."
                                   class="w-full rounded-xl border-gray-200 focus:border-nidan-gold focus:ring-nidan-gold text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Option 2: Upload File</label>
                            <input type="file" name="media_file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-nidan-gold/10 file:text-nidan-gold hover:file:bg-nidan-gold/20 transition-all">
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 italic">Note: Remote URL will be ignored if you upload a file.</p>
                </div>

                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative">
                        <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-nidan-gold"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-nidan-text transition-colors">Visible on Homepage</span>
                </label>
            </div>

            <div class="pt-8 border-t border-gray-100">
                <button type="submit" class="w-full md:w-auto bg-nidan-text text-white px-12 py-4 rounded-full font-bold hover:bg-nidan-dark transition-all transform active:scale-95 shadow-xl">
                    Create Event Slide
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
