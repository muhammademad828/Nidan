@extends('layouts.admin')
@section('title', 'Policies')
@section('page-title', 'Policies')
@section('breadcrumb', 'Home / Policies')

@section('page-content')

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($policies as $policy)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="font-serif text-lg text-nidan-text">{{ $policy->title_en }}</h3>
                <p class="text-xs text-gray-400 uppercase tracking-widest mt-1">{{ $policy->type }}</p>
            </div>
            <a href="{{ route('admin.policies.edit', $policy) }}"
                class="text-xs px-3 py-1 bg-nidan-gold/10 text-nidan-gold rounded hover:bg-nidan-gold/20 transition-colors">
                Edit
            </a>
        </div>
        <p class="text-sm text-gray-500 line-clamp-3">{{ Str::limit(strip_tags($policy->content_en), 150) }}</p>
    </div>
    @empty
    <p class="col-span-2 text-center text-gray-400 italic py-8">No policies found.</p>
    @endforelse
</div>

@endsection
