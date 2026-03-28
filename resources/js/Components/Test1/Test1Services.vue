<script setup>
import { Link } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";

const { t } = useCms();
</script>

<template>
    <section
        id="services"
        class="bg-surface-container-low px-8 py-24 md:px-24"
    >
        <div class="container mx-auto">
            <div
                class="mb-16 flex flex-col items-end justify-between gap-6 md:flex-row"
            >
                <div>
                    <span
                        class="mb-4 block font-body text-xs uppercase tracking-[0.3em] text-primary"
                        >{{ t("test1.services_eyebrow", "Our Expertise") }}</span
                    >
                    <h3
                        class="font-headline text-5xl text-on-surface md:text-6xl"
                    >
                        {{ t("test1.services_title", "Event Planning") }}
                    </h3>
                </div>
                <p
                    class="mb-2 max-w-sm font-body text-on-surface-variant"
                >
                    {{
                        t(
                            "test1.services_lead",
                            "From intimate gatherings to grand celebrations, we weave botanical stories that resonate.",
                        )
                    }}
                </p>
            </div>
            <div
                class="relative grid grid-cols-1 gap-8 md:grid-cols-3 md:gap-4"
            >
                <div class="group relative pt-12">
                    <div
                        class="organic-blob-2 flex aspect-square flex-col items-center justify-center bg-surface-container-highest p-12 text-center transition-all duration-500 group-hover:bg-primary-container/40"
                    >
                        <span
                            class="material-symbols-outlined mb-6 text-4xl text-primary"
                            >auto_awesome</span
                        >
                        <h4 class="mb-4 font-headline text-3xl">
                            {{
                                t(
                                    "test1.service_1_title",
                                    "Kath Ktab Napkins",
                                )
                            }}
                        </h4>
                        <p
                            class="px-4 font-body text-sm text-on-surface-variant"
                        >
                            {{
                                t(
                                    "test1.service_1_desc",
                                    "Custom embroidered artisanal linens for your sacred moments.",
                                )
                            }}
                        </p>
                        <Link
                            :href="route('products.index')"
                            class="mt-8 border-b border-outline-variant pb-1 font-body text-[10px] uppercase tracking-widest"
                            >{{ t("test1.service_1_link", "Enquire") }}</Link
                        >
                    </div>
                </div>
                <div class="group relative md:-mt-12">
                    <div
                        class="organic-blob-1 flex aspect-square flex-col items-center justify-center border-8 border-surface-container-low bg-surface-container-highest p-12 text-center transition-all duration-500 group-hover:bg-primary-container/40"
                    >
                        <span
                            class="material-symbols-outlined mb-6 text-4xl text-primary"
                            >celebration</span
                        >
                        <h4 class="mb-4 font-headline text-3xl">
                            {{ t("test1.service_2_title", "Kosha") }}
                        </h4>
                        <p
                            class="px-4 font-body text-sm text-on-surface-variant"
                        >
                            {{
                                t(
                                    "test1.service_2_desc",
                                    "Bespoke stage designs that capture the essence of your union.",
                                )
                            }}
                        </p>
                        <Link
                            :href="route('products.index')"
                            class="mt-8 border-b border-outline-variant pb-1 font-body text-[10px] uppercase tracking-widest"
                            >{{ t("test1.service_2_link", "Discover") }}</Link
                        >
                    </div>
                </div>
                <div class="group relative pt-12 md:pt-24">
                    <div
                        class="organic-blob-3 flex aspect-square flex-col items-center justify-center bg-surface-container-highest p-12 text-center transition-all duration-500 group-hover:bg-primary-container/40"
                    >
                        <span
                            class="material-symbols-outlined mb-6 text-4xl text-primary"
                            >local_florist</span
                        >
                        <h4 class="mb-4 font-headline text-3xl">
                            {{
                                t(
                                    "test1.service_3_title",
                                    "Engagement Flowers",
                                )
                            }}
                        </h4>
                        <p
                            class="px-4 font-body text-sm text-on-surface-variant"
                        >
                            {{
                                t(
                                    "test1.service_3_desc",
                                    "Curated floral arrangements designed for new beginnings.",
                                )
                            }}
                        </p>
                        <Link
                            :href="route('products.index')"
                            class="mt-8 border-b border-outline-variant pb-1 font-body text-[10px] uppercase tracking-widest"
                            >{{
                                t("test1.service_3_link", "View Collection")
                            }}</Link
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
