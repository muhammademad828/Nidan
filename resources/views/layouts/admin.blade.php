<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — Nidan Atelier')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Manrope:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nidan-gold': '#D4B87E',
                        'nidan-dark': '#1b1c19',
                        'nidan-bg': '#fdfaf5',
                    },
                    fontFamily: {
                        'serif': ['Noto Serif', 'serif'],
                        'sans': ['Manrope', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: #fdfaf5;
            font-family: 'Manrope', sans-serif;
        }

        .sidebar-link.active {
            background: #D4B87E;
            color: white;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E9DAB9;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #D4B87E;
        }
    </style>
</head>

<body class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-xl h-screen fixed left-0 top-0 flex flex-col z-50">
        <div class="p-6 border-b border-gray-100">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-nidan-gold rounded-full flex items-center justify-center">
                    <span class="text-white font-serif font-bold">N</span>
                </div>
                <div>
                    <span class="font-serif font-bold text-nidan-text">Nidan</span>
                    <span class="block text-[10px] text-gray-400 uppercase tracking-widest">Atelier Admin</span>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-1 custom-scrollbar">
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line w-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.slides.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.slides.*') ? 'active' : '' }}">
                <i class="fas fa-images w-5"></i>
                <span>Hero Slides</span>
            </a>
            <a href="{{ route('admin.event-slides.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.event-slides.*') ? 'active' : '' }}">
                <i class="fas fa-film w-5"></i>
                <span>Event Slider</span>
            </a>
            <a href="{{ route('admin.products.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-box w-5"></i>
                <span>Products</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags w-5"></i>
                <span>Categories</span>
            </a>
            <a href="{{ route('admin.vendors.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                <i class="fas fa-handshake w-5"></i>
                <span>Vendors</span>
            </a>
            <a href="{{ route('admin.addons.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.addons.*') ? 'active' : '' }}">
                <i class="fas fa-plus-circle w-5"></i>
                <span>Add-ons</span>
            </a>
            <a href="{{ route('admin.orders.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.orders.*') || request()->routeIs('admin.custom-orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag w-5"></i>
                <span>Orders</span>
            </a>
            <a href="{{ route('admin.customers.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-user-friends w-5"></i>
                <span>Customers</span>
            </a>
            <a href="{{ route('admin.shipping.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}">
                <i class="fas fa-truck w-5"></i>
                <span>Shipping Rates</span>
            </a>
            <a href="{{ route('admin.reviews.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="fas fa-star w-5"></i>
                <span>Reviews</span>
            </a>
            <a href="{{ route('admin.policies.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.policies.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt w-5"></i>
                <span>Policies</span>
            </a>
            <a href="{{ route('admin.settings.announcement') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.settings.announcement') ? 'active' : '' }}">
                <i class="fas fa-bullhorn w-5"></i>
                <span>Announcement Bar</span>
            </a>
            <a href="{{ route('admin.policies.terms') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.policies.terms') ? 'active' : '' }}">
                <i class="fas fa-gavel w-5"></i>
                <span>Terms & Conditions</span>
            </a>
            <a href="{{ route('admin.settings.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                <i class="fas fa-cog w-5"></i>
                <span>Site Settings</span>
            </a>
            <a href="{{ route('admin.home-sections.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.home-sections.*') ? 'active' : '' }}">
                <i class="fas fa-layer-group w-5"></i>
                <span>Home Sections</span>
            </a>
            <a href="{{ route('admin.tags.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                <i class="fas fa-tags w-5"></i>
                <span>Product Tags</span>
            </a>
            <a href="{{ route('admin.pages.index') }}"
                class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice w-5"></i>
                <span>Static Pages</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100 mt-auto">
            <a href="{{ route('home') }}" target="_blank"
                class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors text-sm">
                <i class="fas fa-external-link-alt w-4"></i>
                <span>View Storefront</span>
            </a>
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-1">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-500 hover:bg-red-50 transition-colors text-sm">
                    <i class="fas fa-sign-out-alt w-4"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="ml-64 flex-1 min-h-screen flex flex-col">
        <!-- Topbar -->
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-40">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                @hasSection('breadcrumb')
                    <nav class="text-sm text-gray-400 mt-0.5">@yield('breadcrumb')</nav>
                @endif
            </div>
            <div class="flex items-center gap-6">
                {{-- Notifications --}}
                <div class="relative group" onmouseover="markNotificationsAsRead()">
                    <button class="relative p-2 text-gray-400 hover:text-nidan-gold transition-colors">
                        <i class="fas fa-bell text-xl"></i>
                        <span id="notif-badge" class="{{ $notif_total > 0 ? '' : 'hidden' }} absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center animate-pulse">
                            {{ $notif_total }}
                        </span>
                    </button>
                    
                    {{-- Notification Dropdown --}}
                    <div class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50 transform origin-top-right scale-95 group-hover:scale-100">
                        <div class="p-4 border-b border-gray-50 flex justify-between items-center">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Activity Center</span>
                        </div>
                        <div class="p-2">
                            @if($notif_pending_orders > 0)
                            <a href="{{ route('admin.orders.index', ['status' => 'pending_confirmation']) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-amber-50 transition-colors group/item">
                                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs font-bold text-gray-900">{{ $notif_pending_orders }} New Orders</div>
                                    <div class="text-[10px] text-gray-400">Awaiting your confirmation</div>
                                </div>
                            </a>
                            @endif

                            @if($notif_pending_reviews > 0)
                            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-blue-50 transition-colors group/item">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs font-bold text-gray-900">{{ $notif_pending_reviews }} New Reviews</div>
                                    <div class="text-[10px] text-gray-400">Pending approval</div>
                                </div>
                            </a>
                            @endif

                            @if($notif_new_customers > 0)
                            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-green-50 transition-colors group/item">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs font-bold text-gray-900">{{ $notif_new_customers }} New Clients</div>
                                    <div class="text-[10px] text-gray-400">Joined in last 24h</div>
                                </div>
                            </a>
                            @endif

                            @if($notif_total == 0)
                            <div class="py-8 text-center">
                                <i class="fas fa-check-circle text-gray-100 text-4xl mb-2"></i>
                                <div class="text-xs text-gray-400">All caught up!</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="h-6 w-px bg-gray-100"></div>

                <div class="flex items-center gap-4">
                    <span class="text-xs text-gray-400">{{ now()->format('d M Y') }}</span>
                    <span class="px-3 py-1 bg-nidan-gold/10 text-nidan-gold rounded-full text-xs font-medium">
                        {{ Auth::user()->name }}
                    </span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-8 flex-1">
            @if(session('success'))
                <div
                    class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div
                    class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif
            @yield('page-content')
        </div>
    </div>
    @stack('modals')
    @stack('scripts')
    <script>
        let notifsRead = false;
        function markNotificationsAsRead() {
            if (notifsRead) return;
            
            fetch("{{ route('admin.notifications.markRead') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    notifsRead = true;
                    const badge = document.getElementById('notif-badge');
                    if (badge) {
                        badge.classList.add('hidden');
                    }
                }
            }).catch(error => console.error('Error marking notifs as read:', error));
        }
    </script>
</body>

</html>