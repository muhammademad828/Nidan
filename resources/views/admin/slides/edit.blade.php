@extends('layouts.admin')

@section('page-title', 'Edit Slide')

@section('page-content')
<div class="max-w-4xl">
    <form action="{{ route('admin.slides.update', $slide) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Slide Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Title (English)</label>
                    <input type="text" name="title_en" value="{{ $slide->title_en }}" required
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Title (Arabic)</label>
                    <input type="text" name="title_ar" value="{{ $slide->title_ar }}" required dir="rtl"
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Subtitle (English)</label>
                    <textarea name="subtitle_en" rows="3"
                              class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">{{ $slide->subtitle_en }}</textarea>
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Subtitle (Arabic)</label>
                    <textarea name="subtitle_ar" rows="3" dir="rtl"
                              class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">{{ $slide->subtitle_ar }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Button Text (English)</label>
                    <input type="text" name="button_text_en" value="{{ $slide->button_text_en }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Button Text (Arabic)</label>
                    <input type="text" name="button_text_ar" value="{{ $slide->button_text_ar }}" dir="rtl"
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">WhatsApp Button Text (English)</label>
                    <input type="text" name="secondary_button_text_en" value="{{ $slide->secondary_button_text_en }}" placeholder="Contact Us"
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">WhatsApp Button Text (Arabic)</label>
                    <input type="text" name="secondary_button_text_ar" value="{{ $slide->secondary_button_text_ar }}" dir="rtl" placeholder="تواصل معنا"
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Primary Button URL</label>
                    <input type="text" name="button_url" value="{{ $slide->button_url }}" list="routes_list" placeholder="/collections"
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">
                </div>

                <datalist id="routes_list">
                    @foreach($availableRoutes as $url => $name)
                        <option value="{{ $url }}">{{ $name }}</option>
                    @endforeach
                </datalist>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ $slide->sort_order }}"
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Media Type</label>
                    <select name="media_type" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all">
                        <option value="image" {{ $slide->media_type === 'image' ? 'selected' : '' }}>Image</option>
                        <option value="video" {{ $slide->media_type === 'video' ? 'selected' : '' }}>Video</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <label class="text-sm font-medium text-gray-700">Current Media</label>
                <div class="w-full h-48 rounded-xl overflow-hidden border border-gray-100 mb-4 bg-gray-900 flex items-center justify-center">
                    @if($slide->media_type === 'video')
                        <video class="w-full h-full object-cover" controls>
                            <source src="{{ $slide->media }}" type="video/mp4">
                        </video>
                    @else
                        <img src="{{ $slide->media }}" class="w-full h-full object-cover" alt="current media">
                    @endif
                </div>

                <label class="text-sm font-medium text-gray-700">Change Media Source</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Option 1: Remote URL (Image or Video)</label>
                        <input type="text" name="media_url" value="{{ $slide->media_url }}" placeholder="https://..."
                               class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-nidan-gold focus:ring-2 focus:ring-nidan-gold/20 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Option 2: Replace File (Max 20MB)</label>
                        <input type="file" name="media_file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-nidan-gold/10 file:text-nidan-gold hover:file:bg-nidan-gold/20 transition-all">
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 italic">Note: If you upload a file, the Remote URL will be ignored.</p>
            </div>

            <div class="mt-6">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $slide->is_active ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-nidan-gold/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-nidan-gold"></div>
                    <span class="ml-3 text-sm font-medium text-gray-700">Active Slide</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.slides.index') }}" 
               class="px-6 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-nidan-gold text-white rounded-lg hover:bg-[#b59660] transition-all shadow-lg shadow-nidan-gold/20">
                Update Slide
            </button>
        </div>
    </form>
</div>
@endsection
