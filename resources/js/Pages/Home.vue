<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Test1Hero from "@/Components/Test1/Test1Hero.vue";
import Test1Services from "@/Components/Test1/Test1Services.vue";
import Test1BestSellers from "@/Components/Test1/Test1BestSellers.vue";

defineProps({
    featuredProducts: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    bundles: { type: Array, default: () => [] },
});
</script>

<template>
    <AppLayout>
        <Test1Hero />
        <Test1Services />
        <Test1BestSellers :products="featuredProducts" />
    </AppLayout>
</template>
