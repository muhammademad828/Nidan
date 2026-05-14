@extends('layouts.app')

@section('meta')
    <title>{{ $policy->title }} — Nidan Atelier</title>
@endsection

@section('content')

<section class="py-16 px-6 md:px-12 relative z-10">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-4xl font-serif text-nidan-text mb-3">{{ $policy->title }}</h1>
        <hr class="border-t border-nidan-gold/20 mb-8">

        <div class="prose max-w-none text-gray-700 leading-relaxed">
            {!! nl2br(e($policy->content)) !!}
        </div>

        <div class="mt-10">
            <a href="{{ url()->previous() }}" class="text-sm text-nidan-gold underline hover:text-[#b59660]">← Back</a>
        </div>
    </div>
</section>

@endsection
