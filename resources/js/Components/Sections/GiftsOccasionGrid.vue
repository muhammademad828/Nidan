<script setup>
import { useCms } from "@/Composables/useCms";
import { useRevealAnimation } from "@/Composables/useRevealAnimation";
import { Link } from "@inertiajs/vue3";

const { t, getSection } = useCms();
const section = getSection("gifts");

useRevealAnimation();

const occasions = [
    {
        key: "love",
        labelKey: "gifts.love",
        class: "gc-love",
        color: "linear-gradient(135deg,#c04050,#902030)",
    },
    {
        key: "birthday",
        labelKey: "gifts.birthday",
        class: "gc-bday",
        color: "linear-gradient(135deg,#d080a0,#b05080)",
    },
    {
        key: "graduation",
        labelKey: "gifts.graduation",
        class: "gc-grad",
        color: "linear-gradient(135deg,#8090a0,#506070)",
    },
    {
        key: "friendship",
        labelKey: "gifts.friendship",
        class: "gc-friend",
        color: "linear-gradient(135deg,#a8b870,#788840)",
    },
];
</script>

<template>
    <section class="gifts section" style="text-align: center">
        <h2 class="section-title reveal">
            {{ section?.title || t("gifts.title", "Gifts for Every Moment") }}
        </h2>

        <div class="gifts-grid">
            <Link
                v-for="(occ, i) in occasions"
                :key="occ.key"
                :href="route('products.index', { occasion: occ.key })"
                :class="['gift-item', 'reveal', `d${i + 1}`]"
            >
                <div class="gift-circle" :class="occ.class">
                    <div
                        :style="{
                            width: '100%',
                            height: '100%',
                            background: occ.color,
                        }"
                    />
                </div>
                <div class="gift-label">{{ t(occ.labelKey, occ.key) }}</div>
            </Link>
        </div>
    </section>
</template>

<style scoped>
.gifts {
    background: var(--white);
}

.gifts .section-title {
    margin-bottom: 50px;
}

.gift-item {
    cursor: pointer;
    display: block;
}

.gift-circle {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto 18px;
    transition:
        transform 0.4s var(--ease-luxury),
        box-shadow 0.4s;
}

.gift-item:hover .gift-circle {
    transform: scale(1.06);
    box-shadow: 0 12px 36px rgba(0, 0, 0, 0.15);
}

.gift-label {
    font-family: var(--font-serif);
    font-size: 18px;
    font-weight: 400;
    color: var(--dark);
}
</style>
