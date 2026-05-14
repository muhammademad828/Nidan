@extends('layouts.admin')
@section('title', 'Edit Policy')
@section('page-title', 'Edit Policy')
@section('breadcrumb', 'Home / Policies / Edit')

@section('page-content')

<div class="max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-serif text-xl text-nidan-text">Edit: {{ $policy->title_en }}</h2>
            <a href="{{ route('admin.policies.index') }}" class="text-sm text-nidan-gold hover:underline">← Back</a>
        </div>

        <form method="POST" action="{{ route('admin.policies.update', $policy) }}" class="space-y-6">
            @csrf @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Title (EN) *</label>
                    <input type="text" name="title_en" required value="{{ old('title_en', $policy->title_en) }}"
                        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Title (AR) *</label>
                    <input type="text" name="title_ar" required value="{{ old('title_ar', $policy->title_ar) }}"
                        class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold" dir="rtl">
                </div>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Content (EN)</label>
                <textarea name="content_en" rows="10"
                    class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold">{{ old('content_en', $policy->content_en) }}</textarea>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-gray-500 mb-2">Content (AR)</label>
                <textarea name="content_ar" rows="10"
                    class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-nidan-gold" dir="rtl">{{ old('content_ar', $policy->content_ar) }}</textarea>
            </div>
            <button type="submit"
                class="px-8 py-3 bg-nidan-btn text-white font-medium rounded-full hover:bg-[#b59660] transition-all">
                Save Changes
            </button>
        </form>
    </div>
</div>

@endsection
