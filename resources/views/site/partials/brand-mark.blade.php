{{--
  Site logo image when branding.logo is set; otherwise plain brand text.
  Expects $siteSettings (Fluent). Optional: $inline (bool), $imgClass, $textClass, $homeUrl, $dataVeLogo (bool)
--}}
@php
    $__home = $homeUrl ?? (Route::has('home') ? route('home') : url('/'));
    $__logo = $siteSettings->logo_url ?? null;
    $__label = $brandLabel ?? ($siteSettings->footer_brand ?? $siteSettings->site_name ?? 'Nidan');
    $__img = $imgClass ?? 'h-8 w-auto max-w-[200px] object-contain';
    $__txt = $textClass ?? '';
    $__inline = !empty($inline);
@endphp
@if($__logo)
    @if($__inline)
        <img src="{{ $__logo }}" alt="{{ $__label }}" class="{{ $__img }} inline-block h-[1.15em] w-auto max-w-[6rem] align-[-0.2em] object-contain mx-0.5" loading="lazy" decoding="async" />
    @else
        <a href="{{ $__home }}" class="inline-block" aria-label="{{ $__label }}">
            <img src="{{ $__logo }}" alt="{{ $__label }}" class="{{ $__img }}" @if(!empty($dataVeLogo)) data-ve-logo-img @endif loading="lazy" decoding="async" />
        </a>
    @endif
@else
    <span class="{{ $__txt }}">{{ $__label }}</span>
@endif
