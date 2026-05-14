<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ \App\Services\BilingualService::dir() }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('seo_title', 'ورد وهدايا - توصيل المنوفية وكل مصر')</title>
    <meta name="description" content="@yield('seo_desc', 'متجر نيدان أتيليه للورد الطبيعي والهدايا والاكسسوارات. نقدم خدمة توصيل سريعة في محافظة المنوفية وشحن لجميع أنحاء مصر.')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    @yield('prev_next')

    {{-- Open Graph tags --}}
    <meta property="og:title" content="@yield('seo_title', 'ورد وهدايا - توصيل المنوفية وكل مصر')">
    <meta property="og:description" content="@yield('seo_desc', 'متجر نيدان أتيليه للورد الطبيعي والهدايا والاكسسوارات. نقدم خدمة توصيل سريعة في محافظة المنوفية وشحن لجميع أنحاء مصر.')">
    <meta property="og:image" content="@yield('seo_image', asset('logo.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Amiri:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nidan-bg': '#F9F6EE',
                        'nidan-text': '#2D2319',
                        'nidan-gold': '#D4B87E',
                        'nidan-gold-light': '#E9DAB9',
                        'nidan-btn': '#C5A165',
                        'nidan-nav': '#1A1A1A',
                    },
                    fontFamily: {
                        'serif': ['"Playfair Display"', 'Amiri', 'serif'],
                        'sans': ['Inter', '"IBM Plex Sans Arabic"', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        [dir="rtl"] {
            font-family: 'IBM Plex Sans Arabic', sans-serif;
        }
        
        [dir="rtl"] .font-serif, [dir="rtl"] h1, [dir="rtl"] h2, [dir="rtl"] h3 {
            font-family: 'Amiri', serif;
        }

        .blob-shape {
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            overflow: hidden;
            border: 6px solid #CBA469;
        }

        .bg-shape-tl {
            position: absolute;
            top: 0;
            left: 0;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle at top left, #E9DAB9 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }

        .bg-shape-br {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle at bottom right, #E9DAB9 0%, transparent 60%);
            z-index: 0;
            pointer-events: none;
            background-image: radial-gradient(circle at bottom right, rgba(212, 184, 126, 0.4) 0%, transparent 60%),
                url("data:image/svg+xml,%3Csvg width='400' height='400' viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M 10 390 Q 50 350 150 380 T 350 350' fill='none' stroke='%23CBA469' stroke-width='2' stroke-opacity='0.5'/%3E%3Cpath d='M 50 390 Q 100 320 200 360 T 380 320' fill='none' stroke='%23CBA469' stroke-width='2' stroke-opacity='0.4'/%3E%3Cpath d='M 100 390 Q 150 290 250 340 T 390 290' fill='none' stroke='%23CBA469' stroke-width='2' stroke-opacity='0.3'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: bottom right;
        }

        .bg-shape-tr {
            position: absolute;
            top: -50px;
            right: 10%;
            width: 300px;
            height: 300px;
            background-image: url("data:image/svg+xml,%3Csvg width='200' height='200' viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M 150 10 Q 180 50 150 100 T 180 180' fill='none' stroke='%23CBA469' stroke-width='2' stroke-opacity='0.6'/%3E%3Cpath d='M 180 10 Q 190 60 170 120 T 200 190' fill='none' stroke='%23CBA469' stroke-width='2' stroke-opacity='0.4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            z-index: 0;
            pointer-events: none;
        }

        .btn-shadow {
            box-shadow: 0 4px 14px 0 rgba(201, 169, 110, 0.39);
        }

        @keyframes halo-rotate {
            from {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes halo-pulse {

            0%,
            100% {
                opacity: 0.3;
                scale: 1;
            }

            50% {
                opacity: 0.6;
                scale: 1.2;
            }
        }

        .logo-halo {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 180px;
            height: 180px;
            background: conic-gradient(from 0deg, transparent, #D4B87E, transparent, #E9DAB9, transparent);
            filter: blur(20px);
            z-index: -1;
            pointer-events: none;
            animation: halo-rotate 10s linear infinite, halo-pulse 4s ease-in-out infinite;
        }

        .glass-header {
            background: rgba(249, 246, 238, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(212, 184, 126, 0.2);
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            display: inline-block;
            animation: marquee 30s linear infinite;
        }

        #mobile-menu {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            transform: translateY(-20px);
            opacity: 0;
            pointer-events: none;
        }

        #mobile-menu.active {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-item {
            opacity: 0;
            transform: translateY(20px);
        }

        .testimonial-container {
            position: relative;
            min-height: 250px;
        }

        .testimonial-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.8s ease-in-out, visibility 0.8s;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .testimonial-slide.active {
            position: relative;
            opacity: 1;
            visibility: visible;
        }

        .slider-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s;
        }

        .slider-dot.active {
            background: #D4B87E;
            transform: scale(1.5);
        }

        @keyframes soft-pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.03);
            }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-nidan-bg text-nidan-text font-sans antialiased relative overflow-x-hidden flex flex-col m-0 p-0">
    <div class="bg-shape-br"></div>

    <div class="relative">
        <div class="bg-shape-tl"></div>
        <div class="bg-shape-tr"></div>

        @include('front.header')

        <main class="flex-1 relative z-10">
            @yield('content')
        </main>

        @include('front.footer')

        <!-- Cart Sidebar Overlay -->
        <div id="cart-sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[1050] hidden opacity-0 transition-opacity duration-300"></div>
        
        <!-- Cart Sidebar -->
        <div id="cart-sidebar" class="fixed top-0 {{ \App\Services\BilingualService::dir() === 'rtl' ? 'left-0 -translate-x-full' : 'right-0 translate-x-full' }} w-full md:w-[400px] h-full bg-white z-[1100] transition-transform duration-500 ease-in-out shadow-2xl">
            <div id="cart-sidebar-content" class="h-full">
                <!-- Mini Cart HTML will be injected here -->
            </div>
        </div>
    </div>

    @stack('scripts')
    <script>
        function openCartSidebar() {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-sidebar-overlay');
            const isRtl = document.documentElement.dir === 'rtl';
            
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            
            if (isRtl) {
                sidebar.classList.remove('-translate-x-full');
            } else {
                sidebar.classList.remove('translate-x-full');
            }
        }

        function closeCartSidebar() {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-sidebar-overlay');
            const isRtl = document.documentElement.dir === 'rtl';
            
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
            
            if (isRtl) {
                sidebar.classList.add('-translate-x-full');
            } else {
                sidebar.classList.add('translate-x-full');
            }
        }

        document.getElementById('cart-sidebar-overlay')?.addEventListener('click', closeCartSidebar);
        
        // Delegate event for close button since content is dynamic
        document.getElementById('cart-sidebar')?.addEventListener('click', (e) => {
            if (e.target.closest('#close-cart-btn')) {
                closeCartSidebar();
            }
        });
    </script>
</body>

</html>