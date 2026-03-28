@extends('site.layout')

@section('body_class', 'products-storefront-page')

@push('head')
@if(Route::has('cart.add'))
<meta name="cart-add-url" content="{{ route('cart.add') }}">
@else
<meta name="cart-add-url" content="">
@endif
<style>
    .products-storefront-page .pagination { display: flex; flex-wrap: wrap; justify-content: center; gap: 0.5rem 1rem; align-items: center; }
    .products-storefront-page .pagination a, .products-storefront-page .pagination span {
        font-family: Manrope, ui-sans-serif, system-ui, sans-serif;
        font-size: 10px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        padding: 0.5rem 0.75rem;
        border-radius: 9999px;
        color: #64655d;
    }
    .products-storefront-page .pagination a:hover { color: #745b29; }
    .products-storefront-page .pagination span[aria-current="page"] {
        background: rgba(116, 91, 41, 0.12);
        color: #745b29;
    }
</style>
@endpush

@section('content')
@php
    $qBase = array_filter($filters ?? [], fn ($v) => $v !== null && $v !== '');
    $ve = !empty($visualEditorEnabled);
@endphp
<div class="min-h-screen bg-[#f8f5f0] text-on-background pt-28 md:pt-32 pb-24 px-6 md:px-16 relative overflow-hidden">
<div class="pointer-events-none absolute -top-24 -right-24 w-80 h-80 bg-[#eae9de] organic-blob-2 opacity-60"></div>
<div class="pointer-events-none absolute top-1/3 -left-16 w-64 h-64 bg-primary-container/25 organic-blob-3 opacity-70"></div>
<div class="pointer-events-none absolute bottom-20 right-10 w-48 h-48 bg-surface-container-highest organic-blob-1 opacity-50"></div>

<div class="relative z-10 max-w-[1400px] mx-auto">
<header class="text-center mb-14 md:mb-20">
<span class="font-label text-xs uppercase tracking-[0.3em] text-primary mb-4 block"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="home|products_shop_eyebrow" @endif class="inline">{{ $siteSettings->products_shop_eyebrow }}</span></span>
<h1 class="font-headline text-5xl md:text-6xl text-on-surface mb-4"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="home|products_shop_title" @endif class="inline">{{ $siteSettings->products_shop_title }}</span></h1>
<p class="font-body text-on-surface-variant max-w-lg mx-auto mb-8"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="home|products_shop_tagline" @endif class="inline whitespace-pre-line">{{ $siteSettings->products_shop_tagline }}</span></p>
<div class="w-24 h-[1px] bg-primary/30 mx-auto"></div>
</header>

@if(Route::has('products.index'))
<form method="get" action="{{ route('products.index') }}" class="max-w-xl mx-auto mb-10 md:mb-12" role="search">
@foreach($qBase as $k => $v)
@if($k !== 'q')
<input type="hidden" name="{{ $k }}" value="{{ $v }}">
@endif
@endforeach
<label class="sr-only" for="products-page-search">Search products</label>
<div class="flex gap-2 rounded-full border border-outline-variant/40 bg-surface-bright/90 pl-5 pr-2 py-2 shadow-sm focus-within:border-primary/35 focus-within:ring-2 focus-within:ring-primary/15 transition-shadow">
<input id="products-page-search" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search…" class="min-w-0 flex-1 bg-transparent border-0 font-body text-sm text-on-surface placeholder:text-on-surface-variant/60 focus:ring-0"/>
<button type="submit" class="shrink-0 rounded-full bg-primary/90 text-on-primary px-4 py-2 font-label text-[9px] uppercase tracking-widest hover:bg-primary transition-colors">Go</button>
</div>
</form>
@endif

<div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-12 md:mb-16">
<div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 md:gap-3">
@php
    $catUrl = function (?string $slug) use ($qBase) {
        $q = $qBase;
        if ($slug) {
            $q['category'] = $slug;
        } else {
            unset($q['category']);
        }
        unset($q['page']);
        return route('products.index', $q);
    };
@endphp
<a href="{{ $catUrl(null) }}" class="font-label text-[10px] md:text-xs uppercase tracking-widest px-4 py-2 rounded-full border transition-all duration-300 {{ empty($filters['category'] ?? null) ? 'border-primary bg-primary/10 text-primary' : 'border-outline-variant/60 text-stone-600 hover:border-primary/40' }}">All</a>
@foreach($categories as $cat)
<a href="{{ $catUrl($cat->slug) }}" class="font-label text-[10px] md:text-xs uppercase tracking-widest px-4 py-2 rounded-full border transition-all duration-300 {{ ($filters['category'] ?? '') === $cat->slug ? 'border-primary bg-primary/10 text-primary' : 'border-outline-variant/60 text-stone-600 hover:border-primary/40' }}">{{ $cat->name }}</a>
@endforeach
</div>
<form method="get" action="{{ route('products.index') }}" class="flex justify-center lg:justify-end">
@foreach($qBase as $k => $v)
@if($k !== 'sort')
<input type="hidden" name="{{ $k }}" value="{{ $v }}">
@endif
@endforeach
<label class="sr-only" for="product-sort">Sort</label>
<select name="sort" id="product-sort" onchange="this.form.submit()" class="font-label text-[10px] uppercase tracking-widest bg-surface-bright/80 border border-outline-variant/50 rounded-full px-5 py-3 text-on-surface focus:ring-2 focus:ring-primary/30 cursor-pointer">
@php $sort = $filters['sort'] ?? 'featured'; @endphp
<option value="featured" @selected($sort === 'featured')>Featured</option>
<option value="newest" @selected($sort === 'newest')>Newest</option>
<option value="price_asc" @selected($sort === 'price_asc')>Price: Low to High</option>
<option value="price_desc" @selected($sort === 'price_desc')>Price: High to Low</option>
</select>
</form>
</div>

@if($products->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 md:gap-12">
@foreach($products as $product)
@include('site.partials.product-art-card', ['product' => $product])
@endforeach
</div>
<div class="mt-16 md:mt-20">
{{ $products->appends(request()->query())->onEachSide(1)->links() }}
</div>
@else
<p class="text-center font-body text-on-surface-variant py-24"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="home|products_empty_message" @endif class="inline whitespace-pre-line">{{ $siteSettings->products_empty_message ?? 'No products match your filters yet.' }}</span></p>
@endif
</div>
</div>
@endsection
