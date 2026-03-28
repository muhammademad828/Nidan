@php
    $homeUrl = Route::has('home') ? route('home') : url('/');
    $hash = fn (string $id) => $homeUrl . '#' . $id;
    $logoUrl = $siteSettings->logo_url ?? null;
    $brandText = $siteSettings->footer_brand ?? $siteSettings->site_name ?? 'Nidan';
    $ve = !empty($visualEditorEnabled);
    $navLinkClass = "site-nav-link text-stone-600 dark:text-stone-400 font-['Manrope'] uppercase tracking-widest text-xs hover:text-[#745b29] transition-colors duration-300";
    $floralLabel = $siteSettings->nav_label_floral ?? $siteSettings->nav_label_1 ?? 'Floral';
    $servicesLabel = $siteSettings->nav_label_services ?? $siteSettings->nav_label_2 ?? 'Services';
    $aboutLabel = $siteSettings->nav_label_about ?? $siteSettings->nav_label_3 ?? 'About';
    $contactLabel = $siteSettings->nav_label_contact ?? $siteSettings->nav_label_4 ?? 'Contact';
    $productsUrl = Route::has('products.index') ? route('products.index') : '#';
@endphp
<nav id="site-header" class="site-header fixed top-8 left-1/2 -translate-x-1/2 w-[95%] max-w-[1400px] z-50 bg-[#fffcf7]/70 dark:bg-stone-900/70 backdrop-blur-xl shadow-sm dark:shadow-none rounded-full flex justify-between items-center px-8 md:px-12 py-4">
<div class="flex flex-1 items-center justify-start gap-4 min-w-0">
<button type="button" class="md:hidden scale-95 active:scale-90 transition-transform text-[#745b29] -ml-1" data-nav-open aria-expanded="false" aria-controls="site-nav-drawer" aria-label="Open menu">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
</button>
<div class="hidden md:flex items-center gap-8">
@if(Route::has('products.index'))
<a class="{{ $navLinkClass }}" href="{{ $productsUrl }}" data-nav-active="products"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="layout|nav_label_floral" @endif class="inline">{{ $floralLabel }}</span></a>
@else
<a class="{{ $navLinkClass }}" href="#" data-nav-active="products"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="layout|nav_label_floral" @endif class="inline">{{ $floralLabel }}</span></a>
@endif
<a class="{{ $navLinkClass }} js-smooth-home" href="{{ request()->routeIs('home') ? '#services' : $hash('services') }}" data-home-anchor="services" data-nav-active="section:services"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="layout|nav_label_services" @endif class="inline">{{ $servicesLabel }}</span></a>
</div>
</div>
<div class="flex-shrink-0 flex items-center justify-center">
@if($logoUrl)
<div class="ve-img-wrap relative inline-block max-w-[200px]" data-editable-table="SiteSetting" data-editable-image="branding|logo">
<a href="{{ $homeUrl }}" class="block" aria-label="{{ $brandText }}">
<img data-ve-logo-img src="{{ $logoUrl }}" alt="{{ $brandText }}" class="h-9 md:h-11 w-auto max-w-[200px] object-contain"/>
</a>
</div>
@else
<a href="{{ $homeUrl }}" class="block" aria-label="{{ $brandText }}">
<h1 class="text-[#745b29] dark:text-[#c5a367] font-['Newsreader'] italic text-3xl tracking-tighter">{{ $brandText }}</h1>
</a>
@endif
</div>
<div class="flex flex-1 items-center justify-end gap-6 min-w-0">
<div class="hidden md:flex items-center gap-8 mr-4">
<a class="{{ $navLinkClass }} js-smooth-home" href="{{ request()->routeIs('home') ? '#about' : $hash('about') }}" data-home-anchor="about" data-nav-active="section:about"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="layout|nav_label_about" @endif class="inline">{{ $aboutLabel }}</span></a>
<a class="{{ $navLinkClass }} js-smooth-home" href="{{ request()->routeIs('home') ? '#contact' : $hash('contact') }}" data-home-anchor="contact" data-nav-active="section:contact"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="layout|nav_label_contact" @endif class="inline">{{ $contactLabel }}</span></a>
</div>
<div class="flex items-center gap-4 text-[#745b29] shrink-0">
@if(Route::has('products.index'))
<button type="button" class="scale-95 active:scale-90 transition-transform" data-site-search-open aria-expanded="false" aria-controls="site-search-overlay" aria-label="Search products">
<span class="material-symbols-outlined" data-icon="search">search</span>
</button>
@else
<button type="button" class="scale-95 active:scale-90 transition-transform" aria-label="Search unavailable"><span class="material-symbols-outlined" data-icon="search">search</span></button>
@endif
@auth
@php
    $accountHref = auth()->user()->is_admin && Route::has('admin.dashboard')
        ? route('admin.dashboard')
        : (Route::has('profile.index') ? route('profile.index') : $homeUrl);
@endphp
<a href="{{ $accountHref }}" class="scale-95 active:scale-90 transition-transform inline-flex" aria-label="Account"><span class="material-symbols-outlined" data-icon="person">person</span></a>
@else
@if(Route::has('login'))
<a href="{{ route('login') }}" class="scale-95 active:scale-90 transition-transform inline-flex" aria-label="Sign in"><span class="material-symbols-outlined" data-icon="person">person</span></a>
@else
<span class="scale-95 inline-flex opacity-50" aria-hidden="true"><span class="material-symbols-outlined" data-icon="person">person</span></span>
@endif
@endauth
<button type="button" class="scale-95 active:scale-90 transition-transform relative" data-mini-cart-open aria-expanded="false" aria-controls="mini-cart" aria-label="Shopping bag">
<span class="material-symbols-outlined" data-icon="shopping_bag">shopping_bag</span>
<span class="absolute -top-1 -right-1 min-w-3 h-3 px-0.5 bg-primary text-on-primary text-[8px] rounded-full flex items-center justify-center tabular-nums" data-cart-count>{{ (int) ($cartItemCount ?? 0) }}</span>
</button>
</div>
</div>
</nav>

<div id="site-nav-drawer" class="nav-drawer fixed inset-0 z-[60] md:hidden" data-nav-drawer aria-hidden="true">
<div class="absolute inset-0 bg-stone-900/40 dark:bg-stone-950/60" data-nav-drawer-dismiss aria-hidden="true"></div>
<div class="nav-drawer__panel absolute top-0 start-0 h-full w-[min(100%,20rem)] bg-[#fffcf7] dark:bg-stone-900 shadow-xl border-e border-stone-200/80 dark:border-stone-700 flex flex-col pt-24 pb-10 px-8 gap-8" role="dialog" aria-modal="true" aria-label="Site menu">
<button type="button" class="absolute top-6 end-6 scale-95 active:scale-90 transition-transform text-stone-600 dark:text-stone-300" data-nav-close aria-label="Close menu">
<span class="material-symbols-outlined" data-icon="close">close</span>
</button>
<nav class="flex flex-col gap-6" aria-label="Primary">
@if(Route::has('products.index'))
<a class="{{ $navLinkClass }} text-left" href="{{ $productsUrl }}" data-nav-active="products"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="layout|nav_label_floral" @endif class="inline">{{ $floralLabel }}</span></a>
@else
<a class="{{ $navLinkClass }} text-left" href="#" data-nav-active="products"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="layout|nav_label_floral" @endif class="inline">{{ $floralLabel }}</span></a>
@endif
<a class="{{ $navLinkClass }} js-smooth-home text-left" href="{{ request()->routeIs('home') ? '#services' : $hash('services') }}" data-home-anchor="services" data-nav-active="section:services"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="layout|nav_label_services" @endif class="inline">{{ $servicesLabel }}</span></a>
<a class="{{ $navLinkClass }} js-smooth-home text-left" href="{{ request()->routeIs('home') ? '#about' : $hash('about') }}" data-home-anchor="about" data-nav-active="section:about"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="layout|nav_label_about" @endif class="inline">{{ $aboutLabel }}</span></a>
<a class="{{ $navLinkClass }} js-smooth-home text-left" href="{{ request()->routeIs('home') ? '#contact' : $hash('contact') }}" data-home-anchor="contact" data-nav-active="section:contact"><span @if($ve) data-editable-table="SiteSetting" data-editable-key="layout|nav_label_contact" @endif class="inline">{{ $contactLabel }}</span></a>
</nav>
</div>
</div>
