@extends('layouts.app')
@php use App\Services\BilingualService; @endphp
@section('content')

@foreach($homeSections as $section)
    @if($section->type === 'hero')
<!-- Hero Section (Dynamic Slider) -->
<section id="hero" class="relative z-10 px-6 pt-20 pb-[120px] md:px-12 lg:px-24 min-h-[90vh] flex items-center overflow-hidden">

    @php $heroSlides = $slides ?? collect(); @endphp

    {{-- Slide panels: same layout as original, stacked via absolute positioning --}}
    @if($heroSlides->isNotEmpty())
        @foreach($heroSlides as $i => $slide)
        <div class="hero-slide absolute inset-0 px-6 py-20 md:px-12 lg:px-24 flex items-center transition-opacity duration-700 {{ $i === 0 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none' }}">
            <div class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <div class="flex flex-col items-start justify-center pt-8 lg:pt-0 pe-0 lg:pe-12">
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif leading-tight text-nidan-text mb-6">
                        {!! nl2br(e($slide->title)) !!}
                    </h1>
                    <p class="text-lg md:text-xl text-gray-700 font-light max-w-lg mb-10 leading-relaxed">
                        {{ $slide->subtitle }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                        @if($slide->button_text)
                        @php
                            $btnUrl = $slide->button_url ?: '/collections';
                            $finalUrl = str_starts_with($btnUrl, 'http') ? $btnUrl : BilingualService::localeUrl(app()->getLocale(), $btnUrl);
                        @endphp
                        <a href="{{ $finalUrl }}"
                            class="bg-nidan-btn text-white font-medium text-lg px-8 py-4 rounded-full btn-shadow hover:bg-[#b59660] transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-nidan-gold focus:ring-offset-2 focus:ring-offset-nidan-bg inline-block text-center w-full sm:w-auto">
                            {{ $slide->button_text }}
                        </a>
                        @endif
                        
                        @if($slide->secondary_button_text)
                        @php 
                            $waNumber = \App\Models\SiteSetting::getByKey('whatsapp_number', '201061168835');
                            $waMsg = \App\Models\SiteSetting::getByKey('whatsapp_message', 'Hello');
                            $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waMsg);
                        @endphp
                        <a href="{{ $waUrl }}" target="_blank"
                            class="bg-transparent border border-nidan-gold text-nidan-text font-medium text-lg px-8 py-4 rounded-full shadow-sm hover:bg-nidan-gold hover:text-white transition-all duration-500 inline-flex items-center justify-center gap-3 group/wa w-full sm:w-auto">
                            <div class="w-8 h-8 bg-[#25D366] rounded-full flex items-center justify-center group-hover/wa:scale-110 transition-transform shadow-lg shadow-green-500/20 shrink-0">
                                <i class="fab fa-whatsapp text-white text-lg"></i>
                            </div>
                            <span class="tracking-wide">{{ $slide->secondary_button_text }}</span>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="relative w-full h-[400px] md:h-[500px] lg:h-[650px] flex items-center justify-center">
                    <div class="blob-shape w-full h-full max-w-lg mx-auto relative shadow-2xl overflow-hidden">
                        @php
                            $mediaUrl = $slide->media;
                            $safeMediaUrl = $mediaUrl ?: 'https://via.placeholder.com/600x800?text=No+Image';
                            $mediaLower = strtolower($safeMediaUrl);
                            $isVideo = $slide->media_type === 'video' || 
                                       str_contains($mediaLower, '.mp4') || 
                                       str_contains($mediaLower, '.mov') || 
                                       str_contains($mediaLower, '.webm') ||
                                       str_contains($mediaLower, '.ogg');
                        @endphp
                        @if($isVideo)
                            <video class="w-full h-full object-cover" autoplay muted loop playsinline>
                                <source src="{{ $safeMediaUrl }}" type="video/mp4">
                            </video>
                        @else
                            <img alt="{{ $slide->title }}"
                                class="w-full h-full object-cover object-center"
                                src="{{ $safeMediaUrl }}" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Invisible spacer to keep section height --}}
        <div class="invisible max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center" aria-hidden="true">
            <div class="flex flex-col items-start justify-center pt-8 lg:pt-0 pe-0 lg:pe-12">
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-serif leading-tight text-nidan-text mb-6">Placeholder</h1>
                <p class="text-lg md:text-xl text-gray-700 font-light max-w-lg mb-10 leading-relaxed">Placeholder</p>
                <div class="flex flex-wrap gap-4"><span class="px-8 py-4">x</span></div>
            </div>
            <div class="relative w-full h-[400px] md:h-[500px] lg:h-[650px]"></div>
        </div>

        {{-- Dot indicators (only if more than 1 slide) --}}
        @if($heroSlides->count() > 1)
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 z-20">
            @foreach($heroSlides as $i => $slide)
            <button class="hero-dot w-2.5 h-2.5 rounded-full border border-nidan-gold transition-all duration-300 {{ $i === 0 ? 'bg-nidan-gold scale-125' : 'bg-transparent' }}"
                data-slide="{{ $i }}" aria-label="Slide {{ $i + 1 }}"></button>
            @endforeach
        </div>
        @endif

    @else
    {{-- Fallback: original static content --}}
    <div class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
        <div class="flex flex-col items-start justify-center pt-8 lg:pt-0 pr-0 lg:pr-12">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif leading-tight text-nidan-text mb-6">
                Bespoke Blooms &amp; <br class="hidden lg:block" /> Custom Creations
            </h1>
            <p class="text-lg md:text-xl text-gray-700 font-light max-w-lg mb-10 leading-relaxed">
                Design your dream arrangement for a personalized consultation.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <a href="{{ route('collections') }}"
                    class="bg-nidan-btn text-white font-medium text-lg px-8 py-4 rounded-full btn-shadow hover:bg-[#b59660] transition-all transform hover:scale-105 focus:outline-none inline-block text-center w-full sm:w-auto">
                    {{ BilingualService::label('explore_collection') }}
                </a>
                
                @php
                    $waNumber = \App\Models\SiteSetting::getByKey('whatsapp_number', '201061168835');
                    $waMsg = \App\Models\SiteSetting::getByKey('whatsapp_message', 'Hello');
                    $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waMsg);
                @endphp
                
                <a href="{{ $waUrl }}" target="_blank"
                    class="bg-transparent border border-nidan-gold text-nidan-text font-medium text-lg px-8 py-4 rounded-full shadow-sm hover:bg-nidan-gold hover:text-white transition-all duration-500 inline-flex items-center justify-center gap-3 group/wa w-full sm:w-auto">
                    <div class="w-8 h-8 bg-[#25D366] rounded-full flex items-center justify-center group-hover/wa:scale-110 transition-transform shadow-lg shadow-green-500/20 shrink-0">
                        <i class="fab fa-whatsapp text-white text-lg"></i>
                    </div>
                    <span class="tracking-wide">{{ BilingualService::label('whatsapp_chat') }}</span>
                </a>
            </div>
        </div>
        <div class="relative w-full h-[400px] md:h-[500px] lg:h-[650px] flex items-center justify-center">
            <div class="blob-shape w-full h-full max-w-lg mx-auto relative shadow-2xl">
                <img alt="Floral arrangement" class="w-full h-full object-cover object-center"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuARTv4vwoVWcd9MAOeXUhQo0wThUL65lMpw8mKIMD3eVtkt5gFSCkftHXzKBjIYVrMR1dfeKB1pjfaA9FFhls29tXUTiaqzrVoQMarOnavf-ik9sCcJZVT-bYOtBgUFYzIJJfrM9pANzoaSjFrz_fCzbNVgMqD9a4FKtnkHYzjqIHQ84qKZHlSD6qZryU3ti2PHezOVRspD5DzYHIv_cQdwUpYylyomy3msh6n4teb2DeCXiWgOEbNSqUbczJs_KpP53YjuF1p9UPw" />
            </div>
            <div class="absolute -bottom-6 -left-6 bg-white p-4 shadow-xl rounded-2xl hidden md:block">
                <p class="text-nidan-gold font-serif italic text-lg">"Absolutely stunning craftsmanship"</p>
                <p class="text-sm text-gray-400">— Sarah J.</p>
            </div>
        </div>
    </div>
    @endif
</section>

    @elseif($section->type === 'collection' && $section->tag)
<!-- Dynamic Collection Sections -->
<section class="w-full py-[120px] relative z-10 {{ $loop->odd ? 'bg-white/30' : 'bg-[#fdfaf5]/50' }}">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-6">
            <header class="text-center md:text-start">
                <h2 class="text-sm text-nidan-gold uppercase tracking-[0.3em] mb-4 font-bold">{{ $section->tag->name }}</h2>
                <h3 class="text-4xl md:text-6xl text-nidan-text font-serif leading-tight">{{ $section->title }}</h3>
            </header>
            <a href="{{ route('collections', ['tag' => $section->tag->slug]) }}" class="group flex items-center gap-3 text-xs uppercase tracking-widest font-bold text-nidan-gold hover:text-nidan-text transition-colors pb-2">
                {{ BilingualService::label('browse_collection') }}
                <i class="fas fa-arrow-right transition-transform group-hover:translate-x-2 rtl:group-hover:-translate-x-2"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            @forelse($section->tag->products as $product)
            <article class="flex flex-col group h-full relative">
                {{-- Product Image with Hover Effects --}}
                <div class="relative w-full mb-8">
                    {{-- Fixed Aspect Ratio Container (4:5) --}}
                    <div class="relative w-full pb-[125%] z-10">
                        <div class="absolute inset-0 border-[1.5px] border-nidan-gold rounded-[2.5rem] -rotate-3 scale-[1.02] transition-transform duration-500 group-hover:rotate-0 group-hover:scale-105 z-0 pointer-events-none"></div>
                        <a href="{{ route('product.show', $product) }}" class="absolute inset-0 w-full h-full block rounded-[2.5rem] overflow-hidden shadow-sm transition-all duration-700 bg-white group-hover:shadow-2xl">
                            <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/400x500?text=Nidan+Atelier' }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-110">
                            
                            {{-- Overlay info on hover --}}
                            <div class="absolute inset-0 bg-nidan-dark/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                                <span class="text-white text-[10px] uppercase tracking-widest font-bold mb-2 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">{{ $product->category?->name ?? 'Collection' }}</span>
                                <h4 class="text-white text-lg font-serif mb-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-75">{{ $product->name }}</h4>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Product Details --}}
                <div class="flex flex-col flex-1 px-4">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-[10px] uppercase tracking-[0.2em] text-nidan-gold font-bold">{{ $product->category?->name ?? 'Bespoke' }}</span>
                        <div class="h-[1px] flex-1 mx-4 bg-nidan-gold/20"></div>
                        <span class="text-sm font-serif text-nidan-text">EGP {{ number_format($product->selling_price, 0) }}</span>
                    </div>

                    <h4 class="text-xl font-serif text-nidan-text mb-1 group-hover:text-nidan-gold transition-colors duration-300">
                        <a href="{{ route('product.show', $product) }}">{{ $product->name }}</a>
                    </h4>
                    @if($product->sku)
                        <span class="text-[9px] font-mono tracking-widest text-gray-400 mb-3 block uppercase">CODE: {{ $product->sku }}</span>
                    @endif

                    <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed mb-8 italic">
                        {{ $product->description }}
                    </p>

                    <div class="mt-auto pt-4">
                        <a href="{{ route('product.show', $product) }}" class="flex items-center justify-between w-full py-4 px-8 rounded-full border border-nidan-gold text-nidan-gold text-[10px] uppercase tracking-[0.2em] font-bold hover:bg-nidan-gold hover:text-white transition-all group/btn">
                            <span>{{ BilingualService::label('explore_collection') }}</span>
                            <i class="fas fa-chevron-right text-[8px] transform group-hover/btn:translate-x-1 rtl:group-hover/btn:-translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </article>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-gray-300 italic font-serif text-lg">No products discovered in this collection yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
    @elseif($section->type === 'best_sellers')
<!-- Best Sellers -->
<section class="w-full py-[120px] relative z-10 bg-white/30">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <header class="mb-20 text-center lg:text-start">
            <h2 class="text-lg text-nidan-gold uppercase tracking-[0.2em] mb-3 font-medium">{{ BilingualService::label('curated_selection') }}</h2>
            <h3 class="text-5xl md:text-6xl text-nidan-text font-serif">{{ BilingualService::label('best_sellers') }}</h3>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @forelse($bestSellers ?? [] as $product)
            <article class="flex flex-col gap-6 group">
                <div class="relative w-full my-2">
                    {{-- Fixed Aspect Ratio Container (4:5) --}}
                    <div class="relative w-full pb-[125%] z-10">
                        <div class="absolute inset-0 border-[1.5px] border-[#B09571] rounded-[24px] -rotate-3 scale-[1.02] transition-transform group-hover:rotate-0 group-hover:scale-105 duration-500 z-0 pointer-events-none"></div>
                        <a href="{{ route('product.show', $product) }}" class="absolute inset-0 w-full h-full rounded-[16px] overflow-hidden shadow-lg block bg-white">
                            <img alt="{{ $product->name }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/400x500?text=Nidan' }}" />
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-2xl lg:text-3xl text-nidan-text font-serif">
                        <a href="{{ route('product.show', $product) }}">{{ $product->name }}</a>
                    </h4>
                    @if($product->sku)
                        <span class="text-[10px] font-mono tracking-widest text-gray-400 mt-1 block uppercase">CODE: {{ $product->sku }}</span>
                    @endif
                    <p class="text-nidan-gold mt-2 font-medium tracking-wide">
                        {{ app()->getLocale() === 'ar' ? 'يبدأ من' : 'Starting from' }} EGP {{ number_format($product->selling_price) }}
                    </p>
                </div>
            </article>
            @empty
            <p class="col-span-3 text-center text-gray-400 italic">No products found.</p>
            @endforelse
        </div>
    </div>
</section>

    @elseif($section->type === 'testimonials')
<!-- Testimonials -->
<section class="py-24 bg-nidan-bg overflow-hidden">
    <div class="max-w-5xl mx-auto px-6 md:px-12">
        <header class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-serif text-nidan-text">{{ BilingualService::label('kind_words') }}</h2>
        </header>
        <div id="testimonial-slider" class="testimonial-container">
            @forelse($testimonials ?? [] as $i => $t)
            <div class="testimonial-slide {{ $i === 0 ? 'active' : '' }}">
                <div class="flex gap-1 mb-6">
                    @for($star = 1; $star <= 5; $star++)
                        <i class="fas fa-star text-sm {{ $star <= $t['rating'] ? 'text-nidan-gold' : 'text-gray-200' }}"></i>
                    @endfor
                </div>
                <p class="font-serif italic text-nidan-text text-2xl md:text-3xl leading-relaxed mb-8 max-w-3xl text-center">
                    "{{ $t['comment'] }}"
                </p>
                <div class="flex flex-col items-center">
                    <span class="text-xs uppercase tracking-[0.4em] text-nidan-gold font-bold mb-1">{{ $t['name'] }}</span>
                    @if(isset($t['product']))
                        <span class="text-[10px] text-gray-400 uppercase tracking-widest italic">{{ BilingualService::label('on') }} {{ $t['product'] }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="testimonial-slide active">
                <div class="flex gap-1 mb-6">
                    @for($star = 1; $star <= 5; $star++)
                        <i class="fas fa-star text-sm text-nidan-gold"></i>
                    @endfor
                </div>
                <p class="font-serif italic text-nidan-text text-2xl md:text-3xl leading-relaxed mb-8 max-w-3xl text-center">
                    "The attention to detail in the floral arrangements was beyond magical. A true masterpiece."
                </p>
                <span class="text-xs uppercase tracking-[0.4em] text-nidan-gold font-bold">— Layla M.</span>
            </div>
            @endforelse
        </div>
        <div class="flex justify-center gap-4 mt-12">
            @for($i = 0; $i < max(count($testimonials ?? []), 1); $i++)
            <div class="slider-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}"></div>
            @endfor
        </div>
    </div>
</section>

    @elseif($section->type === 'heritage')
<!-- Heritage Banner -->
@php
    $outerBg = \App\Models\SiteSetting::getByKey('heritage_outer_bg');
    $isOuterUrl = str_starts_with($outerBg, 'http') || str_contains($outerBg, '.png') || str_contains($outerBg, '.jpg');
@endphp
<section id="heritage-section" class="py-24 px-6 md:px-12 relative overflow-hidden bg-cover bg-center" 
         style="{{ $isOuterUrl ? "background-image: url('$outerBg')" : "background-color: $outerBg" }}">
    <div id="parallax-motifs"
        class="absolute inset-0 opacity-50 pointer-events-none bg-[url('{{ asset('heritage_pattern_gold.png') }}')] bg-[length:500px_500px] bg-repeat mix-blend-multiply transition-opacity duration-1000 z-0"
        style="mask-image: linear-gradient(to bottom, transparent, black 15%, black 85%, transparent); -webkit-mask-image: linear-gradient(to bottom, transparent, black 15%, black 85%, transparent);">
    </div>
    <div class="max-w-7xl mx-auto relative group">
        <div class="absolute inset-0 border-[2px] border-[#B09571]/50 rounded-[3.5rem] -rotate-3 transition-transform group-hover:rotate-0 group-hover:scale-105 duration-700 z-0 pointer-events-none"></div>
        @php
            $innerBg = \App\Models\SiteSetting::getByKey('heritage_inner_bg');
            $isInnerUrl = str_starts_with($innerBg, 'http') || str_contains($innerBg, '.png') || str_contains($innerBg, '.jpg');
        @endphp
        <div class="relative z-10 w-full min-h-[600px] flex items-center justify-center text-center overflow-hidden rounded-[2.5rem] shadow-2xl transition-all duration-500 group-hover:shadow-[0_20px_50px_rgba(0,0,0,0.3)] bg-cover bg-center"
             style="{{ $isInnerUrl ? "background-image: url('$innerBg')" : "background-color: $innerBg" }}">
            @php
                $innerImg = \App\Models\SiteSetting::getByKey('heritage_inner_image');
                $isInnerImgUrl = str_starts_with($innerImg, 'http');
            @endphp
            <div class="absolute inset-0 bg-cover bg-center transform transition-transform duration-1000 group-hover:scale-105"
                 style="background-image: url('{{ $isInnerImgUrl ? $innerImg : asset($innerImg) }}')"></div>
            <div class="absolute inset-0 bg-black/50 backdrop-blur-[1px]"></div>
            <div class="relative z-20 px-6 py-24 max-w-4xl">
                <span class="text-nidan-gold uppercase tracking-[0.5em] text-xs font-bold mb-8 block">
                    {{ \App\Models\SiteSetting::getByKey('heritage_top_title') }}
                </span>
                <h2 class="text-4xl md:text-7xl font-serif text-white mb-8 leading-tight drop-shadow-sm">
                    {{ \App\Models\SiteSetting::getByKey('heritage_main_title') }}
                </h2>
                <p class="text-white/80 text-lg md:text-xl font-light mb-12 leading-relaxed max-w-2xl mx-auto">
                    {{ \App\Models\SiteSetting::getByKey('heritage_description') }}
                </p>
                <a href="{{ \App\Models\SiteSetting::getByKey('heritage_button_url') }}"
                    class="inline-block bg-[#B09571] text-white px-14 py-5 rounded-full font-medium text-lg shadow-xl hover:bg-[#9a8262] transition-all transform hover:scale-105 active:scale-95">
                    {{ \App\Models\SiteSetting::getByKey('heritage_button_text') }}
                </a>
            </div>
        </div>
    </div>
</section>

    @elseif($section->type === 'events')
<!-- Services -->
<section class="py-24 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        <header class="text-center mb-20">
            <p class="text-lg text-nidan-gold mb-3 tracking-[0.2em] uppercase font-medium">{{ BilingualService::label('our_expertise') }}</p>
            <h2 class="text-5xl md:text-6xl font-serif text-nidan-text">{{ BilingualService::label('event_planning') }}</h2>
        </header>
        {{-- Event Experience Slider --}}
        <div class="relative group/slider mt-4 mb-4">
            <div class="absolute inset-0 border-[2px] border-nidan-gold rounded-[3.5rem] -rotate-3 scale-[1.02] transition-transform duration-700 group-hover/slider:rotate-0 group-hover/slider:scale-105 z-0 pointer-events-none hidden md:block"></div>
            <div id="event-slider-container" class="relative min-h-[500px] md:min-h-[700px] overflow-hidden rounded-[2.5rem] md:rounded-[3.5rem] shadow-2xl z-10 bg-[#f8f5f0]">
                @forelse($eventSlides as $index => $slide)
                    <div class="event-slide absolute inset-0 transition-all duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-105 pointer-events-none' }}" data-index="{{ $index }}">
                        {{-- Media Layer --}}
                        @php
                            $safeEventMedia = $slide->media_url ?: 'https://via.placeholder.com/1920x1080?text=No+Image';
                            $mediaLower = strtolower($safeEventMedia);
                            $isEventVideo = $slide->media_type === 'video' || 
                                            str_contains($mediaLower, '.mp4') || 
                                            str_contains($mediaLower, '.mov') || 
                                            str_contains($mediaLower, '.webm') ||
                                            str_contains($mediaLower, '.ogg');
                        @endphp
                        @if($isEventVideo)
                            <video class="absolute inset-0 w-full h-full object-cover" autoplay muted loop playsinline>
                                <source src="{{ $safeEventMedia }}" type="video/mp4">
                            </video>
                        @else
                            <img src="{{ $safeEventMedia }}" alt="{{ $slide->title }}" class="absolute inset-0 w-full h-full object-cover">
                        @endif

                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-black/40 backdrop-blur-[1px]"></div>

                        {{-- Content --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-8 md:p-20 z-10">
                            <div class="max-w-4xl w-full">
                                <span class="text-nidan-gold uppercase tracking-[0.4em] font-bold text-xs md:text-sm mb-6 block transform translate-y-4 opacity-0 transition-all duration-1000 delay-300 slide-content">
                                    {{ BilingualService::label('event_planning') ?? 'Exquisite Events' }}
                                </span>
                                <h3 class="text-4xl md:text-7xl font-serif text-white mb-6 leading-tight transform translate-y-8 opacity-0 transition-all duration-1000 delay-500 slide-content drop-shadow-lg">
                                    {!! nl2br(e($slide->title)) !!}
                                </h3>
                                
                                @if($slide->subtitle)
                                <p class="text-white/90 text-lg md:text-2xl font-light mb-10 transform translate-y-8 opacity-0 transition-all duration-1000 delay-600 slide-content max-w-2xl mx-auto leading-relaxed">
                                    {{ $slide->subtitle }}
                                </p>
                                @endif
                                
                                @php
                                    $waNumber = \App\Models\SiteSetting::getByKey('whatsapp_number', '201061168835');
                                    $waMsg = \App\Models\SiteSetting::getByKey('whatsapp_message', 'Hello');
                                    $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waMsg);
                                @endphp

                                <div class="flex justify-center transform translate-y-8 opacity-0 transition-all duration-1000 delay-700 slide-content">
                                    <a href="{{ $waUrl }}" target="_blank"
                                        class="bg-nidan-gold text-white font-bold text-sm md:text-base px-10 md:px-14 py-4 md:py-5 rounded-full shadow-2xl hover:bg-white hover:text-nidan-gold transition-all duration-500 inline-flex items-center gap-4 group/wa transform hover:scale-105">
                                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover/wa:bg-nidan-gold/10 transition-colors">
                                            <i class="fab fa-whatsapp text-current text-lg"></i>
                                        </div>
                                        <span class="tracking-widest uppercase">{{ BilingualService::label('contact') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="absolute inset-0 flex items-center justify-center bg-gray-100 italic text-gray-400">
                        No event slides discovered yet.
                    </div>
                @endforelse

                {{-- Slider Controls --}}
                @if($eventSlides->count() > 1)
                    <div class="absolute bottom-12 right-12 flex gap-4 z-30">
                        @foreach($eventSlides as $index => $slide)
                            <button class="event-dot w-12 h-1.5 rounded-full bg-white/20 transition-all duration-500 overflow-hidden relative" data-index="{{ $index }}">
                                <div class="absolute inset-0 bg-nidan-gold origin-left transform scale-x-0 dot-progress"></div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>


    </div>
</section>
    </div>
    <div class="absolute bottom-0 right-0 w-1/3 h-1/2 bg-nidan-gold-light/10 -z-0 rounded-tl-full pointer-events-none"></div>
</section>
    @endif
@endforeach

@endsection

@push('scripts')
<script>
(function () {
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    if (!slides.length) return;

    let current = 0;
    let timer;

    function goTo(n) {
        slides[current].classList.replace('opacity-100', 'opacity-0');
        slides[current].classList.replace('pointer-events-auto', 'pointer-events-none');
        if (dots[current]) {
            dots[current].classList.remove('bg-nidan-gold', 'scale-125');
            dots[current].classList.add('bg-transparent');
        }
        current = (n + slides.length) % slides.length;
        slides[current].classList.replace('opacity-0', 'opacity-100');
        slides[current].classList.replace('pointer-events-none', 'pointer-events-auto');
        if (dots[current]) {
            dots[current].classList.remove('bg-transparent');
            dots[current].classList.add('bg-nidan-gold', 'scale-125');
        }
    }

    function start() { timer = setInterval(() => goTo(current + 1), 5000); }
    function stop()  { clearInterval(timer); }

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => { stop(); goTo(i); start(); });
    });

    if (slides.length > 1) start();
})();

(function() {
    // Testimonial Slider
    const testimonialContainer = document.getElementById('testimonial-slider');
    const testimonialSlides = testimonialContainer?.querySelectorAll('.testimonial-slide');
    const testimonialDots = document.querySelectorAll('.slider-dot');
    let tCurrent = 0;
    let tTimer;

    function goToT(n) {
        if (!testimonialSlides || !testimonialSlides.length) return;
        testimonialSlides[tCurrent].classList.remove('active');
        testimonialDots[tCurrent].classList.remove('active');
        tCurrent = (n + testimonialSlides.length) % testimonialSlides.length;
        testimonialSlides[tCurrent].classList.add('active');
        testimonialDots[tCurrent].classList.add('active');
    }

    function startT() {
        tTimer = setInterval(() => goToT(tCurrent + 1), 6000);
    }

    testimonialDots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            clearInterval(tTimer);
            goToT(i);
            startT();
        });
    });
    if (testimonialSlides?.length) startT();

    // New Event Slider Logic
    const eventSlides = document.querySelectorAll('.event-slide');
    const eventDots = document.querySelectorAll('.event-dot');
    let eventCurrent = 0;
    let eventTimer;

    function showEventSlide(index) {
        eventSlides.forEach((slide, i) => {
            const content = slide.querySelectorAll('.slide-content');
            if (i === index) {
                slide.classList.remove('opacity-0', 'scale-105', 'pointer-events-none');
                slide.classList.add('opacity-100', 'scale-100');
                content.forEach(c => {
                    c.classList.remove('translate-y-8', 'opacity-0');
                    c.classList.add('translate-y-0', 'opacity-100');
                });
            } else {
                slide.classList.add('opacity-0', 'scale-105', 'pointer-events-none');
                slide.classList.remove('opacity-100', 'scale-100');
                content.forEach(c => {
                    c.classList.add('translate-y-8', 'opacity-0');
                    c.classList.remove('translate-y-0', 'opacity-100');
                });
            }
        });

        eventDots.forEach((dot, i) => {
            const prog = dot.querySelector('.dot-progress');
            if (i === index) {
                dot.classList.add('bg-white/40', 'w-16');
                dot.classList.remove('w-12');
                if (prog) {
                    prog.style.transition = 'none';
                    prog.style.transform = 'scaleX(0)';
                    setTimeout(() => {
                        prog.style.transition = 'transform 5000ms linear';
                        prog.style.transform = 'scaleX(1)';
                    }, 50);
                }
            } else {
                dot.classList.remove('bg-white/40', 'w-16');
                dot.classList.add('w-12');
                if (prog) {
                    prog.style.transition = 'none';
                    prog.style.transform = 'scaleX(0)';
                }
            }
        });
    }

    function nextEvent() {
        eventCurrent = (eventCurrent + 1) % eventSlides.length;
        showEventSlide(eventCurrent);
    }

    eventDots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            clearInterval(eventTimer);
            eventCurrent = i;
            showEventSlide(eventCurrent);
            startEventAuto();
        });
    });

    function startEventAuto() {
        if (eventSlides.length > 1) {
            eventTimer = setInterval(nextEvent, 5000);
        }
    }

    if (eventSlides.length > 0) {
        showEventSlide(0);
        startEventAuto();
    }
})();
</script>
@endpush


