@php
    $blob1 = trim((string) ($siteSettings->hero_blob_1 ?? ''));
    $blob2 = trim((string) ($siteSettings->hero_blob_2 ?? ''));
@endphp
<section class="relative min-h-[921px] flex items-center px-8 md:px-24 overflow-hidden">
<div class="container mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
<div class="z-10 max-w-xl">
@if($siteSettings->hero_title_is_custom ?? false)
<h2 class="font-headline text-6xl md:text-8xl text-on-surface leading-[0.9] -tracking-widest mb-8">
<span data-editable-key="home|hero_title" class="block whitespace-pre-line">{{ $siteSettings->hero_title }}</span>
                    </h2>
@else
<h2 class="font-headline text-6xl md:text-8xl text-on-surface leading-[0.9] -tracking-widest mb-8">
<span data-editable-key="home|hero_title_before" class="inline">{{ $siteSettings->hero_title_before }}</span> <span class="italic font-light text-primary inline"><span data-editable-key="home|hero_title_accent" class="inline">{{ $siteSettings->hero_title_accent }}</span></span> <span data-editable-key="home|hero_title_after" class="inline">{{ $siteSettings->hero_title_after }}</span>
                    </h2>
@endif
<p class="font-body text-lg text-on-surface-variant mb-12 max-w-md">
<span data-editable-key="home|hero_subtitle" class="block whitespace-pre-line">{{ $siteSettings->hero_subtitle }}</span>
                    </p>
                    <input type="file" id="bloom-upload" name="photo" accept="image/jpeg,image/png,image/webp,image/gif" class="sr-only" tabindex="-1">
                    <button type="button" id="bloom-upload-trigger" class="bg-gradient-to-r from-primary to-primary-container text-on-primary px-10 py-5 rounded-full font-label text-xs uppercase tracking-widest hover:shadow-lg transition-all duration-300 scale-95 active:scale-90">
                        <span data-editable-key="home|hero_cta_upload" class="inline">{{ $siteSettings->hero_cta_upload }}</span>
                    </button>
                    <p id="bloom-upload-error" class="mt-3 hidden text-sm text-error" role="alert"></p>
                    <p id="bloom-upload-success" class="mt-3 hidden text-sm text-primary" role="status"></p>
                    <div id="bloom-preview" class="mt-6 hidden max-w-md overflow-hidden rounded-2xl border border-outline-variant/50 shadow-lg">
                        <img src="" alt="" class="max-h-80 w-full object-cover">
                    </div>
</div>
<div class="relative group">
<div
    class="ve-img-wrap ve-blob absolute -top-20 -right-20 w-96 h-96 organic-blob-2 -z-10 opacity-50 @if($blob1 === '') bg-surface-container-highest @endif"
    data-editable-table="SiteSetting"
    data-editable-image="home|hero_blob_1"
    @if($blob1 !== '') style="background-image: url('{{ $blob1 }}'); background-size: cover; background-position: center;" @endif
></div>
<div
    class="ve-img-wrap ve-blob absolute -bottom-10 -left-10 w-64 h-64 organic-blob-3 -z-10 @if($blob2 === '') bg-primary-container/20 @endif"
    data-editable-table="SiteSetting"
    data-editable-image="home|hero_blob_2"
    @if($blob2 !== '') style="background-image: url('{{ $blob2 }}'); background-size: cover; background-position: center;" @endif
></div>
<div class="ve-img-wrap organic-blob-1 overflow-hidden w-full aspect-[4/5] shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-700" data-editable-table="SiteSetting" data-editable-image="home|hero_image">
<img data-ve-hero-img alt="{{ $siteSettings->hero_image_alt }}" class="w-full h-full object-cover" src="{{ $siteSettings->hero_image }}"/>
</div>
@if(!empty($visualEditorEnabled))
<div class="mt-3 max-w-md">
<span class="font-label text-[10px] uppercase tracking-widest text-primary">Hero image alt</span>
<p class="mt-1 text-sm text-on-surface-variant"><span data-editable-key="home|hero_image_alt" class="block">{{ $siteSettings->hero_image_alt }}</span></p>
</div>
@endif
</div>
</div>
</section>
