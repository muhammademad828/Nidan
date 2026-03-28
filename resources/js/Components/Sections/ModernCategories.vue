<script setup>
import { Link } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";

const props = defineProps({
    categories: { type: Array, default: () => [] },
});

const { t } = useCms();

function categoryInitial(name) {
    if (!name || typeof name !== "string") return "—";
    const trimmed = name.trim();
    return trimmed.slice(0, 1) || "—";
}
</script>

<template>
    <section class="cat-section">
        <div class="cat-wrap">
            <header class="cat-header">
                <p class="cat-eyebrow">
                    {{ t("categories.eyebrow", "تسوق حسب الفئة") }}
                </p>
                <h2 class="cat-title">
                    {{ t("categories.title", "فئات واضحة، بدون ضجيج") }}
                </h2>
                <p class="cat-desc">
                    {{
                        t(
                            "categories.desc",
                            "نظمنا المنتجات كما تفعل المتاجر الحقيقية — لتجد ما تبحث عنه بسرعة.",
                        )
                    }}
                </p>
            </header>

            <div class="cat-grid">
                <Link
                    v-for="category in categories.slice(0, 8)"
                    :key="category.id"
                    :href="route('products.index', { category: category.slug })"
                    class="cat-card"
                >
                    <span class="cat-initial" aria-hidden="true">{{
                        categoryInitial(category.name)
                    }}</span>
                    <span class="cat-name">{{ category.name }}</span>
                    <span class="cat-count"
                        >{{ category.products_count ?? "—" }}
                        {{ t("categories.products", "منتج") }}</span
                    >
                    <span class="cat-arrow" aria-hidden="true">→</span>
                </Link>
            </div>

            <div class="cat-spotlight">
                <div class="cat-spotlight-text">
                    <p class="cat-spotlight-label">
                        {{ t("categories.spotlight_label", "اختيار الفريق") }}
                    </p>
                    <h3 class="cat-spotlight-title">
                        {{ t("categories.spotlight_title", "مجموعة موسمية") }}
                    </h3>
                    <p class="cat-spotlight-desc">
                        {{
                            t(
                                "categories.spotlight_desc",
                                "قطع اخترناها للمناسبات اليومية — جودة ثابتة، أسعار معقولة.",
                            )
                        }}
                    </p>
                    <Link
                        :href="route('products.index')"
                        class="cat-spotlight-link"
                    >
                        {{ t("categories.spotlight_cta", "اطلع عليها") }}
                    </Link>
                </div>
                <div class="cat-spotlight-img">
                    <img
                        src="https://images.unsplash.com/photo-1490474418585-ba9bad8fd0ea?w=640&q=82"
                        alt=""
                        width="640"
                        height="480"
                    />
                </div>
            </div>
        </div>
    </section>
</template>
<style scoped>
.cat-section {
    padding: var(--section-padding-block) var(--section-padding-inline);
    background: var(--paper);
    border-bottom: 1px solid var(--line);
}

.cat-wrap {
    max-width: var(--max-width-ultra);
    margin: 0 auto;
}

.cat-header {
    max-width: 36em;
    margin-bottom: 2.75rem;
}

.cat-eyebrow {
    font-size: var(--text-xs);
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--ink-muted);
    margin-bottom: 0.75rem;
}

[dir="rtl"] .cat-eyebrow {
    letter-spacing: 0.06em;
}

.cat-title {
    font-family: var(--font-serif);
    font-size: clamp(1.65rem, 3vw, 2.1rem);
    font-weight: 500;
    color: var(--ink);
    line-height: var(--leading-tight);
    margin-bottom: 0.75rem;
}

.cat-desc {
    font-size: var(--text-base);
    line-height: var(--leading-relaxed);
    color: var(--ink-muted);
}

.cat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 14px;
    margin-bottom: 3rem;
}

.cat-card {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 1.25rem 1.1rem;
    text-decoration: none;
    color: var(--ink);
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    transition:
        border-color var(--transition-fast),
        box-shadow var(--transition-fast);
    min-height: 140px;
}

.cat-card:hover {
    border-color: var(--line-strong);
    box-shadow: var(--shadow);
}

.cat-initial {
    font-family: var(--font-serif);
    font-size: 1.75rem;
    font-weight: 500;
    color: var(--accent);
    line-height: 1;
    margin-bottom: 0.65rem;
}

.cat-name {
    font-family: var(--font-serif);
    font-size: 1.05rem;
    font-weight: 500;
    margin-bottom: 0.35rem;
}

.cat-count {
    font-size: var(--text-xs);
    color: var(--ink-faint);
}

.cat-arrow {
    position: absolute;
    bottom: 1rem;
    inset-inline-end: 1rem;
    font-size: 1rem;
    color: var(--ink-faint);
    transition: transform var(--transition-fast);
}

[dir="rtl"] .cat-arrow {
    transform: scaleX(-1);
}

.cat-card:hover .cat-arrow {
    transform: translateX(3px);
}

[dir="rtl"] .cat-card:hover .cat-arrow {
    transform: scaleX(-1) translateX(-3px);
}

.cat-spotlight {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    border: 1px solid var(--line-strong);
    border-radius: var(--radius-lg);
    overflow: hidden;
    background: var(--white);
    box-shadow: var(--shadow-sm);
}

.cat-spotlight-text {
    padding: clamp(1.75rem, 4vw, 2.5rem);
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-inline-end: 1px solid var(--line);
}

[dir="rtl"] .cat-spotlight-text {
    border-inline-end: none;
    border-inline-start: 1px solid var(--line);
}

.cat-spotlight-label {
    font-size: var(--text-xs);
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--ink-faint);
    margin-bottom: 0.5rem;
}

.cat-spotlight-title {
    font-family: var(--font-serif);
    font-size: clamp(1.35rem, 2.5vw, 1.65rem);
    font-weight: 500;
    margin-bottom: 0.65rem;
    color: var(--ink);
}

.cat-spotlight-desc {
    font-size: var(--text-sm);
    line-height: var(--leading-relaxed);
    color: var(--ink-muted);
    margin-bottom: 1.25rem;
    max-width: 32em;
}

.cat-spotlight-link {
    align-self: flex-start;
    font-size: var(--text-sm);
    font-weight: 500;
    color: var(--accent);
    text-decoration: underline;
    text-underline-offset: 3px;
}

.cat-spotlight-link:hover {
    color: var(--accent-deep);
}

.cat-spotlight-img {
    min-height: 220px;
}

.cat-spotlight-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

@media (max-width: 768px) {
    .cat-spotlight {
        grid-template-columns: 1fr;
    }

    .cat-spotlight-text {
        border-inline-end: none;
        border-inline-start: none;
        border-bottom: 1px solid var(--line);
        order: 2;
    }

    .cat-spotlight-img {
        min-height: 200px;
        order: 1;
    }
}
</style>

