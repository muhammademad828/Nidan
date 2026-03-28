<script setup>
import { computed } from "vue";
import { Head } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";
import BrandMark from "@/Components/BrandMark.vue";

const { locale, getSetting } = useCms();
const isAr = computed(() => locale.value === "ar");
const siteName = computed(() => getSetting("general", "site_name", "NIDAN"));

function goStore(e) {
    e.preventDefault();
    window.location.href = "/";
}
</script>

<template>
    <div
        class="auth-layout min-h-screen bg-[#fffcf7] font-body text-[#252320]"
        :dir="isAr ? 'rtl' : 'ltr'"
        :lang="locale"
    >
        <Head>
            <title>{{ siteName }}</title>
        </Head>

        <header
            class="auth-layout-header sticky top-0 z-[60] flex items-center justify-between gap-4 border-b border-stone-200/60 bg-[#fffcf7]/95 px-4 py-3 backdrop-blur-md md:px-8"
        >
            <a
                href="/"
                class="auth-layout-brand inline-flex items-center font-headline text-xl italic tracking-tight text-primary"
                @click="goStore"
            >
                <BrandMark
                    :as-link="false"
                    img-class="h-7 w-auto max-w-[160px] object-contain md:h-8"
                    text-class="font-headline text-xl italic tracking-tight text-primary"
                />
            </a>
            <a
                href="/"
                class="font-body text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-600 transition-colors hover:text-primary"
                @click="goStore"
            >
                {{ isAr ? "العودة للمتجر" : "Back to store" }}
            </a>
        </header>

        <main class="auth-layout-main">
            <slot />
        </main>
    </div>
</template>

<style scoped>
.auth-layout-main {
    min-height: calc(100vh - 3.25rem);
}

.auth-layout-brand {
    text-decoration: none;
}
</style>
