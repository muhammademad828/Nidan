<script setup>
import { Link } from "@inertiajs/vue3";
import { useCms } from "@/Composables/useCms";
import { useCart } from "@/Composables/useCart";
import { useCurrency } from "@/Composables/useCurrency";

const props = defineProps({
    products: { type: Array, default: () => [] },
});

const { t } = useCms();
const { addToCart } = useCart();
const { fmt } = useCurrency();

const fallbacks = [
    {
        name: "Stainless Steel Chains",
        category: "Jewelry",
        price: "AED 450",
        image:
            "https://lh3.googleusercontent.com/aida-public/AB6AXuBga8WdIvH0u9GhW-zfzonU2sa3G2GJ-XbmjVGmIgJEetnwsnYiEcRmRM5_1qxvV79ulyW_1sV17dRLZNpzVYiik4dWPWbujN2WjjkGOmZnJKQAdvy5fGHlkRvK8-35jjkl7fjay8UBdiligUBVLoTtLuNLGZwAeSmJaj0E2Ea_K_ITZUvQ8T6GqVfuKstrKewwHJQdMqDP7QiwyPlL-ROWF_azTGxprphE7h8v6CUJMcw5NU8d4bd8xXbxCI4KdE2bMiSPy8ssMgiZ",
    },
    {
        name: "Custom Jewelry",
        category: "Signature",
        price: "From AED 1,200",
        image:
            "https://lh3.googleusercontent.com/aida-public/AB6AXuB9ThAhL1yavFA0X9Bx00S6U1gfSyvpGM_wwjChxj6HnSjIzPElyjcq8KzQfqzfgAp7Qcli6n0Ck9eVOiLYNtOul8NP1HMVp75WSou2aCFUsSyPhVoZZAGMlYY5yTUSfrDgMKbctPEm_NPfT8bCl39wTIUwEFRdqd9zBur6717dOWvw4bOBhRMytzjtw0E6WeO1x7et1GjNwAb-reoLkxdSROzgcVKgXj1A7sKqbmhTChjPrVzY0Eq0ckM5gozgHpbG5AUtcddmjj9e",
    },
    {
        name: "Luxury Wallets",
        category: "Accessories",
        price: "AED 890",
        image:
            "https://lh3.googleusercontent.com/aida-public/AB6AXuCxFvK85qup9MgK3seQnvmEYrBgSn1Dw367fXn4ectrZXZAd2rrqpo3OtEizXjGtoESeVM1x8Z54ELu0ZpUCAHOdIZ25Fe0DTFJrvJnqA9KLtbU8gWwnJlSDjsor2thSOsgDEdhRuuKwzdmJNO5_xpR0W6fp63mQp-AfuAtOCh-QE_Vro9paj4EXxCNEfyJGuiVqJasxoXSh0BBkt1JJUQC3z_3ohQNuqRdFGVl0KojKiuWHnUeEugrFqZGqC82YTvUP_WFQvpqTOxY",
    },
];

const displayItems = () => {
    const list = props.products.slice(0, 3);
    if (list.length >= 3) {
        return list.map((p) => ({
            slug: p.slug,
            name: p.name,
            category: p.category || t("products.category", "Shop"),
            price: fmt(p.base_price),
            image: p.primary_image,
            id: p.id,
            stock_status: p.stock_status,
        }));
    }
    return fallbacks.map((f, i) => ({
        slug: null,
        name: f.name,
        category: f.category,
        price: f.price,
        image: f.image,
        id: null,
        stock_status: null,
    }));
};

function quickAdd(item) {
    if (!item.id || item.stock_status === "out_of_stock") return;
    addToCart({ product_id: item.id, quantity: 1, addons: [] });
}
</script>

<template>
    <section class="px-8 py-24 md:px-24">
        <div class="container mx-auto">
            <div class="mb-20 text-center">
                <h3
                    class="mb-4 font-headline text-5xl text-on-surface md:text-6xl"
                >
                    {{ t("test1.best_sellers", "Best Sellers") }}
                </h3>
                <div class="mx-auto h-px w-24 bg-primary/30" />
            </div>
            <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
                <div
                    v-for="(item, idx) in displayItems()"
                    :key="item.slug || item.name + idx"
                    class="group"
                >
                    <div
                        v-if="item.slug"
                        class="relative mb-6 aspect-[3/4] overflow-hidden rounded-lg bg-surface-container-low"
                    >
                        <Link
                            :href="route('products.show', item.slug)"
                            class="block h-full w-full"
                        >
                            <img
                                v-if="item.image"
                                :src="item.image"
                                :alt="item.name"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            <div
                                v-else
                                class="flex h-full min-h-[200px] w-full items-center justify-center bg-surface-container font-headline text-on-surface-variant"
                            >
                                {{ item.name }}
                            </div>
                        </Link>
                        <button
                            type="button"
                            class="absolute bottom-6 right-6 rounded-full bg-surface-bright/90 p-4 opacity-0 backdrop-blur transition-opacity group-hover:opacity-100"
                            @click="quickAdd(item)"
                        >
                            <span class="material-symbols-outlined">add</span>
                        </button>
                    </div>
                    <div
                        v-else
                        class="relative mb-6 aspect-[3/4] overflow-hidden rounded-lg bg-surface-container-low"
                    >
                        <img
                            :src="item.image"
                            :alt="item.name"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        />
                        <Link
                            :href="route('products.index')"
                            class="absolute bottom-6 right-6 rounded-full bg-surface-bright/90 p-4 opacity-0 backdrop-blur transition-opacity group-hover:opacity-100"
                        >
                            <span class="material-symbols-outlined">add</span>
                        </Link>
                    </div>
                    <div class="text-center">
                        <p
                            class="mb-2 font-body text-[10px] uppercase tracking-[0.2em] text-on-surface-variant"
                        >
                            {{ item.category }}
                        </p>
                        <Link
                            v-if="item.slug"
                            :href="route('products.show', item.slug)"
                        >
                            <h5
                                class="mb-2 font-headline text-2xl text-on-surface"
                            >
                                {{ item.name }}
                            </h5>
                        </Link>
                        <h5
                            v-else
                            class="mb-2 font-headline text-2xl text-on-surface"
                        >
                            {{ item.name }}
                        </h5>
                        <p class="font-body text-sm font-bold text-primary">
                            {{ item.price }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
