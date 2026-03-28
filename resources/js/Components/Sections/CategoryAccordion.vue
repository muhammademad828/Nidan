<script setup>
import { useCms } from "@/Composables/useCms";
import { useRevealAnimation } from "@/Composables/useRevealAnimation";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    categories: { type: Array, default: () => [] },
});

const { t, getSection } = useCms();
const section = getSection("categories");

useRevealAnimation();

const catImages = {
    jewelry:
        "https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=800&q=80",
    watches:
        "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80",
    accessories:
        "https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&q=80",
    antiques:
        "https://images.unsplash.com/photo-1569503689348-c4f1dcd4b5c7?w=800&q=80",
    flowers:
        "https://images.unsplash.com/photo-1490750967868-88df5691cc7e?w=800&q=80",
};

function getCatImage(cat) {
    return cat.image_path || catImages[cat.slug] || "";
}
</script>

<template>
    <section class="categories">
        <div class="cat-header reveal">
            <p class="cat-eyebrow">
                {{
                    section?.subtitle ||
                    t("categories.eyebrow", "Curated Collections")
                }}
            </p>
            <h2 class="cat-title">
                {{ t("categories.title", "Shop by") }}
                <em>{{ t("categories.title_em", "Category") }}</em>
            </h2>
        </div>

        <div class="cat-track">
            <Link
                v-for="(cat, index) in categories"
                :key="cat.id"
                :href="route('products.index', { category: cat.slug })"
                :class="['cat-item', `reveal`, `d${index + 1}`]"
            >
                <div
                    class="cat-item-photo"
                    :style="{ backgroundImage: `url('${getCatImage(cat)}')` }"
                ></div>
                <div class="cat-item-grad"></div>
                <div class="cat-item-bar"></div>

                <!-- Shimmer dots -->
                <div class="cat-dots" aria-hidden="true">
                    <div class="cat-dot"></div>
                    <div class="cat-dot"></div>
                    <div class="cat-dot"></div>
                </div>

                <!-- Number badge -->
                <div class="cat-item-num">
                    {{ String(index + 1).padStart(2, "0") }}
                </div>

                <!-- Vertical label (collapsed) -->
                <div class="cat-item-vert">
                    <span class="cat-vert-label">{{ cat.name }}</span>
                </div>

                <!-- Expanded info panel -->
                <div class="cat-item-info">
                    <span class="cat-tag badge">{{
                        cat.tag || "Featured"
                    }}</span>
                    <div class="cat-name">{{ cat.name }}</div>
                    <p class="cat-desc">{{ cat.description }}</p>
                    <span class="cat-cta">
                        {{ t("categories.explore_cta", "Explore") }}
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            aria-hidden="true"
                        >
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
            </Link>
        </div>
    </section>
</template>

<style scoped>
.categories {
    background: var(--dark-section);
    padding: 90px 0;
    overflow: hidden;
}

.cat-header {
    text-align: center;
    margin-bottom: 52px;
    padding: 0 40px;
}

.cat-eyebrow {
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.35em;
    text-transform: uppercase;
    color: var(--gold-light);
    margin-bottom: 12px;
}

[dir="rtl"] .cat-eyebrow {
    letter-spacing: 0;
}

.cat-title {
    font-family: var(--font-serif);
    font-size: clamp(34px, 4vw, 54px);
    font-weight: 300;
    color: var(--white);
    letter-spacing: 0.01em;
}

.cat-title em {
    font-style: italic;
    color: var(--gold-light);
}

.cat-track {
    display: flex;
    padding: 0 60px;
    gap: 16px;
    max-width: 1240px;
    margin: 0 auto;
}

.cat-item {
    position: relative;
    flex: 1;
    min-width: 0;
    height: 500px;
    cursor: pointer;
    overflow: hidden;
    border-radius: 3px;
    transition: flex 0.7s var(--ease-expand);
    display: block;
}

.cat-track:has(.cat-item:hover) .cat-item:not(:hover) {
    flex: 0.4;
}
.cat-item:hover {
    flex: 2.4;
}

.cat-item-photo {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transition:
        transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94),
        filter 0.6s;
    filter: brightness(0.55) saturate(0.75);
}

.cat-item:hover .cat-item-photo {
    transform: scale(1.06);
    filter: brightness(0.7) saturate(1.05);
}

.cat-item-grad {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(0, 0, 0, 0.9) 0%,
        rgba(0, 0, 0, 0.4) 40%,
        rgba(0, 0, 0, 0.05) 75%,
        transparent 100%
    );
    transition: background 0.5s;
}

.cat-item:hover .cat-item-grad {
    background: linear-gradient(
        to top,
        rgba(0, 0, 0, 0.92) 0%,
        rgba(0, 0, 0, 0.5) 45%,
        rgba(0, 0, 0, 0.1) 78%,
        transparent 100%
    );
}

.cat-item-bar {
    position: absolute;
    top: 0;
    inset-inline: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    transform: scaleX(0);
    transform-origin: inline-start;
    transition: transform 0.55s var(--ease-luxury);
    z-index: 4;
}

.cat-item:hover .cat-item-bar {
    transform: scaleX(1);
}

.cat-item-num {
    position: absolute;
    top: 22px;
    inset-inline-start: 18px;
    font-family: var(--font-serif);
    font-size: 11px;
    color: rgba(255, 255, 255, 0.35);
    letter-spacing: 0.05em;
    z-index: 3;
    transition: color 0.3s;
}

.cat-item:hover .cat-item-num {
    color: var(--gold-light);
}

.cat-dots {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 2;
    overflow: hidden;
}

.cat-dot {
    position: absolute;
    width: 3px;
    height: 3px;
    border-radius: 50%;
    background: var(--gold-light);
    opacity: 0;
}

.cat-item:hover .cat-dot {
    animation: dotFloat 2.8s ease-in-out infinite;
}
.cat-dot:nth-child(1) {
    top: 22%;
    inset-inline-end: 18%;
    animation-delay: 0s;
}
.cat-dot:nth-child(2) {
    top: 38%;
    inset-inline-end: 28%;
    animation-delay: 0.6s;
}
.cat-dot:nth-child(3) {
    top: 16%;
    inset-inline-end: 35%;
    animation-delay: 1.2s;
}

.cat-item-vert {
    position: absolute;
    bottom: 0;
    inset-inline: 0;
    top: 0;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 36px;
    z-index: 3;
    transition: opacity 0.3s;
}

.cat-item:hover .cat-item-vert {
    opacity: 0;
    pointer-events: none;
}

.cat-vert-label {
    font-family: var(--font-serif);
    font-size: 14px;
    font-weight: 400;
    color: rgba(255, 255, 255, 0.85);
    letter-spacing: 0.1em;
    writing-mode: vertical-rl;
    transform: rotate(180deg);
}

[dir="rtl"] .cat-vert-label {
    writing-mode: vertical-lr;
    transform: rotate(0deg);
}

.cat-item-info {
    position: absolute;
    bottom: 0;
    inset-inline: 0;
    padding: 36px 30px 34px;
    z-index: 3;
    opacity: 0;
    transform: translateY(18px);
    transition:
        opacity 0.45s var(--ease-luxury) 0.06s,
        transform 0.45s var(--ease-luxury) 0.06s;
}

.cat-item:hover .cat-item-info {
    opacity: 1;
    transform: translateY(0);
}

.cat-tag {
    display: inline-block;
    margin-bottom: 14px;
}

.cat-name {
    font-family: var(--font-serif);
    font-size: 28px;
    font-weight: 400;
    color: var(--white);
    line-height: 1.2;
    margin-bottom: 10px;
}

.cat-desc {
    font-size: 12px;
    font-weight: 300;
    color: rgba(255, 255, 255, 0.6);
    line-height: 1.7;
    margin-bottom: 22px;
    max-width: 240px;
}

.cat-cta {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--white);
    border-bottom: 1px solid rgba(255, 255, 255, 0.35);
    padding-bottom: 3px;
    transition:
        gap 0.2s,
        color 0.2s,
        border-color 0.2s;
}

.cat-cta:hover {
    gap: 15px;
    color: var(--gold-light);
    border-color: var(--gold-light);
}
.cat-cta svg {
    width: 12px;
    height: 12px;
    transform: var(--arrow-transform);
}

@media (max-width: 1000px) {
    .cat-track {
        padding: 0 20px;
        gap: 10px;
    }
    .cat-item {
        height: 380px;
    }
}

@media (max-width: 700px) {
    .cat-track {
        flex-direction: column;
        padding: 0 20px;
    }
    .cat-item {
        height: 260px;
        flex: 1 !important;
    }
    .cat-item-vert {
        display: none;
    }
    .cat-item-info {
        opacity: 1;
        transform: none;
    }
}
</style>
