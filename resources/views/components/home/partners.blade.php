<section id="partners" class="py-24 px-8 md:px-24">
<div class="container mx-auto">
<div class="text-center mb-20">
<h3 class="font-headline text-5xl md:text-6xl mb-4"><span data-editable-key="home|section_partners" class="inline">{{ $siteSettings->section_partners }}</span></h3>
<div class="w-24 h-[1px] bg-primary/30 mx-auto"></div>
</div>
<div data-marquee>
<div class="marquee__viewport">
<div class="marquee__row">
<div class="flex shrink-0 items-center gap-16 md:gap-24 px-4">
@if($partners->isEmpty())
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Maison Al Wasl</span>
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Atelier Noor</span>
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Harbor House Events</span>
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Desert Bloom Co.</span>
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Gulf Linens</span>
@if($siteSettings->logo_url ?? null)
<span class="inline-flex shrink-0 items-center">@include('site.partials.brand-mark', ['inline' => true, 'imgClass' => 'h-8 md:h-10 w-auto max-w-[140px] object-contain'])</span>
@else
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Nidan Atelier</span>
@endif
@else
@foreach($partners as $partner)
@if($partner->logo_url)
@if($partner->url)
<a href="{{ $partner->url }}" class="inline-flex shrink-0 items-center opacity-80 hover:opacity-100 transition-opacity" rel="noopener noreferrer" target="_blank"><img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="h-8 md:h-10 w-auto max-w-[140px] object-contain"/></a>
@else
<span class="inline-flex shrink-0 items-center"><img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="h-8 md:h-10 w-auto max-w-[140px] object-contain"/></span>
@endif
@else
@if($partner->url)
<a href="{{ $partner->url }}" class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant hover:text-primary transition-colors" rel="noopener noreferrer" target="_blank">{{ $partner->name }}</a>
@else
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">{{ $partner->name }}</span>
@endif
@endif
@endforeach
@endif
</div>
<div class="flex shrink-0 items-center gap-16 md:gap-24 px-4" aria-hidden="true">
@if($partners->isEmpty())
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Maison Al Wasl</span>
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Atelier Noor</span>
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Harbor House Events</span>
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Desert Bloom Co.</span>
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Gulf Linens</span>
@if($siteSettings->logo_url ?? null)
<span class="inline-flex shrink-0 items-center opacity-80">@include('site.partials.brand-mark', ['inline' => true, 'imgClass' => 'h-8 md:h-10 w-auto max-w-[140px] object-contain'])</span>
@else
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">Nidan Atelier</span>
@endif
@else
@foreach($partners as $partner)
@if($partner->logo_url)
@if($partner->url)
<span class="inline-flex shrink-0 items-center opacity-80"><img src="{{ $partner->logo_url }}" alt="" class="h-8 md:h-10 w-auto max-w-[140px] object-contain"/></span>
@else
<span class="inline-flex shrink-0 items-center"><img src="{{ $partner->logo_url }}" alt="" class="h-8 md:h-10 w-auto max-w-[140px] object-contain"/></span>
@endif
@else
<span class="whitespace-nowrap font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant">{{ $partner->name }}</span>
@endif
@endforeach
@endif
</div>
</div>
</div>
</div>
</div>
</section>
