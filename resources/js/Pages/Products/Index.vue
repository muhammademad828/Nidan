<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useCms } from "@/Composables/useCms";
import { useCart } from "@/Composables/useCart";
import { useCurrency } from "@/Composables/useCurrency";
import { useFilters } from "@/Composables/useFilters";
import { useProductReveal } from "@/Composables/useRevealAnimation";

const props = defineProps({
    products: { type: Object, default: () => ({ data: [] }) },
    categories: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
});

const { t } = useCms();
const { addToCart } = useCart();
const { fmt } = useCurrency();
const { filters, setFilter, clearFilters } = useFilters();

useProductReveal();

const sortOptions = computed(() => [
    { value: "featured", label: t("products.sort_featured", "Featured") },
    { value: "newest", label: t("products.sort_newest", "Newest") },
    {
        value: "price_asc",
        label: t("products.sort_price_asc", "Price: Low to High"),
    },
    {
        value: "price_desc",
        label: t("products.sort_price_desc", "Price: High to Low"),
    },
]);
</script>

<template>
    <AppLayout>
        <div class="products-page section">
            <div class="section-inner">
                <!-- Filters Bar -->
                <div class="filter-bar">
                    <!-- Category filter -->
                    <div class="filter-group">
                        <label class="filter-label">{{
                            t("products.filter_category", "Category")
                        }}</label>
                        <div class="filter-chips">
                            <button
                                :class="[
                                    'filter-chip',
                                    { active: !filters.category },
                                ]"
                                @click="setFilter('category', '')"
                            >
                                All
                            </button>
                            <button
                                v-for="cat in categories"
                                :key="cat.id"
                                :class="[
                                    'filter-chip',
                                    { active: filters.category === cat.slug },
                                ]"
                                @click="setFilter('category', cat.slug)"
                            >
                                {{ cat.name }}
                            </button>
                        </div>
                    </div>

                    <!-- Sort -->
                    <select
                        class="sort-select"
                        :value="filters.sort"
                        @change="setFilter('sort', $event.target.value)"
                    >
                        <option
                            v-for="opt in sortOptions"
                            :key="opt.value"
                            :value="opt.value"
                        >
                            {{ opt.label }}
                        </option>
                    </select>
                </div>

                <!-- Products Grid -->
                <div
                    v-if="products.data?.length"
                    class="product-grid"
                    style="margin-top: 40px"
                >
                    <article
                        v-for="product in products.data"
                        :key="product.id"
                        class="product-card"
                    >
                        <Link :href="route('products.show', product.slug)">
                            <div class="product-img-wrap">
                                <div class="product-img-inner">
                                    <img
                                        v-if="product.primary_image"
                                        :src="product.primary_image"
                                        :alt="product.name"
                                        loading="lazy"
                                        style="
                                            width: 100%;
                                            height: 100%;
                                            object-fit: cover;
                                        "
                                    />
                                    <div
                                        v-else
                                        style="
                                            width: 100%;
                                            height: 100%;
                                            background: var(--cream-light);
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        "
                                    >
                                        <span
                                            style="
                                                font-family: var(--font-serif);
                                                font-size: 28px;
                                                font-style: italic;
                                                color: var(--gold);
                                            "
                                        >
                                            {{ product.name?.charAt(0) }}
                                        </span>
                                    </div>
                                </div>
                                <button
                                    class="product-quick-add"
                                    @click.prevent="addToCart(product.id)"
                                    :disabled="
                                        product.stock_status === 'out_of_stock'
                                    "
                                >
                                    {{
                                        product.stock_status === "out_of_stock"
                                            ? t(
                                                  "product.out_of_stock",
                                                  "Out of Stock",
                                              )
                                            : t(
                                                  "product.quick_add",
                                                  "Quick Add",
                                              )
                                    }}
                                </button>
                            </div>
                        </Link>
                        <div class="product-category">
                            {{ product.category }}
                        </div>
                        <Link
                            :href="route('products.show', product.slug)"
                            class="product-name"
                        >
                            {{ product.name }}
                        </Link>
                        <div
                            style="display: flex; align-items: center; gap: 8px"
                        >
                            <div class="product-price">
                                {{ fmt(product.base_price) }}
                            </div>
                            <div
                                v-if="product.compare_at_price"
                                class="product-price-compare"
                            >
                                {{ fmt(product.compare_at_price) }}
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Empty State -->
                <div v-else class="empty-state">
                    <div class="empty-state-icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <circle cx="11" cy="11" r="10" />
                            <path d="m21 21-4.35-4.35" />
                            <path d="M8 11h6" />
                        </svg>
                    </div>
                    <h3 class="empty-state-title">
                        {{ t("products.empty_title", "No Treasures Found") }}
                    </h3>
                    <p class="empty-state-desc">
                        {{
                            t(
                                "products.empty_desc",
                                "We couldn't find any products matching your criteria. Try adjusting your filters or explore our collection.",
                            )
                        }}
                    </p>
                    <button class="btn-outline-dark" @click="clearFilters">
                        {{ t("products.clear_filters", "Clear All Filters") }}
                    </button>
                </div>

                <!-- Pagination -->
                <div v-if="products.last_page > 1" class="pagination">
                    <Link
                        v-for="page in products.last_page"
                        :key="page"
                        :href="products.links?.[page]?.url || '#'"
                        :class="[
                            'page-btn',
                            { active: page === products.current_page },
                        ]"
                    >
                        {{ page }}
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.filter-bar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
}

.filter-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--text-light);
    margin-bottom: 12px;
    display: block;
}

.filter-chips {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-chip {
    font-family: var(--font-sans);
    font-size: 11px;
    font-weight: 500;
    padding: 7px 16px;
    border: 1px solid rgba(58, 53, 48, 0.2);
    border-radius: 20px;
    background: transparent;
    color: var(--charcoal);
    cursor: pointer;
    transition: all 0.2s;
}

.filter-chip:hover,
.filter-chip.active {
    background: var(--dark);
    color: var(--white);
    border-color: var(--dark);
}

.sort-select {
    font-family: var(--font-sans);
    font-size: 11px;
    padding: 8px 16px;
    border: 1px solid rgba(58, 53, 48, 0.2);
    background: var(--white);
    color: var(--charcoal);
    cursor: pointer;
    outline: none;
    min-width: 180px;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: var(--text-light);
    font-size: 14px;
}

.empty-state-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 20px;
    background: rgba(201, 168, 76, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gold);
}

.empty-state-icon svg {
    width: 28px;
    height: 28px;
}

.empty-state-title {
    font-family: var(--font-serif);
    font-size: 24px;
    font-weight: 400;
    color: var(--dark);
    margin-bottom: 8px;
}

.empty-state-desc {
    font-size: 13px;
    color: var(--text-light);
    max-width: 320px;
    margin: 0 auto 24px;
    line-height: 1.6;
}

.empty-state button {
    margin-top: 20px;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 60px;
}

.page-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(58, 53, 48, 0.2);
    font-size: 13px;
    color: var(--charcoal);
    transition: all 0.2s;
}

.page-btn.active,
.page-btn:hover {
    background: var(--dark);
    color: var(--white);
    border-color: var(--dark);
}
</style>
