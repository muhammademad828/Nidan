<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";
import { useRevealAnimation } from "@/Composables/useRevealAnimation";

const { t, getSection, locale } = useCms();
const section = getSection("heritage");

useRevealAnimation();

const stats = computed(() => {
    const raw = section?.extra_data;
    // Ensure raw is an array before mapping
    if (!Array.isArray(raw) || !raw.length)
        return [
            {
                stat: "3,000+",
                label: t("heritage.stat_years", "Years of History"),
            },
            { stat: "48", label: t("heritage.stat_pieces", "Unique Pieces") },
            { stat: "18k", label: t("heritage.stat_gold", "Gold Crafted") },
        ];
    return raw.map((s) => ({
        stat: locale.value === "ar" ? s.stat_ar : s.stat_en,
        label: locale.value === "ar" ? s.label_ar : s.label_en,
    }));
});
</script>

<template>
    <section id="craft" class="heritage">
        <div
            class="heritage-photo-bg"
            :style="
                section?.background_image
                    ? { backgroundImage: `url('${section.background_image}')` }
                    : {}
            "
        ></div>
        <div class="heritage-photo-overlay"></div>

        <div class="heritage-content">
            <div class="heritage-text-panel reveal-start">
                <p class="heritage-eyebrow">
                    {{
                        section?.subtitle ||
                        t("heritage.eyebrow", "Heritage Collection")
                    }}
                </p>
                <h2 class="heritage-title">
                    {{
                        section?.title ||
                        t("heritage.title", "Inspired by Ancient Egypt")
                    }}
                </h2>

                <div class="heritage-stats">
                    <div v-for="stat in stats" :key="stat.stat">
                        <div class="heritage-stat-num">{{ stat.stat }}</div>
                        <div class="heritage-stat-label">{{ stat.label }}</div>
                    </div>
                </div>

                <p class="heritage-desc">
                    {{ section?.description || t("heritage.description") }}
                </p>

                <Link
                    :href="
                        section?.button_url ||
                        route('products.index', { category: 'antiques' })
                    "
                    class="heritage-link"
                >
                    {{
                        section?.button_text ||
                        t("heritage.cta", "Explore the Legacy")
                    }}
                    →
                </Link>
            </div>
        </div>
    </section>
</template>
<style scoped>
.heritage {
    width: 100%;
    position: relative;
    min-height: 560px;
    overflow: hidden;
    display: flex;
    align-items: stretch;
}

.heritage-photo-bg {
    position: absolute;
    inset: 0;
    background-image: url("https://images.unsplash.com/photo-1577741314755-048d8525d31e?w=1600&q=80");
    background-size: cover;
    background-position: center 40%;
    transform: scale(1.04);
    transition: transform 8s ease;
}

.heritage:hover .heritage-photo-bg {
    transform: scale(1.08);
}

.heritage-photo-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        105deg,
        rgba(12, 10, 6, 0.9) 0%,
        rgba(12, 10, 6, 0.65) 45%,
        rgba(12, 10, 6, 0.18) 100%
    );
}

[dir="rtl"] .heritage-photo-overlay {
    background: linear-gradient(
        255deg,
        rgba(12, 10, 6, 0.9) 0%,
        rgba(12, 10, 6, 0.65) 45%,
        rgba(12, 10, 6, 0.18) 100%
    );
}

.heritage-content {
    position: relative;
    z-index: 2;
    max-width: var(--max-width);
    width: 100%;
    margin: 0 auto;
    padding: 90px 40px;
    display: flex;
    align-items: center;
}

.heritage-text-panel {
    max-width: 480px;
}

.heritage-eyebrow {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: var(--gold-light);
    margin-bottom: 18px;
}

[dir="rtl"] .heritage-eyebrow {
    letter-spacing: 0;
}

.heritage-title {
    font-family: var(--font-serif);
    font-size: clamp(36px, 4vw, 54px);
    font-weight: 400;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 20px;
}

.heritage-stats {
    display: flex;
    gap: 32px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.heritage-stat-num {
    font-family: var(--font-serif);
    font-size: 28px;
    font-weight: 400;
    color: var(--gold-light);
    line-height: 1;
}

.heritage-stat-label {
    font-size: 10px;
    letter-spacing: 0.1em;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    margin-top: 2px;
}

.heritage-desc {
    font-size: 14px;
    font-weight: 300;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.8;
    margin-bottom: 36px;
}

.heritage-link {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold-light);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid rgba(184, 150, 62, 0.4);
    padding-bottom: 4px;
    transition:
        gap 0.2s,
        border-color 0.2s;
}

.heritage-link:hover {
    gap: 16px;
    border-color: var(--gold-light);
}
</style>

