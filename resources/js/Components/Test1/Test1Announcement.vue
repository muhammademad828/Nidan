<script setup>
import { useCms } from "@/Composables/useCms";

const { t, getSetting } = useCms();
const text =
    getSetting("home", "announcement_text") ||
    t(
        "home.announcement",
        "Complimentary 2-Hour Delivery on Orders Over AED 500",
    );
</script>

<template>
    <div
        class="w-full bg-primary-container/30 px-4 py-2 text-center"
        role="region"
        aria-label="Announcement"
    >
        <p
            class="font-body text-[10px] uppercase tracking-[0.2em] text-on-primary-container md:text-xs"
        >
            {{ text }}
        </p>
    </div>
</template>
