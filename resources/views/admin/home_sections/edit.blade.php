@extends('layouts.admin')

@section('title', 'Edit Home Section')
@section('page-title', 'Edit Home Section')

@section('page-content')
<div class="max-w-2xl">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.home-sections.update', $homeSection) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Section Title (EN)</label>
                    <input type="text" name="title_en" value="{{ $homeSection->title_en }}" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Section Title (AR)</label>
                    <input type="text" name="title_ar" value="{{ $homeSection->title_ar }}" dir="rtl" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold" required>
                </div>

                @if($homeSection->type === 'collection')
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Linked Product Tag</label>
                    <select name="tag_id" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold" required>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ $homeSection->tag_id == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name_en }} ({{ $tag->name_ar }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ $homeSection->sort_order }}" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold">
                </div>

                <div class="pt-4">
                    <button type="submit" class="px-8 py-4 bg-nidan-gold text-white rounded-full font-bold shadow-lg hover:bg-[#b59660] transition-all transform hover:scale-105 active:scale-95">
                        Update Section
                    </button>
                    <a href="{{ route('admin.home-sections.index') }}" class="ml-4 text-gray-400 hover:text-gray-600 font-bold text-sm transition-colors">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
