@extends('layouts.admin')

@section('title', 'Create Home Section')
@section('page-title', 'Create Home Section')

@section('page-content')
<div class="max-w-2xl">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.home-sections.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Section Title (EN)</label>
                    <input type="text" name="title_en" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold" placeholder="e.g. Celebrate Graduation with Nidan" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Section Title (AR)</label>
                    <input type="text" name="title_ar" dir="rtl" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold" placeholder="مثلاً: احتفل بالتخرج مع نيدان" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Linked Product Tag</label>
                    <select name="tag_id" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold" required>
                        <option value="">Select a tag...</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->name_en }} ({{ $tag->name_ar }})</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-gray-400 italic">This section will automatically display products tagged with this label.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold">
                </div>

                <div class="pt-4">
                    <button type="submit" class="px-8 py-4 bg-nidan-gold text-white rounded-full font-bold shadow-lg hover:bg-[#b59660] transition-all transform hover:scale-105 active:scale-95">
                        Create Section
                    </button>
                    <a href="{{ route('admin.home-sections.index') }}" class="ml-4 text-gray-400 hover:text-gray-600 font-bold text-sm transition-colors">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
