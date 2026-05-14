@extends('layouts.app')
@php use App\Services\BilingualService; use App\Services\SeoService; @endphp

@section('seo_title', SeoService::getCategoryTitle($category))
@section('seo_desc', SeoService::getCategoryDescription($category))
@section('seo_image', $category->image ? asset('storage/' . $category->image) : asset('logo.png'))

@section('canonical', $products->url(1))
@section('prev_next')
    @if($products->previousPageUrl())
        <link rel="prev" href="{{ $products->previousPageUrl() }}">
    @endif
    @if($products->nextPageUrl())
        <link rel="next" href="{{ $products->nextPageUrl() }}">
    @endif
@endsection
@section('content')

<section class="py-16 px-6 md:px-12 relative z-10">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-serif text-nidan-text">{{ $category->name }}</h1>
            @if($category->description)
            <p class="text-gray-500 mt-3 max-w-xl mx-auto">{{ $category->description }}</p>
            @endif
        </div>

        <x-filter-bar :maxPrice="$maxPrice" />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($products as $product)
            <article class="group flex flex-col gap-4">
                <div class="relative w-full aspect-[4/5] my-2">
                    <div class="absolute inset-0 border-[1.5px] border-[#B09571] rounded-[24px] -rotate-3 scale-[1.02] transition-transform group-hover:rotate-0 group-hover:scale-105 duration-500 z-0 pointer-events-none"></div>
                    <a href="{{ route('product.show', $product) }}" class="rounded-[16px] overflow-hidden relative z-10 w-full h-full shadow-lg block">
                        <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/400x500?text=Nidan' }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    </a>
                    @if($product->is_customizable)
                    <span class="absolute top-3 left-3 z-20 px-3 py-1 bg-nidan-gold text-white text-[10px] uppercase tracking-widest rounded-full">{{ BilingualService::label('customizable') }}</span>
                    @endif
                    @if($product->is_flower)
                    <span class="absolute top-3 right-3 z-20 px-3 py-1 bg-white/90 text-nidan-text text-[10px] uppercase tracking-widest rounded-full">{{ BilingualService::label('flowers') }}</span>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-serif text-nidan-text">
                        <a href="{{ route('product.show', $product) }}" class="hover:text-nidan-gold transition-colors">{{ $product->name }}</a>
                    </h3>
                    @if($product->sku)
                        <span class="text-[10px] font-mono tracking-widest text-gray-400 mt-1 block uppercase">CODE: {{ $product->sku }}</span>
                    @endif
                    <p class="text-nidan-gold mt-2 font-medium tracking-wide">EGP {{ number_format($product->selling_price) }}</p>
                </div>
            </article>
            @empty
            <p class="col-span-4 text-center text-gray-400 italic py-12">{{ BilingualService::label('no_products') }}</p>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>
</section>

@endsection
