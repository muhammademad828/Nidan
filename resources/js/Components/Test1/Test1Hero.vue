<script setup>
import { Link } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";

const { t, getSection } = useCms();
const hero = getSection("hero");
const img =
    hero?.image_url ||
    "https://lh3.googleusercontent.com/aida-public/AB6AXuBv-3934KxHY_FfyGBXkCTJ0_rHYbl-PVHmJCIpfJvhN07GOpGCsSF3SC2F8hG7FhPiU_fzbwsar2h0gPfVYMAakqXErtYsk1PokCjG4LS_o5EKFksv0rvodws_vbupVkACzw2BPSBvkoUGIeru-FGrUNYqrVji5imEvZ60-mELKhsHPh7eJ0VUjWceJIywZ9mMTaLR9RLkDkzuX2kQPxBn_aobbOtuFk0p5h8f2KRyVM5CB0M2VZtr5fmuUJAXtBrmnw9Eig9QEYvx";
</script>

<template>
    <section
        class="relative flex min-h-[min(921px,90vh)] items-center overflow-hidden px-8 md:px-24"
    >
        <div
            class="container mx-auto grid grid-cols-1 items-center gap-12 lg:grid-cols-2"
        >
            <div class="z-10 max-w-xl">
                <h2
                    class="font-headline text-5xl leading-[0.9] tracking-[-0.06em] text-on-surface md:text-7xl lg:text-8xl"
                >
                    <span class="block">{{
                        t("test1.hero_title_a", "Bespoke")
                    }}</span>
                    <span
                        class="block font-light italic text-primary"
                        >{{ t("test1.hero_title_b", "Blooms") }}</span
                    >
                    <span class="block">{{
                        t("test1.hero_title_c", "& Custom Creations")
                    }}</span>
                </h2>
                <p
                    class="mb-12 mt-8 max-w-md font-body text-lg text-on-surface-variant"
                >
                    {{
                        hero?.subtitle ||
                        t(
                            "test1.hero_sub",
                            "Crafting ephemeral moments into lasting memories through the art of curated floristry and artisanal design.",
                        )
                    }}
                </p>
                <Link
                    :href="hero?.button_url || route('products.index')"
                    class="inline-block scale-95 rounded-full bg-gradient-to-r from-primary to-primary-container px-10 py-5 font-body text-xs uppercase tracking-widest text-on-primary transition-all duration-300 hover:shadow-lg active:scale-90"
                >
                    {{
                        hero?.button_text ||
                        t("test1.hero_cta", "Upload your photo")
                    }}
                </Link>
            </div>
            <div class="group relative">
                <div
                    class="organic-blob-2 absolute -right-20 -top-20 -z-10 h-96 w-96 bg-surface-container-highest opacity-50"
                />
                <div
                    class="organic-blob-3 absolute -bottom-10 -left-10 -z-10 h-64 w-64 bg-primary-container/20"
                />
                <div
                    class="organic-blob-1 aspect-[4/5] w-full rotate-3 overflow-hidden shadow-2xl transition-transform duration-700 group-hover:rotate-0"
                >
                    <img
                        :src="img"
                        alt=""
                        class="h-full w-full object-cover"
                        width="800"
                        height="1000"
                    />
                </div>
            </div>
        </div>
    </section>
</template>
