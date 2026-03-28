<script setup>
import { useCms } from "@/Composables/useCms";
import { useRevealAnimation } from "@/Composables/useRevealAnimation";

const { t, getSection } = useCms();
const section = getSection("instagram");
useRevealAnimation();

const mockImages = [
    { gradient: "linear-gradient(135deg,#3a3020,#2a2010)" },
    { gradient: "linear-gradient(135deg,#e8c8b8,#d8b8a8)" },
    { gradient: "linear-gradient(135deg,#384858,#283848)" },
    { gradient: "linear-gradient(135deg,#f0e4cc,#e0d4bc)" },
];
</script>

<template>
    <section class="instagram">
        <h2 class="instagram-handle reveal">
            {{ section?.title || t("instagram.handle", "@NidanGifts") }}
        </h2>
        <p class="instagram-sub reveal d1">
            {{ section?.subtitle || t("instagram.subtitle") }}
        </p>

        <div class="insta-grid">
            <div
                v-for="(img, i) in mockImages"
                :key="i"
                class="insta-item"
                role="img"
                :aria-label="`Instagram post ${i + 1}`"
            >
                <div
                    :style="{
                        width: '100%',
                        height: '100%',
                        background: img.gradient,
                    }"
                ></div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.instagram {
    background: var(--cream-light);
    padding: 80px 0 60px;
    text-align: center;
}

.instagram-handle {
    font-family: var(--font-serif);
    font-size: 28px;
    font-weight: 400;
    color: var(--dark);
    margin-bottom: 8px;
}

.instagram-sub {
    font-size: 13px;
    font-weight: 300;
    color: var(--text-light);
    margin-bottom: 32px;
}

.insta-item {
    aspect-ratio: 1;
    overflow: hidden;
    cursor: pointer;
    position: relative;
}

.insta-item::after {
    content: "";
    position: absolute;
    inset: 0;
    background: transparent;
    transition: background 0.2s;
}

.insta-item:hover::after {
    background: rgba(0, 0, 0, 0.15);
}
</style>
