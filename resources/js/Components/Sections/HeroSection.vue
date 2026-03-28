<script setup>
import { computed } from "vue";
import { useCms } from "@/Composables/useCms";
import { Link } from "@inertiajs/vue3";
import BrandMark from "@/Components/BrandMark.vue";

const { t, getSection, locale } = useCms();
const hero = getSection("hero");
const isAr = computed(() => locale.value === "ar");
</script>

<template>
    <section class="hero-editorial">
        <div class="hero-inner">
            <div class="hero-copy">
                <p
                    class="hero-kicker flex flex-wrap items-center gap-x-1.5"
                    :dir="isAr ? 'rtl' : 'ltr'"
                >
                    <span>{{ t("hero.eyebrow", "Welcome to") }}</span>
                    <BrandMark inline :as-link="false" />
                </p>
                <h1 class="hero-headline">
                    <span class="hero-line">{{
                        t("hero.title_line1", "فن الهدايا")
                    }}</span>
                    <span class="hero-line hero-line--accent">{{
                        t("hero.title_line2", "بلمسة هادئة")
                    }}</span>
                </h1>
                <p class="hero-lead">
                    {{
                        hero?.subtitle ||
                        t(
                            "hero.subtitle",
                            "قطع نختارها بعناية، ونصوغها بلغة بسيطة — بعيداً عن الصخب والزخرفة الفارغة.",
                        )
                    }}
                </p>
                <div class="hero-cta-row">
                    <Link
                        :href="hero?.button_url || route('products.index')"
                        class="hero-btn hero-btn--primary"
                    >
                        {{
                            hero?.button_text ||
                            t("hero.btn_primary", "تصفح المتجر")
                        }}
                    </Link>
                    <Link
                        :href="route('products.index')"
                        class="hero-btn hero-btn--quiet"
                    >
                        {{ t("hero.btn_secondary", "المجموعات") }}
                    </Link>
                </div>
                <ul class="hero-notes">
                    <li>{{ t("hero.note_ship", "شحن موثوق") }}</li>
                    <li>{{ t("hero.note_pack", "تغليف بسيط أنيق") }}</li>
                    <li>{{ t("hero.note_pick", "اختيار يدوي") }}</li>
                </ul>
            </div>
            <figure class="hero-figure">
                <div class="hero-frame">
                    <img
                        src="https://images.unsplash.com/photo-1607032071602-db2927858e91?w=720&q=82"
                        alt=""
                        class="hero-photo"
                        width="720"
                        height="900"
                    />
                </div>
                <figcaption class="hero-caption">
                    {{ t("hero.caption", "من استوديوهات صغيرة وورش نثق بها") }}
                </figcaption>
            </figure>
        </div>
    </section>
</template>
<style scoped>
.hero-editorial {
    margin-top: 0;
    padding: clamp(48px, 8vw, 88px) var(--section-padding-inline)
        clamp(56px, 10vw, 100px);
    background: var(--paper-bright);
    border-bottom: 1px solid var(--line);
}

.hero-inner {
    max-width: var(--max-width-ultra);
    margin: 0 auto;
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(260px, 42%);
    gap: clamp(32px, 6vw, 72px);
    align-items: end;
}

.hero-kicker {
    font-size: var(--text-xs);
    font-weight: 600;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--ink-muted);
    margin-bottom: 1rem;
    max-width: 28em;
}

[dir="rtl"] .hero-kicker {
    letter-spacing: 0.08em;
}

.hero-headline {
    font-family: var(--font-serif);
    font-weight: 500;
    font-size: clamp(2.25rem, 5vw, 3.35rem);
    line-height: 1.12;
    color: var(--ink);
    margin-bottom: 1.25rem;
}

.hero-line {
    display: block;
}

.hero-line--accent {
    color: var(--accent);
    font-style: italic;
    font-weight: 400;
}

.hero-lead {
    font-size: clamp(1rem, 1.35vw, 1.1rem);
    line-height: var(--leading-relaxed);
    color: var(--ink-muted);
    max-width: 32em;
    margin-bottom: 2rem;
}

.hero-cta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 12px 20px;
    margin-bottom: 2rem;
    align-items: center;
}

.hero-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 46px;
    padding: 0 1.35rem;
    font-size: var(--text-sm);
    font-weight: 500;
    text-decoration: none;
    border-radius: var(--radius-sm);
    transition:
        background var(--transition-fast),
        color var(--transition-fast),
        border-color var(--transition-fast);
}

.hero-btn--primary {
    background: var(--accent);
    color: var(--white);
    border: 1px solid var(--accent-deep);
}

.hero-btn--primary:hover {
    background: var(--accent-deep);
}

.hero-btn--quiet {
    background: transparent;
    color: var(--ink);
    border: 1px solid var(--line-strong);
}

.hero-btn--quiet:hover {
    border-color: var(--accent);
    color: var(--accent);
}

.hero-notes {
    list-style: none;
    display: flex;
    flex-wrap: wrap;
    gap: 8px 20px;
    font-size: var(--text-xs);
    color: var(--ink-faint);
}

.hero-notes li {
    position: relative;
    padding-inline-start: 14px;
}

.hero-notes li::before {
    content: "";
    position: absolute;
    inset-inline-start: 0;
    top: 0.45em;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--accent);
    opacity: 0.65;
}

.hero-figure {
    margin: 0;
}

.hero-frame {
    border: 1px solid var(--line-strong);
    padding: 6px;
    background: var(--white);
    box-shadow: var(--shadow-md);
    transform: rotate(-1.25deg);
    transition: transform var(--transition-med);
}

.hero-editorial:hover .hero-frame {
    transform: rotate(0deg);
}

.hero-photo {
    width: 100%;
    height: auto;
    display: block;
    aspect-ratio: 4 / 5;
    object-fit: cover;
    filter: contrast(1.02) saturate(0.96);
}

.hero-caption {
    margin-top: 12px;
    font-size: var(--text-xs);
    color: var(--ink-faint);
    line-height: var(--leading-normal);
    max-width: 28em;
}

@media (max-width: 900px) {
    .hero-inner {
        grid-template-columns: 1fr;
        align-items: start;
    }

    .hero-figure {
        order: -1;
        max-width: 420px;
        margin-inline: auto;
    }

    .hero-frame {
        transform: none;
    }

    .hero-editorial:hover .hero-frame {
        transform: none;
    }
}

[dir="rtl"] .hero-inner {
    direction: rtl;
}
</style>

