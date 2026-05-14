@extends('layouts.app')
@php use App\Services\BilingualService; @endphp

@section('seo_title', 'تشكيلات الهدايا والورد - نيدان أتيليه | المنوفية ومصر')
@section('seo_desc', 'اكتشف تشكيلاتنا المميزة من الورد الطبيعي، الهدايا، والاكسسوارات. نوفر بوكيهات ورد لكل المناسبات مع خدمة توصيل للمنوفية وشحن لكل مصر.')
@section('content')

<section class="py-24 px-6 md:px-12 relative z-10 bg-nidan-bg">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-[-1]">
        <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-nidan-gold/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[30rem] h-[30rem] bg-[#D4B87E]/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto">
        <header class="text-center mb-20 relative">
            <span class="flex items-center justify-center gap-4 mb-4">
                <span class="h-[1px] w-12 bg-nidan-gold/50"></span>
                <p class="text-nidan-gold text-xs uppercase tracking-[0.4em] font-semibold">{{ BilingualService::label('explore_our_world') }}</p>
                <span class="h-[1px] w-12 bg-nidan-gold/50"></span>
            </span>
            <h2 class="text-5xl md:text-7xl font-serif text-nidan-text leading-tight">{{ BilingualService::label('collections') }}</h2>
        </header>

        @if($tag)
            <div class="mb-16 text-center">
                <span class="inline-block px-6 py-2 border border-nidan-gold/30 rounded-full text-nidan-gold uppercase tracking-[0.3em] text-[10px] font-bold mb-4">
                    Filtered by Label
                </span>
                <h3 class="text-3xl font-serif text-nidan-text">#{{ $tag->name }}</h3>
            </div>

            <x-filter-bar :maxPrice="$maxPrice" />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                @forelse($taggedProducts as $product)
                    <article class="flex flex-col group h-full">
                        <a href="{{ route('product.show', $product) }}" class="relative aspect-[3/4] rounded-2xl overflow-hidden bg-gray-100 mb-6 shadow-sm group-hover:shadow-2xl transition-all duration-500 block">
                            <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://via.placeholder.com/400x500?text=Nidan+Atelier' }}" 
                                 alt="{{ $product->name }}" 
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors duration-500"></div>
                        </a>
                        <div class="flex flex-col flex-1 text-center">
                            <span class="text-[9px] uppercase tracking-[0.2em] text-gray-400 font-semibold mb-2 block">
                                {{ $product->category?->name ?? 'Collection' }}
                            </span>
                            <h4 class="text-lg font-serif text-nidan-text mb-2 group-hover:text-nidan-gold transition-colors">
                                <a href="{{ route('product.show', $product) }}">{{ $product->name }}</a>
                            </h4>
                            <p class="text-xs font-medium text-nidan-text mb-4">
                                EGP {{ number_format($product->selling_price, 2) }}
                            </p>
                        </div>
                    </article>
                @empty
                    <p class="col-span-full text-center text-gray-400 italic py-12">{{ BilingualService::label('no_products') }}</p>
                @endforelse
            </div>
            <div class="mt-16 flex justify-center">{{ $taggedProducts->links() }}</div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12">
                @forelse($categories as $category)
                <a href="{{ route('category.show', ['locale' => app()->getLocale(), 'category' => $category->slug]) }}"
                    class="group block relative overflow-hidden rounded-[2.5rem] aspect-[3/4] shadow-md hover:shadow-2xl transition-all duration-700 bg-gray-900">
                    <img src="{{ $category->image_url ? (str_starts_with($category->image_url, 'http') ? $category->image_url : asset('storage/' . $category->image_url)) : 'https://via.placeholder.com/600x800?text=Nidan' }}"
                        alt="{{ $category->name }}"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-90 group-hover:opacity-100">
                    
                    {{-- Elegant Gradient Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-[#1a1510]/95 via-[#1a1510]/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-500"></div>
                    
                    {{-- Content Container --}}
                    <div class="absolute inset-0 p-8 md:p-10 flex flex-col justify-end">
                        <div class="transform translate-y-8 group-hover:translate-y-0 transition-transform duration-500 ease-out flex flex-col items-start">
                            <span class="text-nidan-gold/90 text-[10px] uppercase tracking-[0.3em] font-bold block mb-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                {{ $category->active_products_count ?? $category->products_count }} {{ BilingualService::label('products_count') }}
                            </span>
                            
                            <h3 class="text-3xl md:text-4xl font-serif text-white mb-5">{{ $category->name }}</h3>
                            
                            <div class="h-[1px] w-12 bg-nidan-gold/70 mb-5 group-hover:w-full transition-all duration-700 ease-in-out"></div>
                            
                            <div class="flex items-center text-white/90 text-[11px] uppercase tracking-[0.2em] font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-200">
                                <span>{{ BilingualService::label('explore_collection') }}</span>
                                <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left mr-3' : 'fa-arrow-right ml-3' }} group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <p class="col-span-full text-center text-gray-400 italic py-20">{{ BilingualService::label('no_categories') }}</p>
                @endforelse
            </div>
        @endif
    </div>
</section>

@endsection
