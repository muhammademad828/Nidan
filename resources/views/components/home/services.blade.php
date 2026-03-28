<section id="services" class="py-24 px-8 md:px-24 bg-surface-container-low">
<div class="container mx-auto">
<div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
<div>
<span class="font-label text-xs uppercase tracking-[0.3em] text-primary mb-4 block"><span data-editable-key="home|services_eyebrow" class="inline">{{ $siteSettings->services_eyebrow }}</span></span>
<h3 class="font-headline text-5xl md:text-6xl text-on-surface"><span data-editable-key="home|services_title" class="inline">{{ $siteSettings->services_title }}</span></h3>
</div>
<p class="font-body text-on-surface-variant max-w-sm mb-2">
                        <span data-editable-key="home|services_intro" class="block whitespace-pre-line">{{ $siteSettings->services_intro }}</span>
                    </p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-4 relative">
@if($serviceOfferings->isEmpty())
<div class="group relative pt-12">
<div class="js-reveal bg-surface-container-highest organic-blob-2 p-12 aspect-square flex flex-col justify-center items-center text-center transition-all duration-500 group-hover:bg-primary-container/40">
<span class="material-symbols-outlined text-4xl mb-6 text-primary" data-icon="auto_awesome">auto_awesome</span>
<h4 class="font-headline text-3xl mb-4">Kath Ktab Napkins</h4>
<p class="font-body text-sm text-on-surface-variant px-4">Custom embroidered artisanal linens for your sacred moments.</p>
<a class="mt-8 font-label text-[10px] uppercase tracking-widest border-b border-outline-variant pb-1" href="{{ Route::has('products.index') ? route('products.index') : '#' }}">Enquire</a>
</div>
</div>
<div class="group relative md:-mt-12">
<div class="js-reveal js-reveal--delay-1 bg-surface-container-highest organic-blob-1 p-12 aspect-square flex flex-col justify-center items-center text-center transition-all duration-500 group-hover:bg-primary-container/40 border-8 border-surface-container-low">
<span class="material-symbols-outlined text-4xl mb-6 text-primary" data-icon="celebration">celebration</span>
<h4 class="font-headline text-3xl mb-4">Kosha</h4>
<p class="font-body text-sm text-on-surface-variant px-4">Bespoke stage designs that capture the essence of your union.</p>
<a class="mt-8 font-label text-[10px] uppercase tracking-widest border-b border-outline-variant pb-1" href="{{ Route::has('products.index') ? route('products.index') : '#' }}">Discover</a>
</div>
</div>
<div class="group relative pt-12 md:pt-24">
<div class="js-reveal js-reveal--delay-2 bg-surface-container-highest organic-blob-3 p-12 aspect-square flex flex-col justify-center items-center text-center transition-all duration-500 group-hover:bg-primary-container/40">
<span class="material-symbols-outlined text-4xl mb-6 text-primary" data-icon="local_florist">local_florist</span>
<h4 class="font-headline text-3xl mb-4">Engagement Flowers</h4>
<p class="font-body text-sm text-on-surface-variant px-4">Curated floral arrangements designed for new beginnings.</p>
<a class="mt-8 font-label text-[10px] uppercase tracking-widest border-b border-outline-variant pb-1" href="{{ Route::has('products.index') ? route('products.index', ['category' => 'flowers']) : '#' }}">View Collection</a>
</div>
</div>
@else
@foreach($serviceOfferings as $i => $offering)
@php
    $slot = $i % 3;
    $blobClass = match ($slot) {
        0 => 'organic-blob-2',
        1 => 'organic-blob-1',
        default => 'organic-blob-3',
    };
    $wrapClass = match ($slot) {
        0 => 'group relative pt-12',
        1 => 'group relative md:-mt-12',
        default => 'group relative pt-12 md:pt-24',
    };
    $innerBorder = $slot === 1 ? ' border-8 border-surface-container-low' : '';
    $delayClass = match ($slot) {
        1 => ' js-reveal--delay-1',
        2 => ' js-reveal--delay-2',
        default => '',
    };
    $icon = $offering->icon_material ?: 'auto_awesome';
    $cta = $offering->button_url;
    $ctaHref = $cta ? (str_starts_with($cta, 'http') ? $cta : url($cta)) : '#';
@endphp
<div class="{{ $wrapClass }}">
<div class="js-reveal{{ $delayClass }} bg-surface-container-highest {{ $blobClass }} p-12 aspect-square flex flex-col justify-center items-center text-center transition-all duration-500 group-hover:bg-primary-container/40{{ $innerBorder }}">
<span class="material-symbols-outlined text-4xl mb-6 text-primary" data-icon="{{ $icon }}">{{ $icon }}</span>
<h4 class="font-headline text-3xl mb-4">{{ $offering->title }}</h4>
<p class="font-body text-sm text-on-surface-variant px-4">{{ $offering->description }}</p>
<a class="mt-8 font-label text-[10px] uppercase tracking-widest border-b border-outline-variant pb-1" href="{{ $ctaHref }}">{{ $offering->button_text ?? 'Learn more' }}</a>
</div>
</div>
@endforeach
@endif
</div>
</div>
</section>
