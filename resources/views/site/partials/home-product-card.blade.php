@php
    $card = app(\App\Services\ProductService::class)->normalizeProductCard($product);
    $ve = !empty($visualEditorEnabled ?? false);
    $nameCol = app()->getLocale() === 'ar' ? 'name_ar' : 'name_en';
    $currency = $siteSettings->default_currency ?? 'EGP';
    $img = !empty($card['primary_image']) ? $card['primary_image'] : asset('images/hero-bouquet.jpg');
    $hasCompare = !empty($card['compare_at_price']) && $card['compare_at_price'] > $card['base_price'];
    $shapeIdx = (int) (crc32($card['slug']) % 3) + 1;
    $shapeClass = 'product-blob-shape-' . $shapeIdx;
    $bgShapeIdx = ($shapeIdx % 3) + 1;
    $bgShapeClass = 'product-blob-shape-' . $bgShapeIdx;
    $blobTint = $siteSettings->secondary_color ?? '#fffcf7';
    if (! is_string($blobTint) || ! preg_match('/^#([0-9A-Fa-f]{6}|[0-9A-Fa-f]{3})$/', $blobTint)) {
        $blobTint = '#fffcf7';
    }
@endphp
<div class="group product-gallery-card flex flex-col items-center">
<div class="relative w-full max-w-[320px] mx-auto mb-6">
<div class="product-blob-bg absolute inset-0 -z-10 {{ $bgShapeClass }} blur-3xl" style="background-color: {{ $blobTint }};" aria-hidden="true"></div>
<div class="product-blob-media relative w-full aspect-[3/4] @if($ve) ve-img-wrap @endif" @if($ve) data-editable-table="Product" data-editable-id="{{ $card['id'] }}" data-editable-column="primary_image_path" data-editable-image="Product|{{ $card['id'] }}|primary_image_path" @endif>
<div class="product-card-image-container {{ $shapeClass }} h-full w-full">
@if(Route::has('products.show'))
<a href="{{ route('products.show', $card['slug']) }}" class="block h-full w-full">
<img alt="{{ $card['name'] }}" class="h-full w-full object-cover" src="{{ $img }}" loading="lazy" decoding="async"/>
</a>
@else
<img alt="{{ $card['name'] }}" class="h-full w-full object-cover" src="{{ $img }}" loading="lazy" decoding="async"/>
@endif
</div>
@if(Route::has('cart.add'))
<button type="button" data-cart-add="{{ $card['id'] }}" class="absolute bottom-4 end-4 bg-surface-bright/90 backdrop-blur p-3 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-md border border-outline-variant/30" aria-label="Add to cart">
<span class="material-symbols-outlined" data-icon="add">add</span>
</button>
@else
<button type="button" class="absolute bottom-4 end-4 bg-surface-bright/90 backdrop-blur p-3 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-md border border-outline-variant/30" aria-label="Add to cart">
<span class="material-symbols-outlined" data-icon="add">add</span>
</button>
@endif
</div>
</div>
<div class="text-center w-full">
<p class="font-label text-[10px] uppercase tracking-[0.2em] text-on-surface-variant mb-2">{{ $card['category'] ?? 'Collection' }}</p>
@if(Route::has('products.show'))
<h5 class="font-headline text-2xl mb-2"><a href="{{ route('products.show', $card['slug']) }}" class="hover:text-primary transition-colors"><span @if($ve) data-editable-table="Product" data-editable-id="{{ $card['id'] }}" data-editable-column="{{ $nameCol }}" data-editable-key="product_description|{{ $card['id'] }}|{{ $nameCol }}" @endif class="inline">{{ $card['name'] }}</span></a></h5>
@else
<h5 class="font-headline text-2xl mb-2"><span @if($ve) data-editable-table="Product" data-editable-id="{{ $card['id'] }}" data-editable-column="{{ $nameCol }}" data-editable-key="product_description|{{ $card['id'] }}|{{ $nameCol }}" @endif class="inline">{{ $card['name'] }}</span></h5>
@endif
<p class="font-label text-sm font-bold text-primary">
@if($hasCompare)
                        From {{ $currency }} {{ number_format($card['base_price'], 0) }}
@else
                        {{ $currency }} {{ number_format($card['base_price'], 0) }}
@endif
</p>
</div>
</div>
