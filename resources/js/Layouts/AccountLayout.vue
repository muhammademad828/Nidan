<script setup>
import { computed } from "vue";
import { Head } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";
import CartDrawer from "@/Components/Organisms/CartDrawer.vue";
import AccountDashboardHeader from "@/Components/Account/AccountDashboardHeader.vue";

const props = defineProps({
    user: { type: Object, required: true },
});

const { seo, getSetting, locale } = useCms();

const pageThemeStyle = computed(() => {
    const primary = getSetting("general", "primary_color");
    const secondary = getSetting("general", "secondary_color");
    const o = {};
    if (primary) o["--account-primary"] = primary;
    if (secondary) o["--account-surface"] = secondary;
    return o;
});
</script>

<template>
    <div
        class="account-layout font-body"
        :dir="locale === 'ar' ? 'rtl' : 'ltr'"
        :lang="locale"
        :style="pageThemeStyle"
    >
        <Head>
            <title>{{ getSetting("general", "site_name", "NIDAN") }} — Account</title>
            <meta
                name="description"
                :content="seo.meta_description || getSetting('general', 'site_description', '')"
            />
        </Head>

        <CartDrawer />
        <AccountDashboardHeader :user="user" />

        <div class="account-layout__body">
            <slot />
        </div>
    </div>
</template>

<style scoped>
.account-layout {
    --account-sidebar-w: 272px;
    --account-header-h: 3.5rem;
    position: relative;
    z-index: 1;
    isolation: isolate;
    min-height: 100vh;
    background: var(--account-surface, var(--cream-light, #fffcf7));
    color: var(--ink, #252320);
}

.account-layout__body {
    margin-top: var(--account-header-h);
    min-height: calc(100vh - var(--account-header-h));
}

@media (max-width: 1023px) {
    .account-layout {
        --account-sidebar-w: 0px;
    }
}
</style>
