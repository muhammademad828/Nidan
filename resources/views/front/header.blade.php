<?php use App\Services\BilingualService; ?>

<div class="relative z-[1000]">
    <!-- Background decorative shapes -->
    <div class="bg-shape-tl"></div>
    <div class="bg-shape-tr"></div>

    <!-- Announcement Bar -->
    <div class="w-full bg-nidan-nav text-nidan-gold-light py-2 overflow-hidden whitespace-nowrap border-b border-nidan-gold/20">
        <div class="animate-marquee text-[10px] tracking-[0.3em] uppercase inline-flex items-center">
            @php
                $announcement = \App\Models\SiteSetting::getByKey('announcement_text', 'Eternal Spring — Bespoke Arrangements for Every Occasion — Express 2-Hour Delivery in Cairo —');
            @endphp
            <span class="px-4">{{ $announcement }}</span>
            <span class="px-4">{{ $announcement }}</span>
            <span class="px-4">{{ $announcement }}</span>
            <span class="px-4">{{ $announcement }}</span>
        </div>
    </div>

    <!-- Main Header -->
    <header class="glass-header sticky top-0 w-full transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-2 flex items-center justify-between relative">

            <!-- Left Navigation -->
            <nav class="hidden md:flex items-center gap-12 flex-1">
                <a href="{{ route('collections') }}"
                    class="text-xs uppercase tracking-[0.2em] font-medium text-nidan-text hover:text-nidan-gold transition-colors">
                    {{ BilingualService::label('collections') }}
                </a>
                <a href="#heritage-section"
                    class="text-xs uppercase tracking-[0.2em] font-medium text-nidan-text hover:text-nidan-gold transition-colors">
                    {{ BilingualService::label('heritage') }}
                </a>
                <a href="{{ route('page.show', ['locale' => app()->getLocale(), 'slug' => 'our-story']) }}"
                    class="text-xs uppercase tracking-[0.2em] font-medium text-nidan-text hover:text-nidan-gold transition-colors">
                    {{ app()->getLocale() == 'ar' ? 'قصتنا' : 'Our Story' }}
                </a>
            </nav>

            <!-- Logo (Centered) -->
            <div class="relative flex items-center justify-center -my-6 md:-my-10 z-50">
                <a href="{{ route('home') }}" class="block">
                    <img src="{{ asset('logo.png') }}" alt="Nidan"
                        class="h-28 md:h-36 transition-transform duration-500 hover:scale-105"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <span class="hidden text-6xl md:text-7xl font-serif font-medium tracking-tight text-nidan-text">Nidan</span>
                </a>
            </div>

            <!-- Right Navigation -->
            <nav class="hidden md:flex items-center gap-12 flex-1 justify-end">
                <a href="{{ route('track') }}"
                    class="text-xs uppercase tracking-[0.2em] font-medium text-nidan-text hover:text-nidan-gold transition-colors">
                    {{ BilingualService::label('track_order') }}
                </a>
                @guest
                    <a href="{{ route('login') }}"
                        class="text-xs uppercase tracking-[0.2em] font-medium text-nidan-text hover:text-nidan-gold transition-colors">
                        {{ BilingualService::label('login') }}
                    </a>
                @else
                    <a href="{{ in_array(Auth::user()->role, ['admin', 'staff']) ? route('admin.dashboard') : route('account') }}"
                        class="text-xs uppercase tracking-[0.2em] font-medium text-nidan-text hover:text-nidan-gold transition-colors">
                        {{ BilingualService::label('account') }}
                    </a>
                @endguest
                <button type="button" onclick="openSearchOverlay()"
                    class="text-xs uppercase tracking-[0.2em] font-medium text-nidan-text hover:text-nidan-gold transition-colors focus:outline-none">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('cart') }}"
                    class="text-xs uppercase tracking-[0.2em] font-medium text-nidan-text hover:text-nidan-gold transition-colors">
                    <i class="fas fa-shopping-bag"></i>
                </a>
                <!-- Language Switch -->
                @php $locale = app()->getLocale(); @endphp
                @if($locale === 'en')
                    <a href="{{ BilingualService::localeUrl('ar') }}"
                        class="text-xs uppercase tracking-[0.2em] font-medium text-nidan-text hover:text-nidan-gold transition-colors">العربية</a>
                @else
                    <a href="{{ BilingualService::localeUrl('en') }}"
                        class="text-xs uppercase tracking-[0.2em] font-medium text-nidan-text hover:text-nidan-gold transition-colors">EN</a>
                @endif
            </nav>

            <!-- Mobile Menu Toggle -->
            <button id="menu-toggle"
                class="md:hidden text-nidan-text hover:text-nidan-gold transition-colors absolute right-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </header>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu"
        class="absolute top-[100%] left-0 w-full bg-nidan-bg border-b border-nidan-gold/20 md:hidden overflow-hidden z-[999] shadow-2xl">
        <nav class="flex flex-col items-center py-12 gap-8">
            <a href="{{ route('collections') }}" class="text-lg uppercase tracking-[0.3em] font-serif text-nidan-text hover:text-nidan-gold transition-colors">{{ BilingualService::label('collections') }}</a>
            <a href="#heritage-section" class="text-lg uppercase tracking-[0.3em] font-serif text-nidan-text hover:text-nidan-gold transition-colors">{{ BilingualService::label('heritage') }}</a>
            <a href="{{ route('track') }}" class="text-lg uppercase tracking-[0.3em] font-serif text-nidan-text hover:text-nidan-gold transition-colors">{{ BilingualService::label('track_order') }}</a>
            @guest
                <a href="{{ route('login') }}"
                    class="text-lg uppercase tracking-[0.3em] font-serif text-nidan-text hover:text-nidan-gold transition-colors">
                    {{ BilingualService::label('login') }}
                </a>
            @else
                <a href="{{ in_array(Auth::user()->role, ['admin', 'staff']) ? route('admin.dashboard') : route('account') }}"
                    class="text-lg uppercase tracking-[0.3em] font-serif text-nidan-text hover:text-nidan-gold transition-colors">
                    {{ BilingualService::label('account') }}
                </a>
            @endguest
            <button type="button" onclick="openSearchOverlay()" class="text-lg uppercase tracking-[0.3em] font-serif text-nidan-text hover:text-nidan-gold transition-colors">
                {{ app()->getLocale() == 'ar' ? 'بحث' : 'Search' }}
            </button>
            <a href="{{ route('cart') }}" class="text-lg uppercase tracking-[0.3em] font-serif text-nidan-text hover:text-nidan-gold transition-colors">{{ BilingualService::label('cart') }}</a>
            <div class="flex gap-4 mt-4">
                <a href="{{ BilingualService::localeUrl('en') }}" class="text-sm uppercase tracking-widest text-nidan-text/60 hover:text-nidan-gold">EN</a>
                <span class="text-nidan-text/20">|</span>
                <a href="{{ BilingualService::localeUrl('ar') }}" class="text-sm uppercase tracking-widest text-nidan-text/60 hover:text-nidan-gold">العربية</a>
            </div>
        </nav>
    </div>
</div>

<!-- Full-screen Search Overlay -->
<div id="search-panel" class="fixed inset-0 bg-white/80 backdrop-blur-2xl z-[1100] flex flex-col transform -translate-y-full transition-transform duration-500 ease-in-out hidden">
    <div class="flex-1 overflow-y-auto custom-scrollbar">
        <div class="w-full max-w-5xl mx-auto px-6 md:px-12 py-24">
            
            <!-- Close Button (Top Corner) -->
            <button onclick="closeSearchOverlay()" class="absolute top-8 right-8 text-nidan-text/40 hover:text-nidan-gold transition-colors focus:outline-none p-2 group">
                <span class="text-xs uppercase tracking-widest font-bold mr-2 opacity-0 group-hover:opacity-100 transition-opacity">{{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}</span>
                <i class="fas fa-times text-2xl"></i>
            </button>

            <!-- Search Input Section -->
            <div class="mb-16">
                <div class="relative flex items-center border-b-2 border-nidan-gold/20 py-6 focus-within:border-nidan-gold transition-all duration-500">
                    <i class="fas fa-search text-3xl text-nidan-gold mr-6"></i>
                    <input type="text" id="search-input" 
                        class="w-full bg-transparent border-none focus:ring-0 text-3xl md:text-5xl font-serif text-nidan-text placeholder-nidan-text/10"
                        placeholder="{{ app()->getLocale() == 'ar' ? 'عن ماذا تبحث؟' : 'What are you looking for?' }}"
                        autocomplete="off"
                        dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
                    >
                    <div id="search-spinner" class="hidden ml-4">
                        <i class="fas fa-spinner fa-spin text-2xl text-nidan-gold"></i>
                    </div>
                </div>
                
                {{-- Price Filter for Search --}}
                <div class="mt-8 flex flex-wrap items-center gap-4 animate-fade-in-up" style="animation-delay: 200ms;">
                    <div class="flex items-center gap-3 bg-white/40 backdrop-blur-md border border-nidan-gold/20 rounded-full px-6 py-2 shadow-sm">
                        <span class="text-[9px] uppercase tracking-[0.2em] text-nidan-gold font-bold whitespace-nowrap">{{ BilingualService::label('price_range') }}</span>
                        <div class="flex items-center gap-3">
                            <input type="number" id="search-min-price" placeholder="{{ BilingualService::label('min') }}" 
                                class="w-16 bg-transparent border-none focus:ring-0 text-[11px] font-bold p-0 placeholder-gray-300">
                            <span class="text-nidan-gold/20">—</span>
                            <input type="number" id="search-max-price" placeholder="{{ BilingualService::label('max') }}" 
                                class="w-16 bg-transparent border-none focus:ring-0 text-[11px] font-bold p-0 placeholder-gray-300">
                        </div>
                    </div>
                    
                    <div class="relative bg-white/40 backdrop-blur-md border border-nidan-gold/20 rounded-full px-6 py-2 shadow-sm flex items-center gap-3">
                        <span class="text-[9px] uppercase tracking-[0.2em] text-nidan-gold font-bold whitespace-nowrap">{{ BilingualService::label('sort_by') }}</span>
                        <select id="search-sort" class="bg-transparent border-none focus:ring-0 text-[11px] font-bold p-0 pr-6 cursor-pointer appearance-none text-nidan-text">
                            <option value="newest">{{ BilingualService::label('newest') }}</option>
                            <option value="price_asc">{{ BilingualService::label('price_asc') }}</option>
                            <option value="price_desc">{{ BilingualService::label('price_desc') }}</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-6 text-[8px] text-nidan-gold pointer-events-none"></i>
                    </div>

                    <button onclick="document.getElementById('search-min-price').value=''; document.getElementById('search-max-price').value=''; document.getElementById('search-input').dispatchEvent(new Event('input'));" 
                        class="px-6 py-2 text-[9px] uppercase tracking-[0.2em] text-gray-400 hover:text-red-400 transition-colors font-bold">
                        <i class="fas fa-undo-alt mr-1"></i>
                        {{ BilingualService::label('clear') }}
                    </button>
                </div>
            </div>

            <!-- Initial Suggestions -->
            <div id="search-suggestions" class="transition-opacity duration-300">
                <h4 class="text-[10px] tracking-[0.4em] uppercase text-nidan-gold font-bold mb-6">{{ app()->getLocale() == 'ar' ? 'عمليات بحث شائعة' : 'Trending Searches' }}</h4>
                <div class="flex flex-wrap gap-4">
                    @php
                        $trends = app()->getLocale() == 'ar' ? ['بوكيه ورد', 'هدايا عيد ميلاد', 'صندوق شوكولاتة', 'زفاف'] : ['Rose Bouquet', 'Birthday Gifts', 'Chocolate Box', 'Wedding'];
                    @endphp
                    @foreach($trends as $trend)
                        <button onclick="document.getElementById('search-input').value='{{ $trend }}'; document.getElementById('search-input').dispatchEvent(new Event('input'));" 
                            class="px-8 py-3 rounded-full border border-nidan-gold/10 text-sm font-medium text-nidan-text hover:bg-nidan-gold hover:text-white transition-all duration-500 bg-white/50 backdrop-blur-sm shadow-sm">
                            {{ $trend }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Search Results Container -->
            <div class="mt-16 pb-12">
                <div id="search-results" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-12">
                    <!-- Results injected via JS -->
                </div>
                
                <!-- Empty state -->
                <div id="search-empty" class="hidden py-24 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-nidan-gold/5 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-search text-2xl text-nidan-gold/30"></i>
                    </div>
                    <h3 class="text-2xl font-serif text-nidan-text mb-3">{{ app()->getLocale() == 'ar' ? 'لم نجد نتائج' : 'No results found' }}</h3>
                    <p class="text-gray-400 max-w-xs mx-auto">{{ app()->getLocale() == 'ar' ? 'حاول البحث بكلمات مختلفة أو استعرض مجموعاتنا المميزة.' : 'Try searching with different keywords or explore our signature collections.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let searchTimeout = null;
    const searchPanel = document.getElementById('search-panel');
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');
    const searchResults = document.getElementById('search-results');
    const searchEmpty = document.getElementById('search-empty');
    const searchSpinner = document.getElementById('search-spinner');

    function openSearchOverlay() {
        searchPanel.classList.remove('hidden');
        
        // Trigger animations
        setTimeout(() => {
            searchPanel.classList.remove('-translate-y-full');
            searchInput.focus();
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeSearchOverlay() {
        searchPanel.classList.add('-translate-y-full');
        
        setTimeout(() => {
            searchPanel.classList.add('hidden');
            searchInput.value = '';
            searchResults.innerHTML = '';
            searchEmpty.classList.add('hidden');
            searchSuggestions.classList.remove('hidden');
        }, 500);
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !searchPanel.classList.contains('hidden')) {
            closeSearchOverlay();
        }
    });

    const searchSort = document.getElementById('search-sort');
    const searchMin = document.getElementById('search-min-price');
    const searchMax = document.getElementById('search-max-price');

    function performSearch() {
        const query = searchInput.value.trim();
        const minPrice = searchMin.value;
        const maxPrice = searchMax.value;
        const sort = searchSort.value;

        if (query.length < 2) {
            searchResults.innerHTML = '';
            searchEmpty.classList.add('hidden');
            searchSpinner.classList.add('hidden');
            searchSuggestions.classList.remove('hidden');
            return;
        }

        searchSuggestions.classList.add('hidden');
        searchSpinner.classList.remove('hidden');
        searchEmpty.classList.add('hidden');

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            let url = `/{{ app()->getLocale() }}/search?q=${encodeURIComponent(query)}`;
            if (minPrice) url += `&min_price=${minPrice}`;
            if (maxPrice) url += `&max_price=${maxPrice}`;
            if (sort) url += `&sort=${sort}`;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                searchSpinner.classList.add('hidden');
                searchResults.innerHTML = '';
                
                if (data.products && data.products.length > 0) {
                    data.products.forEach((product, index) => {
                        const defaultImage = 'https://via.placeholder.com/400x400?text=Nidan';
                        const image = product.image ? product.image : defaultImage;
                        
                        const animationDelay = index * 30;
                        
                        const html = `
                            <a href="${product.url}" class="group block animate-fade-in-up" style="animation-delay: ${animationDelay}ms;">
                                <div class="relative w-full aspect-square rounded-xl overflow-hidden mb-3 bg-gray-50 border border-gray-100">
                                    <img src="${image}" alt="${product.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <div>
                                    ${product.category ? `<p class="text-[9px] uppercase tracking-widest text-nidan-gold mb-1">${product.category}</p>` : ''}
                                    <h4 class="font-serif text-sm text-nidan-text group-hover:text-nidan-gold transition-colors line-clamp-1">${product.name}</h4>
                                    <p class="font-medium text-nidan-text text-sm mt-1">${product.price} ج.م</p>
                                </div>
                            </a>
                        `;
                        searchResults.insertAdjacentHTML('beforeend', html);
                    });
                } else {
                    searchEmpty.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                searchSpinner.classList.add('hidden');
            });
        }, 300);
    }

    searchInput.addEventListener('input', performSearch);
    searchSort.addEventListener('change', performSearch);
    searchMin.addEventListener('input', performSearch);
    searchMax.addEventListener('input', performSearch);

    // Mobile Menu Toggle
    document.getElementById('menu-toggle')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('active');
    });
</script>
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
        opacity: 0;
    }
</style>
