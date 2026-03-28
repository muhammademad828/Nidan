<script setup>
import { Link } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";
import { useCart } from "@/Composables/useCart";
import { useCurrency } from "@/Composables/useCurrency";
import { useRevealAnimation } from "@/Composables/useRevealAnimation";

const props = defineProps({
    products: { type: Array, default: () => [] },
});

const { t, getSection } = useCms();
const section = getSection("featured");
const { addToCart } = useCart();
const { fmt } = useCurrency();

useRevealAnimation();
</script>

<template>
    <section class="featured">
        <!-- Header -->
        <div class="featured-header reveal">
            <div>
                <p class="featured-eyebrow">{{ t("featured.subtitle", "Handpicked for you") }}</p>
                <h2 class="featured-title">
                    {{ section?.title || t("featured.title", "Featured Pieces") }}
                </h2>
            </div>
            <Link :href="route('products.index')" class="view-all">
                {{ t("featured.view_all", "View All") }}
            </Link>
        </div>

        <!-- Product Grid -->
        <div class="featured-grid">
            <article
                v-for="(product, idx) in products"
                :key="product.id"
                :class="['pcard', 'reveal', `d${idx + 1}`]"
            >
                <Link :href="route('products.show', product.slug)" class="pcard-img-link">
                    <div class="pcard-img-wrap">
                        <img
                            v-if="product.primary_image"
                            :src="product.primary_image"
                            :alt="product.name"
                            loading="lazy"
                            class="pcard-img"
                        />
                        <div v-else class="pcard-img-placeholder">
                            <span>{{ product.name?.charAt(0) }}</span>
                        </div>

                        <!-- Stock badge -->
                        <span v-if="product.stock_status === 'out_of_stock'" class="pcard-stock-badge out">
                            {{ t("products.out_of_stock", "Sold Out") }}
                        </span>

                    </div>
                </Link>

                <div class="pcard-body">
                    <div class="pcard-category">{{ product.category }}</div>
                    <Link :href="route('products.show', product.slug)" class="pcard-name">
                        {{ product.name }}
                    </Link>
                    <div class="pcard-price">
                        <span class="pcard-price-main">{{ fmt(product.base_price) }}</span>
                        <span v-if="product.compare_at_price" class="pcard-price-compare">
                            {{ fmt(product.compare_at_price) }}
                        </span>
                    </div>
                    <button
                        type="button"
                        class="pcard-add-btn"
                        @click="addToCart(product.id)"
                        :disabled="product.stock_status === 'out_of_stock'"
                    >
                        {{
                            product.stock_status === "out_of_stock"
                                ? t("products.out_of_stock", "Out of Stock")
                                : t("products.add_to_cart", "Add to Cart")
                        }}
                    </button>
                </div>
            </article>

            <!-- Skeleton placeholders when empty -->
            <template v-if="!products.length">
                <div v-for="n in 4" :key="`sk-${n}`" class="pcard pcard-skeleton">
                    <div class="pcard-img-wrap"></div>
                    <div class="pcard-body">
                        <div class="sk-line" style="width:50%; height:9px;"></div>
                        <div class="sk-line" style="width:75%; height:15px; margin-top:8px;"></div>
                        <div class="sk-line" style="width:35%; height:13px; margin-top:6px;"></div>
                    </div>
                </div>
            </template>
        </div>
    </section>
</template>

<style scoped>
.featured {
    background: var(--white);
    padding: 80px var(--section-padding-inline);
}

/* Header */
.featured-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    max-width: var(--max-width);
    margin-inline: auto;
    margin-bottom: 48px;
}

.featured-eyebrow {
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: var(--text-light);
    margin-bottom: 8px;
}
[dir="rtl"] .featured-eyebrow { letter-spacing: 0; }

.featured-title {
    font-family: var(--font-serif);
    font-size: clamp(28px, 3vw, 40px);
    font-weight: 400;
    color: var(--dark);
    line-height: 1.1;
}

/* Grid */
.featured-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    max-width: var(--max-width);
    margin-inline: auto;
}

/* Card */
.pcard {
    display: flex;
    flex-direction: column;
    transition: transform 0.35s var(--ease-luxury);
}
.pcard:hover { transform: translateY(-2px); }

.pcard-img-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.pcard-img-wrap {
    position: relative;
    width: 100%;
    aspect-ratio: 3 / 4;
    overflow: hidden;
    border-radius: 4px;
    background: var(--cream-light);
    margin-bottom: 16px;
    border: 1px solid rgba(58, 53, 48, 0.08);
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.8) inset;
    transition:
        border-color 0.3s ease,
        box-shadow 0.35s var(--ease-luxury);
}
.pcard:hover .pcard-img-wrap {
    border-color: rgba(184, 150, 62, 0.35);
    box-shadow: 0 12px 40px rgba(26, 22, 16, 0.08);
}

.pcard-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
    transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.pcard:hover .pcard-img { transform: scale(1.05); }

/* Shimmer on hover */
.pcard-img-wrap::after {
    content: '';
    position: absolute;
    top: 0;
    inset-inline-start: -100%;
    width: 55%;
    height: 100%;
    background: linear-gradient(105deg, transparent 30%, rgba(255,255,255,0.18) 50%, transparent 70%);
    transition: inset-inline-start 0.7s ease;
    pointer-events: none;
}
.pcard:hover .pcard-img-wrap::after { inset-inline-start: 160%; }

.pcard-img-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, var(--cream), var(--cream-light));
    display: flex; align-items: center; justify-content: center;
}
.pcard-img-placeholder span {
    font-family: var(--font-serif);
    font-size: 48px;
    font-style: italic;
    color: var(--gold);
    opacity: 0.5;
}

.pcard-stock-badge {
    position: absolute;
    top: 14px;
    inset-inline-start: 14px;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 5px 12px;
    border-radius: 2px;
    color: var(--white);
    background: rgba(220, 38, 38, 0.9);
    z-index: 2;
}

/* Body */
.pcard-body {
    padding: 0 2px;
    display: flex;
    flex-direction: column;
    flex: 1;
    gap: 0;
}

.pcard-category {
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--text-light);
    margin-bottom: 5px;
}
[dir="rtl"] .pcard-category { letter-spacing: 0; }

.pcard-name {
    display: block;
    font-family: var(--font-serif);
    font-size: 17px;
    font-weight: 400;
    color: var(--dark);
    line-height: 1.25;
    margin-bottom: 7px;
    text-decoration: none;
    transition: color 0.2s;
}
.pcard-name:hover { color: var(--gold); }

.pcard-price {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 14px;
    flex-wrap: wrap;
}
.pcard-price-main { font-size: 15px; font-weight: 500; color: var(--charcoal); }
.pcard-price-compare { font-size: 12px; color: var(--text-light); text-decoration: line-through; }

/* Visible add-to-cart — always shown, luxury bar */
.pcard-add-btn {
    margin-top: auto;
    width: 100%;
    padding: 12px 14px;
    font-family: var(--font-sans);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--dark);
    background: var(--white);
    border: 1px solid rgba(58, 53, 48, 0.22);
    border-radius: 3px;
    cursor: pointer;
    transition:
        background 0.25s ease,
        border-color 0.25s ease,
        color 0.25s ease,
        box-shadow 0.25s ease;
}
[dir="rtl"] .pcard-add-btn {
    letter-spacing: 0;
    font-size: 12px;
    text-transform: none;
}
.pcard-add-btn:hover:not(:disabled) {
    background: var(--gold-btn);
    border-color: var(--gold-btn);
    color: var(--white);
    box-shadow: 0 6px 20px rgba(184, 150, 62, 0.28);
}
.pcard-add-btn:disabled {
    opacity: 0.55;
    cursor: not-allowed;
    border-color: rgba(58, 53, 48, 0.12);
}

/* Skeleton */
.pcard-skeleton .pcard-img-wrap { background: var(--cream-light); animation: shimmer 1.5s infinite; }
.sk-line { background: var(--cream); border-radius: 3px; animation: shimmer 1.5s infinite; }
@keyframes shimmer {
    0%, 100% { opacity: 0.7; }
    50%       { opacity: 1;   }
}

/* Responsive */
@media (max-width: 1000px) {
    .featured-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
}
@media (max-width: 640px) {
    .featured { padding: 60px 20px; }
    .featured-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .featured-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .pcard-img-wrap { margin-bottom: 10px; }
}
@media (max-width: 400px) {
    .featured-grid { grid-template-columns: 1fr; }
}
</style>
