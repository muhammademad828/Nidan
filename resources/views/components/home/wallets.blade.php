<section class="py-24 px-8 md:px-24">
<div class="container mx-auto">
<div class="text-center mb-20">
<h3 class="font-headline text-5xl md:text-6xl mb-4"><span data-editable-key="home|section_wallets" class="inline">{{ $siteSettings->section_wallets }}</span></h3>
<div class="w-24 h-[1px] bg-primary/30 mx-auto"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-12">
@forelse($walletProducts as $product)
@include('site.partials.home-product-card', ['product' => $product])
@empty
<p class="md:col-span-3 text-center font-body text-on-surface-variant"><span data-editable-key="home|empty_wallets" class="inline whitespace-pre-line">{{ $siteSettings->empty_wallets }}</span></p>
@endforelse
</div>
</div>
</section>
