<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";
import BrandMark from "@/Components/BrandMark.vue";

const { t, getSetting, locale } = useCms();
const year = new Date().getFullYear();
const isAr = computed(() => locale.value === "ar");
</script>

<template>
    <footer
        class="mt-24 flex w-full flex-col items-center justify-between gap-8 rounded-t-[3rem] bg-[#eae9de] px-8 py-12 text-primary md:flex-row md:px-16 dark:bg-stone-800 dark:text-[#c5a367]"
    >
        <div
            class="flex flex-col items-center gap-4 md:items-start"
        >
            <h4 class="font-headline text-2xl text-primary dark:text-[#c5a367]">
                <BrandMark
                    :as-link="false"
                    img-class="h-10 w-auto max-w-[min(220px,80vw)] object-contain md:h-11"
                    text-class="font-headline text-2xl text-primary dark:text-[#c5a367]"
                />
            </h4>
            <p
                class="font-body flex flex-wrap items-center justify-center gap-x-1 text-[10px] uppercase tracking-[0.2em] opacity-60 md:justify-start"
                :dir="isAr ? 'rtl' : 'ltr'"
            >
                <span>© {{ year }}</span>
                <BrandMark
                    inline
                    :as-link="false"
                    img-class="max-w-[4.5rem] object-contain opacity-90"
                />
                <span>{{ t("footer.rights_reserved", "All rights reserved.") }}</span>
            </p>
        </div>
        <div class="flex flex-wrap justify-center gap-8">
            <Link
                :href="route('products.index')"
                class="font-body text-xs uppercase tracking-[0.2em] text-stone-500 transition-all hover:text-primary dark:text-stone-400 dark:hover:text-white"
                >{{ t("footer.link_privacy", "Privacy Policy") }}</Link
            >
            <Link
                :href="route('products.index')"
                class="font-body text-xs uppercase tracking-[0.2em] text-stone-500 transition-all hover:text-primary dark:text-stone-400 dark:hover:text-white"
                >{{ t("footer.terms", "Terms of Service") }}</Link
            >
            <Link
                :href="route('products.index')"
                class="font-body text-xs uppercase tracking-[0.2em] text-stone-500 transition-all hover:text-primary dark:text-stone-400 dark:hover:text-white"
                >{{ t("footer.link_shipping", "Shipping & Returns") }}</Link
            >
        </div>
        <div class="flex items-center gap-6">
            <a
                :href="getSetting('social', 'instagram_url', '#')"
                class="opacity-80 transition-opacity hover:opacity-100"
                target="_blank"
                rel="noopener"
                aria-label="Instagram"
            >
                <span class="material-symbols-outlined text-xl"
                    >photo_camera</span
                >
            </a>
            <a
                :href="getSetting('social', 'facebook_url', '#')"
                class="opacity-80 transition-opacity hover:opacity-100"
                target="_blank"
                rel="noopener"
                aria-label="Social"
            >
                <span class="material-symbols-outlined text-xl"
                    >social_leaderboard</span
                >
            </a>
        </div>
    </footer>
</template>
