<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-route-name" content="{{ request()->route()?->getName() ?? '' }}">
    <meta name="app-locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="site-default-currency" content="{{ $siteSettings->default_currency ?? 'EGP' }}">
    @php
        $__homeUrl = Route::has('home') ? route('home') : url('/');
        $__acct = '';
        if (auth()->check()) {
            $__acct = auth()->user()->is_admin && Route::has('admin.dashboard')
                ? route('admin.dashboard')
                : (Route::has('profile.index') ? route('profile.index') : $__homeUrl);
        }
    @endphp
    <meta name="home-url" content="{{ $__homeUrl }}">
    @if(Route::has('products.index'))
    <meta name="products-index-url" content="{{ route('products.index') }}">
    @endif
    @if(Route::has('products.show'))
    <meta name="product-show-url-template" content="{{ route('products.show', ['slug' => '__SLUG__']) }}">
    @endif
    @if(Route::has('cart.get'))
    <meta name="cart-get-url" content="{{ route('cart.get') }}">
    @endif
    @if(Route::has('cart.remove'))
    <meta name="cart-remove-url-template" content="{{ route('cart.remove', ['item' => '__ITEM__']) }}">
    @endif
    @if(Route::has('checkout.index'))
    <meta name="checkout-url" content="{{ route('checkout.index') }}">
    @endif
    @if(Route::has('login'))
    <meta name="customer-login-url" content="{{ route('login') }}">
    @endif
    <meta name="store-account-url" content="{{ $__acct }}">
    @if(!empty($visualEditorEnabled) && Route::has('api.frontend.update'))
        <meta name="visual-editor" content="1">
        <meta name="visual-editor-url" content="{{ route('api.frontend.update') }}">
    @endif
    <title>{{ $seo['meta_title'] ?? $siteSettings->site_name ?? 'Nidan' }}</title>
    @if(!empty($seo['meta_description']))
        <meta name="description" content="{{ $seo['meta_description'] }}">
    @endif
    @php
        $fav = $siteSettings->favicon_url ?? null;
    @endphp
    <link rel="icon" type="image/png" href="{{ $fav ?: asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
@php
    $cream = $siteSettings->secondary_color ?? '#fffcf7';
    $gold = $siteSettings->primary_color ?? '#745b29';
    $tailwindColors = array_merge([
        'on-secondary' => '#ffffff',
        'on-secondary-fixed-variant' => '#635b49',
        'secondary-dim' => '#605746',
        'surface-bright' => '#fffcf7',
        'error-container' => '#fe8b70',
        'surface-variant' => '#eae9de',
        'primary-container' => '#ffdea5',
        'on-secondary-fixed' => '#463f2f',
        'surface' => $cream,
        'secondary-fixed' => '#ede1cb',
        'primary-fixed-dim' => '#f2d093',
        'surface-tint' => $gold,
        'primary-fixed' => '#ffdea5',
        'secondary-container' => '#ede1cb',
        'on-error-container' => '#742410',
        'error-dim' => '#5c1202',
        'tertiary-container' => '#fdddb9',
        'on-primary-fixed' => '#513c0d',
        'on-tertiary-fixed' => '#503c22',
        'on-error' => '#ffffff',
        'on-surface' => '#373831',
        'tertiary' => '#786044',
        'outline-variant' => '#babab0',
        'surface-container-low' => '#fcf9f3',
        'background' => $cream,
        'on-background' => '#373831',
        'on-tertiary-container' => '#644e33',
        'secondary-fixed-dim' => '#dfd3bd',
        'inverse-primary' => '#f8d598',
        'tertiary-fixed' => '#fdddb9',
        'inverse-surface' => '#0e0e0c',
        'on-primary' => '#fff6ed',
        'error' => '#a54731',
        'tertiary-dim' => '#6b5439',
        'surface-container' => '#f6f4ec',
        'on-surface-variant' => '#64655d',
        'surface-container-highest' => '#eae9de',
        'on-tertiary' => '#ffffff',
        'primary-dim' => '#664f1f',
        'on-primary-fixed-variant' => '#705827',
        'surface-container-lowest' => '#ffffff',
        'on-tertiary-fixed-variant' => '#6e573b',
        'on-secondary-container' => '#595140',
        'outline' => '#818178',
        'primary' => $gold,
        'inverse-on-surface' => '#9e9d98',
        'tertiary-fixed-dim' => '#eecfac',
        'secondary' => '#6c6352',
    ], []);
@endphp
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: @json($tailwindColors),
            fontFamily: {
              "headline": ["Newsreader"],
              "body": ["Manrope"],
              "label": ["Manrope"]
            },
            borderRadius: {"DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"},
          },
        },
      }
    </script>
    <style id="nidan-brand-css">
        :root {
            --nidan-primary: {{ $gold }};
            --nidan-cream: {{ $cream }};
        }
        .text-primary { color: var(--nidan-primary) !important; }
        .hover\:text-primary:hover { color: var(--nidan-primary) !important; }
        .bg-primary { background-color: var(--nidan-primary) !important; }
        .from-primary { --tw-gradient-from: var(--nidan-primary) var(--tw-gradient-from-position) !important; }
        .to-primary-container { --tw-gradient-to: #ffdea5 var(--tw-gradient-to-position) !important; }
        .bg-background { background-color: var(--nidan-cream) !important; }
        .bg-surface { background-color: var(--nidan-cream) !important; }
    </style>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        .organic-blob-1 {
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        }
        .organic-blob-2 {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        }
        .organic-blob-3 {
            border-radius: 50% 50% 20% 80% / 25% 80% 20% 75%;
        }
        /* Product grid: distinct organic masks (variations on hero/services language) */
        .product-blob-shape-1 {
            border-radius: 62% 38% 28% 72% / 62% 32% 68% 38%;
        }
        .product-blob-shape-2 {
            border-radius: 32% 68% 72% 28% / 28% 32% 72% 68%;
        }
        .product-blob-shape-3 {
            border-radius: 48% 52% 18% 82% / 24% 78% 22% 76%;
        }
        .product-blob-media {
            transform: translateZ(0);
        }
        /* Organic mask + scale live on this inner container (not the <img>) */
        .product-card-image-container {
            overflow: hidden;
            transform: translateZ(0);
            transition:
                transform 0.3s ease-in-out,
                box-shadow 0.3s ease-in-out;
            box-shadow: 0 16px 48px rgba(55, 56, 49, 0.12);
        }
        .product-card-image-container.scale-active {
            transform: scale(1.05) translateZ(0);
            box-shadow: 0 28px 60px rgba(55, 56, 49, 0.18);
        }
        .product-blob-bg {
            pointer-events: none;
            opacity: 0.38;
            transform: scale(1.18);
            transition:
                transform 0.3s ease-in-out,
                opacity 0.3s ease;
        }
        .product-blob-bg.scale-active {
            transform: scale(1.2744);
            opacity: 0.52;
        }
        @media (prefers-reduced-motion: reduce) {
            .product-card-image-container,
            .product-blob-bg {
                transition: none;
            }
            .product-card-image-container.scale-active {
                transform: translateZ(0);
            }
            .product-blob-bg.scale-active {
                transform: scale(1.18);
                opacity: 0.38;
            }
        }
        .glass-nav {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @vite(['resources/js/nidan-app.js'])
    @stack('head')
</head>
    <body class="bg-background text-on-background font-body selection:bg-primary-container selection:text-on-primary-container @yield('body_class')">
@include('site.partials.announcement-bar')
@include('site.partials.site-nav')
@include('site.partials.store-overlays')
@yield('content')
</body>
</html>
