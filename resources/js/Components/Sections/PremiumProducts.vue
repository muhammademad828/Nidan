<script setup>
import { Link } from '@inertiajs/vue3'
import { useCurrency } from '@/Composables/useCurrency'
import { useCms } from '@/Composables/useCms'

const { t } = useCms()

const props = defineProps({
    products: { type: Array, default: () => [] }
})

const { fmt } = useCurrency()
</script>

<template>
    <section class="premium-products">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <p class="section-eyebrow">
                    {{ t('premium.eyebrow', 'مختارات من المخزن') }}
                </p>
                <h2 class="section-title">
                    {{ t('premium.title', 'منتجات نرشّحها بصراحة') }}
                </h2>
                <p class="section-description">
                    {{ t('premium.desc', 'بدون شعارات مبالغ فيها — فقط ما نشتريه أو نهديه لأنفسنا.') }}
                </p>
            </div>

            <!-- Products Grid -->
            <div class="products-grid">
                <div 
                    v-for="(product, index) in products.slice(0, 6)" 
                    :key="product.id"
                    class="featured-card"
                    :style="{ animationDelay: `${index * 0.05}s` }"
                >
                    <div class="product-image-container">
                        <img 
                            :src="product.primary_image || '/images/placeholder.jpg'" 
                            :alt="product.name"
                            class="product-image"
                        />
                        <div class="product-overlay">
                            <Link :href="route('products.show', product.slug)" class="btn-quick-view">
                                <span>عرض سريع</span>
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </Link>
                        </div>
                        
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-name">{{ product.name }}</h3>
                        <p class="product-category">{{ product.category?.name || 'فاخر' }}</p>
                        <div class="product-price">
                            <span class="current-price">{{ fmt(product.base_price) }}</span>
                            <span v-if="product.compare_at_price" class="original-price">
                                {{ fmt(product.compare_at_price) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View All Button -->
            <div class="section-footer">
                <Link :href="route('products.index')" class="btn-view-all">
                    <span>عرض جميع المنتجات</span>
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </Link>
            </div>
        </div>
    </section>
</template>
<style scoped>
.premium-products {
    padding: var(--section-padding-block) 0;
    background: var(--paper-bright);
    position: relative;
    border-bottom: 1px solid var(--line);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Section Header */
.section-header {
    text-align: center;
    margin-bottom: 2.75rem;
    max-width: 36em;
    margin-inline: auto;
}

.section-eyebrow {
    font-size: var(--text-xs);
    font-weight: 600;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--ink-muted);
    margin-bottom: 0.75rem;
}

[dir="rtl"] .section-eyebrow {
    letter-spacing: 0.06em;
}

.section-title {
    font-family: var(--font-serif);
    font-size: clamp(1.65rem, 3vw, 2.1rem);
    font-weight: 500;
    line-height: var(--leading-tight);
    margin-bottom: 0.75rem;
    color: var(--ink);
}

.section-description {
    font-size: var(--text-base);
    line-height: var(--leading-relaxed);
    color: var(--ink-muted);
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 22px;
    margin-bottom: 2.75rem;
}

.featured-card {
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition:
        border-color var(--transition-fast),
        box-shadow var(--transition-fast);
    opacity: 0;
    animation: fadeInUp 0.5s ease forwards;
}

.featured-card:hover {
    border-color: var(--line-strong);
    box-shadow: var(--shadow);
}

.product-image-container {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.featured-card:hover .product-image {
    transform: scale(1.03);
}

.product-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.featured-card:hover .product-overlay {
    opacity: 1;
}

.btn-quick-view {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--white);
    color: var(--ink);
    padding: 10px 18px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--line-strong);
    text-decoration: none;
    font-size: var(--text-sm);
    font-weight: 500;
    transition: transform 0.25s ease, background 0.2s, color 0.2s;
    transform: translateY(12px);
}

.featured-card:hover .btn-quick-view {
    transform: translateY(0);
}

.btn-quick-view:hover {
    background: var(--accent);
    color: var(--white);
}

.btn-quick-view svg {
    width: 16px;
    height: 16px;
}

.product-info {
    padding: 24px;
}

.product-name {
    font-family: var(--font-serif);
    font-size: 1.1rem;
    font-weight: 500;
    color: var(--ink);
    margin-bottom: 8px;
    line-height: 1.35;
}

.product-category {
    font-size: 14px;
    color: var(--text-light);
    margin-bottom: 16px;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 12px;
}

.current-price {
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--ink);
}

.original-price {
    font-size: 16px;
    color: var(--text-light);
    text-decoration: line-through;
}

/* Section Footer */
.section-footer {
    text-align: center;
}

.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--accent);
    color: var(--white);
    padding: 14px 26px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--accent-deep);
    text-decoration: none;
    font-size: var(--text-sm);
    font-weight: 500;
    transition: background var(--transition-fast);
}

.btn-view-all:hover {
    background: var(--accent-deep);
}

.btn-view-all svg {
    width: 18px;
    height: 18px;
    transition: transform 0.3s ease;
}

.btn-view-all:hover svg {
    transform: translateX(4px);
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .premium-products {
        padding: 60px 0;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    
    .section-title {
        font-size: 32px;
    }
    
    .section-description {
        font-size: 16px;
    }
    
    .product-info {
        padding: 20px;
    }
    
    .product-name {
        font-size: 18px;
    }
    
    .current-price {
        font-size: 18px;
    }
}

/* RTL Support */
[dir="rtl"] .btn-view-all:hover svg {
    transform: translateX(-4px);
}

[dir="rtl"] .btn-quick-view svg {
    transform: scaleX(-1);
}
</style>

