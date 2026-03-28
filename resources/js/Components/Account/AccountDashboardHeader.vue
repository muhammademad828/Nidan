<script setup>
import { computed } from "vue";
import { useCms } from "@/Composables/useCms";

const props = defineProps({
    user: { type: Object, required: true },
});

const { locale } = useCms();
const isAr = computed(() => locale.value === "ar");

const initial = computed(() => {
    const n = props.user?.name?.trim() || "?";
    return n.charAt(0).toUpperCase();
});

/** Full document navigation — leaves dashboard shell and avoids Inertia/nested-frame issues. */
function goHome(e) {
    e.preventDefault();
    window.location.href = "/";
}
</script>

<template>
    <header class="account-dash-header" role="banner">
        <a
            href="/"
            class="account-dash-back font-body text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-600 transition-colors hover:text-primary"
            @click="goHome"
        >
            <span class="material-symbols-outlined account-dash-back-icon" aria-hidden="true"
                >arrow_back</span
            >
            {{ isAr ? "العودة للرئيسية" : "Back to home" }}
        </a>
        <div class="account-dash-user">
            <span class="account-dash-avatar" aria-hidden="true">{{ initial }}</span>
            <span class="account-dash-name font-body text-sm font-medium text-stone-800">{{
                user.name
            }}</span>
        </div>
    </header>
</template>

<style scoped>
.account-dash-header {
    position: fixed;
    top: 0;
    inset-inline-end: 0;
    inset-inline-start: var(--account-sidebar-w, 272px);
    z-index: 55;
    display: flex;
    height: var(--account-header-h, 3.5rem);
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding-inline: clamp(1rem, 3vw, 1.75rem);
    border-bottom: 1px solid rgba(58, 53, 48, 0.08);
    background: rgba(255, 252, 247, 0.92);
    backdrop-filter: blur(10px);
}

.account-dash-back {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    text-decoration: none;
}

[dir="rtl"] .account-dash-back-icon {
    transform: scaleX(-1);
}

.account-dash-back-icon {
    font-size: 1.125rem;
    font-variation-settings: "FILL" 0, "wght" 400;
}

.account-dash-user {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    min-width: 0;
}

.account-dash-avatar {
    display: flex;
    height: 2.25rem;
    width: 2.25rem;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    border: 1px solid color-mix(in srgb, var(--account-primary, #6b4c3b) 35%, transparent);
    font-family: "Cormorant Garamond", Georgia, serif;
    font-size: 1rem;
    font-weight: 500;
    color: var(--account-primary, var(--accent, #6b4c3b));
    background: color-mix(in srgb, var(--account-primary, #6b4c3b) 8%, transparent);
}

.account-dash-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 1023px) {
    .account-dash-header {
        inset-inline-start: 0;
    }
}
</style>
