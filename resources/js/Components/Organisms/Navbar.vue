<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";
import { useCart } from "@/Composables/useCart";
import { useCurrency } from "@/Composables/useCurrency";
import LanguageSwitcher from "@/Components/Atoms/LanguageSwitcher.vue";
import BrandMark from "@/Components/BrandMark.vue";
import { debounce } from "@/Utils/debounce";

const { t, getSetting } = useCms();
const { itemCount, toggle: toggleCart } = useCart();
const { fmt } = useCurrency();
const _page = usePage();
const authUser = computed(() => _page.props.auth?.user ?? null);

/* ── Search Panel ── */
const searchOpen = ref(false);
const searchQuery = ref("");
const searchInput = ref(null);
const searching = ref(false);
const results = ref({ products: [], categories: [] });

async function openSearch() {
    searchOpen.value = true;
    await nextTick();
    searchInput.value?.focus();
}

function closeSearch() {
    searchOpen.value = false;
    searchQuery.value = "";
    results.value = { products: [], categories: [] };
}

const doSearch = debounce(async (q) => {
    if (!q || q.trim().length < 2) {
        results.value = { products: [], categories: [] };
        searching.value = false;
        return;
    }
    searching.value = true;
    try {
        const res = await fetch(
            `/api/search?q=${encodeURIComponent(q.trim())}`,
        );
        results.value = await res.json();
    } catch {
        results.value = { products: [], categories: [] };
    } finally {
        searching.value = false;
    }
}, 380);

watch(searchQuery, (val) => doSearch(val));

function handleSearchKey(e) {
    if (e.key === "Escape") closeSearch();
    if (e.key === "Enter" && searchQuery.value.trim()) {
        closeSearch();
        router.visit(route("products.index", { q: searchQuery.value.trim() }));
    }
}

function goToProduct(slug) {
    closeSearch();
    router.visit(route("products.show", slug));
}

/* ── Mobile Menu ── */
const mobileMenuOpen = ref(false);

function openMobileMenu() {
    mobileMenuOpen.value = true;
}

function closeMobileMenu() {
    mobileMenuOpen.value = false;
}

/**
 * Profile & customer login use `dashboard.blade.php` (isolated shell). Inertia client visits
 * from the storefront keep the original `app` root — force a full document load.
 */
function navigateAccountFull(e) {
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) {
        return;
    }
    e.preventDefault();
    closeMobileMenu();
    window.location.assign(route("profile.index"));
}

function navigateLoginFull(e) {
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) {
        return;
    }
    e.preventDefault();
    closeMobileMenu();
    window.location.assign(route("login"));
}

/* ── Close on Escape globally ── */
function onKeydown(e) {
    if (e.key === "Escape" && searchOpen.value) closeSearch();
    if (e.key === "Escape" && mobileMenuOpen.value) closeMobileMenu();
}
onMounted(() => {
    window.addEventListener("keydown", onKeydown);
});
onUnmounted(() => {
    window.removeEventListener("keydown", onKeydown);
});

const hasResults = computed(
    () =>
        results.value.products?.length > 0 ||
        results.value.categories?.length > 0,
);
</script>

<template>
    <!-- ── Mobile Menu Overlay ── -->
    <Transition name="mobile-menu-fade">
        <div
            v-if="mobileMenuOpen"
            class="mobile-menu-overlay"
            @click.self="closeMobileMenu"
        >
            <div class="mobile-menu-panel">
                <div class="mobile-menu-header">
                    <h3>{{ t("nav.menu", "Menu") }}</h3>
                    <button
                        class="mobile-menu-close"
                        @click="closeMobileMenu"
                        aria-label="Close menu"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="mobile-menu-nav">
                    <Link
                        :href="route('products.index')"
                        class="mobile-menu-link"
                        @click="closeMobileMenu"
                    >
                        {{ t("nav.shop", "Shop") }}
                    </Link>
                    <Link
                        :href="route('products.index')"
                        class="mobile-menu-link"
                        @click="closeMobileMenu"
                    >
                        {{ t("nav.collections", "Collections") }}
                    </Link>
                    <Link
                        :href="`${route('home')}#services`"
                        class="mobile-menu-link"
                        @click="closeMobileMenu"
                    >
                        {{ t("nav.about", "About") }}
                    </Link>

                    <div class="mobile-menu-divider"></div>

                    <a
                        v-if="authUser"
                        :href="route('profile.index')"
                        class="mobile-menu-link"
                        @click="navigateAccountFull"
                    >
                        {{ t("nav.account", "My Account") }}
                    </a>
                    <a
                        v-else
                        :href="route('login')"
                        class="mobile-menu-link"
                        @click="navigateLoginFull"
                    >
                        {{ t("nav.login", "Login") }}
                    </a>
                </nav>
            </div>
        </div>
    </Transition>

    <!-- ── Search Overlay ── -->
    <Transition name="search-fade">
        <div v-if="searchOpen" class="search-overlay" @click.self="closeSearch">
            <div class="search-panel">
                <div class="search-bar">
                    <svg
                        class="search-icon-input"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input
                        ref="searchInput"
                        v-model="searchQuery"
                        type="search"
                        class="search-input"
                        :placeholder="
                            t(
                                'nav.search_placeholder',
                                'Search products, SKU, occasions…',
                            )
                        "
                        autocomplete="off"
                        @keydown="handleSearchKey"
                    />
                    <button
                        class="search-close-btn"
                        @click="closeSearch"
                        aria-label="Close search"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Spinner -->
                <div v-if="searching" class="search-loading">
                    <span class="search-spinner"></span>
                    <span>{{ t("search.searching", "Searching…") }}</span>
                </div>

                <!-- Results -->
                <div v-else-if="hasResults" class="search-results">
                    <!-- Category hits -->
                    <div v-if="results.categories?.length" class="search-group">
                        <p class="search-group-label">
                            {{ t("search.results_category", "Categories") }}
                        </p>
                        <div class="search-cats">
                            <a
                                v-for="cat in results.categories"
                                :key="cat.slug"
                                class="search-cat-tag"
                                :href="
                                    route('products.index', {
                                        category: cat.slug,
                                    })
                                "
                                @click.prevent="
                                    closeSearch();
                                    router.visit(
                                        route('products.index', {
                                            category: cat.slug,
                                        }),
                                    );
                                "
                            >
                                {{ cat.name }}
                            </a>
                        </div>
                    </div>
                    <!-- Product hits -->
                    <div v-if="results.products?.length" class="search-group">
                        <p class="search-group-label">
                            {{ t("search.results_name", "Products") }}
                        </p>
                        <div class="search-product-list">
                            <button
                                v-for="p in results.products.slice(0, 6)"
                                :key="p.id"
                                class="search-product-item"
                                @click="goToProduct(p.slug)"
                            >
                                <div class="search-product-img">
                                    <img
                                        v-if="p.primary_image"
                                        :src="p.primary_image"
                                        :alt="p.name"
                                    />
                                    <div
                                        v-else
                                        class="search-product-img-placeholder"
                                    ></div>
                                </div>
                                <div class="search-product-info">
                                    <span class="search-product-name">{{
                                        p.name
                                    }}</span>
                                    <span class="search-product-sku">{{
                                        p.sku
                                    }}</span>
                                </div>
                                <span class="search-product-price">{{
                                    fmt(p.base_price)
                                }}</span>
                            </button>
                        </div>
                        <a
                            v-if="results.products.length > 6"
                            class="search-view-all"
                            :href="route('products.index', { q: searchQuery })"
                            @click.prevent="
                                closeSearch();
                                router.visit(
                                    route('products.index', { q: searchQuery }),
                                );
                            "
                        >
                            See all {{ results.products.length }} results →
                        </a>
                    </div>
                </div>

                <!-- No Results -->
                <div
                    v-else-if="searchQuery.length >= 2 && !searching"
                    class="search-empty"
                >
                    <p>
                        {{ t("search.no_results", "No results found for") }}
                        "<strong>{{ searchQuery }}</strong
                        >"
                    </p>
                </div>
            </div>
        </div>
    </Transition>

    <!-- ── Navbar (test1 pill) ── -->
    <nav
        class="fixed top-8 left-1/2 z-[100] flex w-[95%] max-w-[1400px] -translate-x-1/2 items-center justify-between gap-2 rounded-full border border-stone-200/40 bg-[#fffcf7]/70 px-4 py-3 shadow-sm backdrop-blur-xl md:gap-4 md:px-10 md:py-4 dark:border-stone-700/50 dark:bg-stone-900/70 dark:shadow-none"
        role="navigation"
    >
        <div class="flex min-w-0 flex-1 items-center gap-3 md:gap-8">
            <button
                class="mobile-menu-btn flex shrink-0 items-center justify-center rounded-full p-2 text-stone-600 transition-colors hover:bg-stone-200/40 hover:text-primary md:hidden dark:text-stone-300"
                type="button"
                @click="openMobileMenu"
                :aria-label="t('nav.menu', 'Menu')"
            >
                <svg
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <div
                class="hidden items-center gap-8 md:flex rtl:flex-row-reverse"
            >
                <Link
                    :href="route('products.index')"
                    class="font-body text-xs uppercase tracking-widest text-stone-600 transition-colors duration-300 hover:text-primary dark:text-stone-400 dark:hover:text-[#c5a367]"
                >
                    {{ t("test1.nav_floral", "Floral") }}
                </Link>
                <Link
                    :href="`${route('home')}#services`"
                    class="font-body text-xs uppercase tracking-widest text-stone-600 transition-colors duration-300 hover:text-primary dark:text-stone-400 dark:hover:text-[#c5a367]"
                >
                    {{ t("test1.nav_services", "Services") }}
                </Link>
            </div>
        </div>

        <div
            class="pointer-events-none absolute left-1/2 flex -translate-x-1/2 justify-center md:static md:translate-x-0 md:pointer-events-auto"
        >
            <Link
                :href="route('home')"
                class="pointer-events-auto inline-flex items-center justify-center"
                :aria-label="`${getSetting('general', 'site_name', 'Nidan')} — Home`"
            >
                <BrandMark
                    :as-link="false"
                    img-class="mx-auto"
                    text-class="font-headline text-2xl italic tracking-tighter text-primary dark:text-[#c5a367] md:text-3xl"
                />
            </Link>
        </div>

        <div
            class="flex flex-1 items-center justify-end gap-2 md:gap-4 rtl:flex-row-reverse"
        >
            <div
                class="mr-2 hidden items-center gap-8 md:flex rtl:ml-2 rtl:mr-0 rtl:flex-row-reverse"
            >
                <Link
                    :href="`${route('home')}#services`"
                    class="font-body text-xs uppercase tracking-widest text-stone-600 transition-colors duration-300 hover:text-primary dark:text-stone-400"
                >
                    {{ t("nav.about", "About") }}
                </Link>
                <a
                    :href="
                        getSetting('contact', 'whatsapp_number')
                            ? `https://wa.me/${getSetting('contact', 'whatsapp_number')}`
                            : '#'
                    "
                    class="font-body text-xs uppercase tracking-widest text-stone-600 transition-colors duration-300 hover:text-primary dark:text-stone-400"
                    target="_blank"
                    rel="noopener"
                >
                    {{ t("nav.contact", "Contact") }}
                </a>
            </div>
            <LanguageSwitcher />
            <div
                class="flex items-center gap-1 text-primary md:gap-2 dark:text-[#c5a367]"
            >
                <button
                    type="button"
                    class="scale-95 rounded-full p-2 transition-transform hover:bg-stone-200/30 active:scale-90 dark:hover:bg-stone-700/40"
                    @click="openSearch"
                    :aria-label="t('nav.search_placeholder', 'Search')"
                >
                    <span class="material-symbols-outlined text-[22px] md:text-[24px]"
                        >search</span
                    >
                </button>
                <a
                    v-if="authUser"
                    :href="route('profile.index')"
                    class="scale-95 rounded-full p-2 transition-transform hover:bg-stone-200/30 active:scale-90 dark:hover:bg-stone-700/40"
                    :aria-label="authUser.name"
                    @click="navigateAccountFull"
                >
                    <span class="material-symbols-outlined text-[22px] md:text-[24px]"
                        >person</span
                    >
                </a>
                <a
                    v-else
                    :href="route('login')"
                    class="scale-95 rounded-full p-2 transition-transform hover:bg-stone-200/30 active:scale-90 dark:hover:bg-stone-700/40"
                    :aria-label="t('nav.login', 'Login')"
                    @click="navigateLoginFull"
                >
                    <span class="material-symbols-outlined text-[22px] md:text-[24px]"
                        >person</span
                    >
                </a>
                <button
                    type="button"
                    class="relative scale-95 rounded-full p-2 transition-transform hover:bg-stone-200/30 active:scale-90 dark:hover:bg-stone-700/40"
                    @click="toggleCart"
                    :aria-label="t('nav.cart', 'Cart')"
                >
                    <span class="material-symbols-outlined text-[22px] md:text-[24px]"
                        >shopping_bag</span
                    >
                    <span
                        v-if="itemCount > 0"
                        class="absolute -right-0.5 -top-0.5 flex h-3.5 min-w-3.5 items-center justify-center rounded-full bg-primary px-0.5 text-[8px] font-semibold text-on-primary"
                        >{{ itemCount > 99 ? "99+" : itemCount }}</span
                    >
                </button>
            </div>
        </div>
    </nav>
</template>

<style scoped>
/* أنماط البحث العامة في components.css */

.mobile-menu-overlay {
    position: fixed;
    inset: 0;
    background: rgba(10, 8, 5, 0.6);
    backdrop-filter: blur(6px);
    z-index: 500;
}
.mobile-menu-panel {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 300px;
    max-width: 80vw;
    background: var(--nav-bg);
    padding: 80px 30px 40px;
    box-shadow: 2px 0 24px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}
[dir="rtl"] .mobile-menu-panel {
    left: auto;
    right: 0;
    box-shadow: -2px 0 24px rgba(0, 0, 0, 0.1);
}
.mobile-menu-links {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.mobile-menu-link {
    display: block;
    padding: 12px 0;
    font-size: 18px;
    font-family: var(--font-serif);
    color: var(--dark);
    text-decoration: none;
    border-bottom: 1px solid rgba(58, 53, 48, 0.1);
}

/* ── Transition ── */
.mobile-menu-fade-enter-active,
.mobile-menu-fade-leave-active {
    transition: opacity 0.3s ease;
}
.mobile-menu-fade-enter-active .mobile-menu-panel,
.mobile-menu-fade-leave-active .mobile-menu-panel {
    transition: transform 0.3s var(--ease-luxury);
}
.mobile-menu-fade-enter-from,
.mobile-menu-fade-leave-to {
    opacity: 0;
}
.mobile-menu-fade-enter-from .mobile-menu-panel {
    transform: translateX(-100%);
}
.mobile-menu-fade-leave-to .mobile-menu-panel {
    transform: translateX(-100%);
}
[dir="rtl"] .mobile-menu-fade-enter-from .mobile-menu-panel {
    transform: translateX(100%);
}
[dir="rtl"] .mobile-menu-fade-leave-to .mobile-menu-panel {
    transform: translateX(100%);
}

.search-fade-enter-active,
.search-fade-leave-active {
    transition: opacity 0.25s ease;
}
.search-fade-enter-active .search-panel,
.search-fade-leave-active .search-panel {
    transition:
        transform 0.3s var(--ease-luxury),
        opacity 0.25s ease;
}
.search-fade-enter-from,
.search-fade-leave-to {
    opacity: 0;
}
.search-fade-enter-from .search-panel {
    transform: translateY(-16px);
    opacity: 0;
}
.search-fade-leave-to .search-panel {
    transform: translateY(-8px);
    opacity: 0;
}
</style>
