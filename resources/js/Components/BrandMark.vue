<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";

const props = defineProps({
    /** CSS classes for the <img> when logo is shown */
    imgClass: { type: String, default: "" },
    /** CSS classes for the text fallback */
    textClass: { type: String, default: "" },
    /** Smaller mark for inline use inside a sentence */
    inline: { type: Boolean, default: false },
    /** Wrap in Link to home (disable when already inside a link) */
    asLink: { type: Boolean, default: true },
});

const { getSetting, brandLogoSrc } = useCms();

const alt = computed(() => getSetting("general", "site_name", "Nidan"));
const src = computed(() => brandLogoSrc.value);

const defaultImgClass = computed(() =>
    props.inline
        ? "inline-block h-[1.2em] w-auto max-w-[6rem] align-[-0.25em] object-contain mx-0.5"
        : "h-8 w-auto object-contain md:h-9",
);

const mergedImgClass = computed(() =>
    [defaultImgClass.value, props.imgClass].filter(Boolean).join(" "),
);
</script>

<template>
    <Link
        v-if="asLink && src"
        :href="route('home')"
        class="inline-flex items-center"
    >
        <img :src="src" :alt="alt" :class="mergedImgClass" loading="lazy" decoding="async" />
    </Link>
    <img
        v-else-if="src"
        :src="src"
        :alt="alt"
        :class="mergedImgClass"
        loading="lazy"
        decoding="async"
    />
    <span v-else :class="textClass">{{ alt }}</span>
</template>
