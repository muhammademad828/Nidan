@extends('layouts.app')
@php use App\Services\BilingualService; @endphp

@section('seo_title', $page->meta_title)
@section('seo_desc', $page->meta_desc)
@section('seo_image', $page->image ? asset('storage/' . $page->image) : asset('logo.png'))

@section('content')

<section class="relative pt-32 pb-24 px-6 md:px-12 overflow-hidden bg-nidan-bg">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none z-0">
        <div class="absolute top-[-10%] right-[-10%] w-[40rem] h-[40rem] bg-nidan-gold/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[30rem] h-[30rem] bg-[#D4B87E]/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="max-w-4xl mx-auto relative z-10">
        <header class="text-center mb-16 reveal">
            <span class="flex items-center justify-center gap-4 mb-6">
                <span class="h-[1px] w-12 bg-nidan-gold/50"></span>
                <p class="text-nidan-gold text-[10px] uppercase tracking-[0.4em] font-bold">{{ BilingualService::label('nidan_atelier') }}</p>
                <span class="h-[1px] w-12 bg-nidan-gold/50"></span>
            </span>
            <h1 class="text-5xl md:text-7xl font-serif text-nidan-text leading-tight mb-8">{{ $page->title }}</h1>
        </header>

        @if($page->image)
            <div class="mb-20 rounded-[3rem] overflow-hidden shadow-2xl reveal shadow-nidan-text/5 aspect-[16/9]">
                <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="prose prose-nidan max-w-none reveal">
            <div class="text-nidan-text/80 text-lg leading-relaxed space-y-8 font-sans {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                {!! $page->content !!}
            </div>
        </div>

        <div class="mt-24 pt-12 border-t border-nidan-gold/10 flex flex-col items-center reveal">
            <div class="w-16 h-16 rounded-full bg-nidan-gold/10 flex items-center justify-center text-nidan-gold mb-6">
                <i class="fas fa-leaf"></i>
            </div>
            <p class="text-[10px] uppercase tracking-[0.4em] font-bold text-nidan-gold mb-12">{{ BilingualService::label('crafted_with_love') }}</p>
            
            <a href="{{ route('collections') }}" class="group flex items-center gap-4 px-12 py-5 bg-nidan-text text-white rounded-full text-xs uppercase tracking-[0.2em] font-bold hover:bg-nidan-gold transition-all shadow-xl shadow-nidan-text/10">
                <span>{{ BilingualService::label('explore_collections') }}</span>
                <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left' : 'fa-arrow-right' }} group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

<style>
    .prose-nidan p { margin-bottom: 2rem; }
    .prose-nidan h2 { 
        font-family: 'Playfair Display', 'Amiri', serif;
        font-size: 2rem;
        color: #2D2319;
        margin-top: 4rem;
        margin-bottom: 1.5rem;
    }
</style>

@endsection
