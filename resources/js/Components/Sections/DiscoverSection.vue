<script setup>
import { useCms } from "@/Composables/useCms";
import { useRevealAnimation } from "@/Composables/useRevealAnimation";
import { Link } from "@inertiajs/vue3";

const { t, getSection } = useCms();
const section = getSection("discover");
useRevealAnimation();
</script>

<template>
    <div class="discover-wrap">
        <!-- Minimal label header -->
        <div class="discover-header reveal">
            <p class="discover-eyebrow">
                {{ section?.subtitle || t("discover.subtitle") }}
            </p>
            <h2 class="discover-heading">
                {{ section?.title || t("discover.title", "Discover the Essence") }}
            </h2>
        </div>

        <!-- Split cards -->
        <div class="discover-cards">
            <Link
                :href="route('products.index', { category: 'flowers' })"
                class="discover-card dc-flowers"
            >
                <div class="discover-card-bg"></div>
                <div class="discover-card-overlay"></div>
                <div class="discover-card-content">
                    <span class="discover-card-label">{{ t("discover.flowers", "Flowers") }}</span>
                    <div class="discover-card-line"></div>
                    <span class="discover-card-cta">{{ t("discover.explore_cta", "Explore Collection") }} →</span>
                </div>
            </Link>

            <Link
                :href="route('products.index')"
                class="discover-card dc-gifts"
            >
                <div class="discover-card-bg"></div>
                <div class="discover-card-overlay"></div>
                <div class="discover-card-content">
                    <span class="discover-card-label">{{ t("discover.gifts", "Gifts & Accessories") }}</span>
                    <div class="discover-card-line"></div>
                    <span class="discover-card-cta">{{ t("discover.explore_cta", "Explore Collection") }} →</span>
                </div>
            </Link>
        </div>
    </div>
</template>

<style scoped>
.discover-wrap {
    background: var(--white);
}

/* Tight header — no heavy section padding */
.discover-header {
    text-align: center;
    padding: 64px 24px 40px;
}

.discover-eyebrow {
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.32em;
    text-transform: uppercase;
    color: var(--text-light);
    margin-bottom: 12px;
}

[dir="rtl"] .discover-eyebrow { letter-spacing: 0; }

.discover-heading {
    font-family: var(--font-serif);
    font-size: clamp(28px, 3.5vw, 44px);
    font-weight: 300;
    color: var(--dark);
    letter-spacing: 0.01em;
}

/* Split expand cards */
.discover-cards {
    display: flex;
    width: 100%;
    height: 520px;
    overflow: hidden;
}

.discover-card {
    flex: 1;
    position: relative;
    cursor: pointer;
    overflow: hidden;
    transition: flex 0.65s var(--ease-expand);
    display: block;
}

.discover-card:hover { flex: 1.6; }
.discover-cards:has(.discover-card:hover) .discover-card:not(:hover) { flex: 0.4; }

.discover-card-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transition: transform 0.8s var(--ease-expand);
}
.discover-card:hover .discover-card-bg { transform: scale(1.06); }

.dc-flowers .discover-card-bg {
    background-image: url("https://images.unsplash.com/photo-1487530811015-780b31c3965e?w=1200&q=80");
}
.dc-gifts .discover-card-bg {
    background-image: url("https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=1200&q=80");
}

.discover-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.68) 0%, rgba(0,0,0,0.1) 55%, transparent 100%);
    transition: background 0.5s;
}
.discover-card:hover .discover-card-overlay {
    background: linear-gradient(to top, rgba(0,0,0,0.78) 0%, rgba(0,0,0,0.22) 60%, transparent 100%);
}

.discover-card-content {
    position: absolute;
    bottom: 52px;
    inset-inline: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    z-index: 2;
}

.discover-card-label {
    font-family: var(--font-serif);
    font-size: 38px;
    font-weight: 300;
    color: var(--white);
    text-shadow: 0 2px 24px rgba(0,0,0,0.3);
    transition: transform 0.4s var(--ease-luxury);
}
.discover-card:hover .discover-card-label { transform: translateY(-6px); }

.discover-card-line {
    width: 0;
    height: 1px;
    background: rgba(255,255,255,0.55);
    transition: width 0.55s var(--ease-luxury);
}
.discover-card:hover .discover-card-line { width: 52px; }

.discover-card-cta {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: rgba(255,255,255,0);
    transition: color 0.4s, transform 0.4s;
    transform: translateY(10px);
}
[dir="rtl"] .discover-card-cta { letter-spacing: 0; }
.discover-card:hover .discover-card-cta {
    color: rgba(255,255,255,0.92);
    transform: translateY(0);
}

@media (max-width: 900px) { .discover-cards { height: 400px; } }
@media (max-width: 640px) {
    .discover-cards { height: auto; flex-direction: column; }
    .discover-card { flex: none !important; height: 260px; }
    .discover-header { padding: 48px 20px 28px; }
}
</style>
