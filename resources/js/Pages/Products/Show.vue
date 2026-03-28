<script setup>
import { ref, computed, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import UpsellModal from "@/Components/UpsellModal.vue";
import { useCms } from "@/Composables/useCms";
import { useCart } from "@/Composables/useCart";
import { useCurrency } from "@/Composables/useCurrency";

const props = defineProps({
    product: { type: Object, required: true },
});

const { t, locale, getSetting } = useCms();
const { addToCart, loading: cartLoading } = useCart();
const { fmt } = useCurrency();
const page = usePage();
const visualEditorEnabled = computed(() => !!page.props.visualEditorEnabled);

const veMainImageAttrs = computed(() => {
    if (!visualEditorEnabled.value) return {};
    const p = props.product;
    return {
        "data-editable-table": "Product",
        "data-editable-id": String(p.id),
        "data-editable-column": "primary_image_path",
        "data-editable-image": `Product|${p.id}|primary_image_path`,
    };
});

const selectedVariation = ref(props.product.variations?.[0] ?? null);
const selectedAddons = ref([]);
const qty = ref(1);
const activeImage = ref(0);
const showUpsell = ref(false);

const primaryImages = computed(() => {
    const gallery = Array.isArray(props.product.gallery) 
        ? props.product.gallery 
        : (Array.isArray(props.product.images) ? props.product.images : []);
        
    if (gallery.length) {
        // Fallback mapping for older db formats
        const mapped = gallery.map(g => typeof g === 'string' ? { path: g, alt_text: props.product.name } : g);
        const withUrls = mapped.filter((i) => i?.path);
        if (withUrls.length) return withUrls;
    }
    
    if (props.product.primary_image) {
        return [{ path: props.product.primary_image, alt_text: props.product.name, is_primary: true }];
    }
    return [{ path: null, alt_text: props.product.name }];
});

const currentMainImage = computed(() => {
    return primaryImages.value[activeImage.value] || primaryImages.value[0] || { path: null };
});

const currentPrice = computed(() => {
    const base = props.product.base_price ?? 0;
    const mod = selectedVariation.value?.price_modifier ?? 0;
    return (base + mod).toFixed(2);
});

const addonTotal = computed(() =>
    selectedAddons.value.reduce((sum, id) => {
        const a = props.product.addons?.find((a) => a.id === id);
        return sum + (a?.price ?? 0);
    }, 0),
);

const totalPrice = computed(() =>
    ((parseFloat(currentPrice.value) + addonTotal.value) * qty.value).toFixed(2),
);

function toggleAddon(id) {
    const i = selectedAddons.value.indexOf(id);
    i === -1 ? selectedAddons.value.push(id) : selectedAddons.value.splice(i, 1);
}

function handleAddToCart() {
    addToCart(props.product.id, selectedVariation.value?.id ?? null, qty.value, selectedAddons.value);
    if (props.product.addons?.length) {
        showUpsell.value = true;
    }
}

const whatsappMessage = computed(() => {
    const name = props.product.name;
    const sku = props.product.sku;
    const price = fmt(parseFloat(currentPrice.value));
    const url = typeof window !== 'undefined' ? window.location.href : '';
    return encodeURIComponent(`أهلاً، أود طلب ${name} (كود ${sku}) بسعر ${price}. ${url}`);
});

const whatsappLink = computed(() => {
    const num = getSetting("contact", "whatsapp_number", "");
    return `https://wa.me/${num}?text=${whatsappMessage.value}`;
});

const isOutOfStock = computed(
    () => props.product.stock_status === "out_of_stock" || (props.product.stock_quantity !== null && props.product.stock_quantity === 0)
);

const isLowStock = computed(
    () => !isOutOfStock.value && props.product.stock_quantity !== null && props.product.stock_quantity < 5
);
</script>

<template>
    <AppLayout>
        <div class="product-show-wrapper">
            
            <div class="breadcrumb-container">
                <a :href="route('home')">{{ t("nav.home", "Home") }}</a>
                <span class="sep">/</span>
                <a :href="route('products.index')">{{ t("nav.shop", "Shop") }}</a>
                <span class="sep">/</span>
                <span class="breadcrumb-current">{{ product.name }}</span>
            </div>

            <div class="product-grid">
                
                <!-- LEFT COL: Gallery 7-col -->
                <div class="gallery-section">
                    <div class="gallery-main-wrapper" :class="{ 've-img-wrap': visualEditorEnabled }" v-bind="veMainImageAttrs">
                        <img v-if="currentMainImage.path" :src="currentMainImage.path" :alt="currentMainImage.alt_text" class="gallery-main-image" />
                        <div v-else class="gallery-placeholder">
                            <span>{{ product.name }}</span>
                        </div>
                        
                        <span v-if="isOutOfStock" class="stock-badge out">{{ t('products.out_of_stock', 'Out of Stock') }}</span>
                        <span v-else-if="isLowStock" class="stock-badge low">{{ t('products.low_stock', 'Low Stock') }}</span>
                    </div>

                    <div v-if="primaryImages.length > 1" class="gallery-thumbs-list">
                        <button 
                            v-for="(img, idx) in primaryImages" 
                            :key="img.path || idx" 
                            class="gallery-thumb-btn"
                            :class="{ active: activeImage === idx }"
                            @click="activeImage = idx"
                        >
                            <img v-if="img.path" :src="img.path" :alt="img.alt_text" class="gallery-thumb-img" loading="lazy" />
                        </button>
                    </div>
                </div>

                <!-- RIGHT COL: Info 5-col -->
                <div class="details-section">
                    
                    <div class="meta-row">
                        <span class="meta-category">{{ product.category }}</span>
                        <span class="meta-sku">{{ t('products.sku', 'SKU') }}: {{ product.sku }}</span>
                    </div>

                    <h1 class="product-title">
                        <span
                            v-if="visualEditorEnabled"
                            data-editable-table="Product"
                            :data-editable-id="String(product.id)"
                            :data-editable-column="product.editable_name_column"
                            :data-editable-key="`product_description|${product.id}|${product.editable_name_column}`"
                            class="inline"
                        >{{ product.name }}</span>
                        <template v-else>{{ product.name }}</template>
                    </h1>

                    <div class="price-row">
                        <span class="price-main">{{ fmt(parseFloat(currentPrice)) }}</span>
                        <span v-if="product.compare_at_price" class="price-compare">{{ fmt(product.compare_at_price) }}</span>
                    </div>

                    <p v-if="product.short_description || visualEditorEnabled" class="short-desc">
                        <span
                            v-if="visualEditorEnabled"
                            data-editable-table="Product"
                            :data-editable-id="String(product.id)"
                            :data-editable-column="product.editable_short_column"
                            :data-editable-key="`product_description|${product.id}|${product.editable_short_column}`"
                            class="inline whitespace-pre-line"
                        >{{ product.short_description }}</span>
                        <template v-else>{{ product.short_description }}</template>
                    </p>

                    <div v-if="product.variations?.length" class="options-block">
                        <span class="option-label">{{ selectedVariation?.type || 'Variant' }}: {{ selectedVariation?.value }}</span>
                        <div class="variations-list">
                            <button 
                                v-for="v in product.variations" 
                                :key="v.id" 
                                class="var-pill"
                                :class="{ active: selectedVariation?.id === v.id }"
                                @click="selectedVariation = v"
                            >
                                {{ v.value }}
                                <span v-if="v.price_modifier > 0" class="var-pill-price">+{{ fmt(v.price_modifier) }}</span>
                            </button>
                        </div>
                    </div>

                    <div v-if="product.addons?.length" class="options-block">
                        <span class="option-label">{{ t('products.addons', 'Add-ons') }}</span>
                        <div class="addons-list">
                            <label v-for="addon in product.addons" :key="addon.id" class="addon-item">
                                <input type="checkbox" :value="addon.id" :checked="selectedAddons.includes(addon.id)" @change="toggleAddon(addon.id)" class="addon-check" />
                                <span class="addon-name">{{ addon.name }}</span>
                                <span class="addon-price">+{{ fmt(addon.price) }}</span>
                            </label>
                        </div>
                    </div>

                    <div class="action-area">
                        <div class="qty-row">
                            <span class="option-label" style="margin: 0;">{{ t('cart.qty_label', 'Quantity') }}</span>
                            <div class="qty-selector">
                                <button class="qty-btn" @click="qty = Math.max(1, qty - 1)">−</button>
                                <span class="qty-display">{{ qty }}</span>
                                <button class="qty-btn" @click="qty = Math.min(99, qty + 1)">+</button>
                            </div>
                        </div>

                        <div v-if="addonTotal > 0 || qty > 1" class="total-preview">
                            <span class="total-label">{{ t('cart.total', 'Total') }}</span>
                            <span class="total-val">{{ fmt(parseFloat(totalPrice)) }}</span>
                        </div>

                        <button class="btn-primary" :disabled="isOutOfStock || cartLoading" @click="handleAddToCart">
                            <svg v-if="cartLoading" class="btn-spinner" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            <span>{{ isOutOfStock ? t('products.out_of_stock', 'Out of Stock') : t('products.add_to_cart', 'Add to Cart') }}</span>
                        </button>

                        <a :href="whatsappLink" target="_blank" rel="noopener" class="btn-whatsapp">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0 0 20.464 3.488" />
                            </svg>
                            <span>{{ locale === 'ar' ? 'اطلب عبر واتساب' : 'Order via WhatsApp' }}</span>
                        </a>
                    </div>

                    <div v-if="product.is_giftable" class="gift-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 12v10H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                        <span>{{ locale === 'ar' ? 'متاح كهدية — تغليف فاخر مع إمكانية إضافة رسالة' : 'Available as a gift — personalised packaging & message included' }}</span>
                    </div>

                    <div v-if="product.description || visualEditorEnabled" class="full-description">
                        <h3 class="desc-title">{{ t('products.description', 'Description') }}</h3>
                        <div
                            v-if="visualEditorEnabled"
                            class="desc-content"
                            data-editable-table="Product"
                            :data-editable-id="String(product.id)"
                            :data-editable-column="product.editable_description_column"
                            :data-editable-key="`product_description|${product.id}|${product.editable_description_column}`"
                        >{{ product.description_plain }}</div>
                        <div v-else class="desc-content" v-html="product.description"></div>
                    </div>
                </div>
                
            </div>
        </div>

        <UpsellModal
            :show="showUpsell"
            :addons="product.addons ?? []"
            :product-name="product.name"
            @close="showUpsell = false"
        />
    </AppLayout>
</template>

<style scoped>
.product-show-wrapper {
  padding-top: 0;
  padding-bottom: 100px;
  background-color: var(--cream-light);
  min-height: 100vh;
}

.breadcrumb-container {
  max-width: 1280px;
  width: 95%;
  margin: 0 auto;
  padding: 20px 0 28px;
  display: flex;
  gap: 8px;
  align-items: center;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--text-light);
}
.breadcrumb-container a { transition: color 0.2s; text-decoration: none; color: inherit; }
.breadcrumb-container a:hover { color: var(--gold); }
.breadcrumb-current { color: var(--charcoal); font-weight: 500; }
.sep { color: rgba(58, 53, 48, 0.3); font-size: 10px; }

/* Grid Layout — true 50/50 desktop split */
.product-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 56px;
  max-width: 1280px;
  width: 95%;
  margin: 0 auto;
  align-items: start;
}

@media (max-width: 960px) {
  .product-grid { grid-template-columns: 1fr; gap: 40px; }
}

/* Gallery — fills full half */
.gallery-section {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.gallery-main-wrapper {
  width: 100%;
  aspect-ratio: 3 / 4;
  border-radius: 4px;
  overflow: hidden;
  background: var(--cream);
  border: 1px solid rgba(58, 53, 48, 0.08);
  position: relative;
}

.gallery-main-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center top;
  display: block;
  transition: transform 0.7s var(--ease-luxury);
}
.gallery-main-wrapper:hover .gallery-main-image { transform: scale(1.04); }

.gallery-placeholder {
  width: 100%; height: 100%;
  background: linear-gradient(135deg, var(--cream), var(--cream-light));
  display: flex; align-items: center; justify-content: center;
  font-family: var(--font-serif); font-size: 28px; font-style: italic; color: var(--gold); opacity: 0.6; padding: 20px; text-align: center;
}

.stock-badge {
    position: absolute; top: 20px; inset-inline-start: 20px;
    font-size: 10px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;
    padding: 6px 14px; border-radius: 2px; color: #fff; z-index: 10;
}
[dir="rtl"] .stock-badge { letter-spacing: 0; }
.stock-badge.out { background: rgba(220, 38, 38, 0.95); }
.stock-badge.low { background: rgba(184, 150, 62, 0.95); }

/* Thumbs */
.gallery-thumbs-list {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}
.gallery-thumb-btn {
  width: 72px;
  height: 90px;
  border-radius: 3px;
  border: 1.5px solid transparent;
  overflow: hidden;
  cursor: pointer;
  transition: border-color 0.2s, opacity 0.2s;
  padding: 0;
  background: var(--cream);
  opacity: 0.55;
  flex-shrink: 0;
}
.gallery-thumb-btn.active, .gallery-thumb-btn:hover { border-color: var(--gold); opacity: 1; }
.gallery-thumb-img { width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block; }

/* Details — fills full right half */
.details-section {
  position: sticky;
  top: calc(var(--layout-header-offset) + 24px);
  display: flex;
  flex-direction: column;
  gap: 24px;
}
@media (max-width: 960px) { .details-section { position: static; } }

.meta-row {
  display: flex; justify-content: space-between; align-items: center;
  font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase;
}
[dir="rtl"] .meta-row { letter-spacing: 0; }
.meta-category { color: var(--gold); font-weight: 600; }
.meta-sku { color: var(--text-light); }

.product-title {
  font-family: var(--font-serif);
  font-size: clamp(32px, 4vw, 44px);
  font-weight: 400;
  color: var(--dark);
  line-height: 1.1;
  margin: -8px 0;
}

.price-row { display: flex; align-items: baseline; gap: 16px; }
.price-main { font-family: var(--font-serif); font-size: 32px; color: var(--dark); }
.price-compare { font-size: 18px; color: var(--text-light); text-decoration: line-through; }

.short-desc {
  font-size: 15px;
  color: var(--charcoal);
  line-height: 1.6;
  font-weight: 300;
  padding-bottom: 24px;
  border-bottom: 1px solid rgba(58, 53, 48, 0.08);
  margin: 0;
}

/* Options */
.options-block { display: flex; flex-direction: column; gap: 12px; }
.option-label {
  font-size: 11px; font-weight: 600; letter-spacing: 0.1em;
  text-transform: uppercase; color: var(--charcoal); margin: 0;
}
[dir="rtl"] .option-label { letter-spacing: 0; }

.variations-list { display: flex; flex-wrap: wrap; gap: 10px; }
.var-pill {
  padding: 10px 20px; border: 1.5px solid rgba(58, 53, 48, 0.15); border-radius: 4px;
  font-size: 13px; background: var(--white); color: var(--charcoal); font-weight: 500;
  cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px;
}
.var-pill:hover { border-color: var(--gold); color: var(--dark); }
.var-pill.active { background: var(--gold); border-color: var(--gold); color: var(--white); }
.var-pill-price { font-size: 11px; opacity: 0.9; }

.addons-list { display: flex; flex-direction: column; gap: 10px; }
.addon-item {
  display: flex; align-items: center; gap: 12px; padding: 12px 16px;
  border: 1px solid rgba(58, 53, 48, 0.15); border-radius: 6px; cursor: pointer; transition: background 0.2s;
  background: var(--white);
}
.addon-item:hover { border-color: var(--gold); }
.addon-item:has(.addon-check:checked) { border-color: var(--gold); background: rgba(184, 150, 62, 0.05); }
.addon-check { width: 18px; height: 18px; accent-color: var(--gold); }
.addon-name { flex: 1; font-size: 14px; color: var(--dark); }
.addon-price { font-size: 13px; font-weight: 500; color: var(--gold); }

/* Actions */
.action-area {
  padding-top: 24px; display: flex; flex-direction: column; gap: 16px;
  border-top: 1px solid rgba(58, 53, 48, 0.08);
}
.qty-row { display: flex; align-items: center; gap: 24px; }
.qty-selector {
  display: flex; align-items: center; border: 1.5px solid rgba(58, 53, 48, 0.2); border-radius: 4px; background: var(--white); overflow: hidden;
}
.qty-btn { width: 44px; height: 44px; background: none; border: none; font-size: 18px; cursor: pointer; color: var(--charcoal); transition: background 0.2s; }
.qty-btn:hover { background: var(--cream); }
.qty-display { width: 44px; text-align: center; font-size: 15px; font-weight: 500; }

.total-preview {
  display: flex; justify-content: space-between; align-items: center;
  background: var(--white); padding: 16px 20px; border-radius: 6px; border: 1px dashed rgba(58, 53, 48, 0.2);
}
.total-label { font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--text-light); letter-spacing: 0.1em; }
.total-val { font-family: var(--font-serif); font-size: 26px; color: var(--dark); font-weight: 400; }

/* Buttons */
.btn-primary {
  width: 100%; padding: 18px; background: var(--gold-btn); color: var(--white);
  border: none; border-radius: 4px; font-family: var(--font-sans); font-size: 12px; font-weight: 600;
  letter-spacing: 0.15em; text-transform: uppercase; cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
  display: flex; justify-content: center; align-items: center; gap: 10px;
}
[dir="rtl"] .btn-primary { letter-spacing: 0; }
.btn-primary:hover:not(:disabled) { background: var(--gold); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(184, 150, 62, 0.35); }
.btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

.btn-spinner { width: 18px; height: 18px; animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

.btn-whatsapp {
  width: 100%; padding: 16px; background: #25d366; color: var(--white);
  border: none; border-radius: 4px; font-family: var(--font-sans); font-size: 13px; font-weight: 600;
  letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer;
  transition: transform 0.2s, background 0.2s; display: flex; justify-content: center; align-items: center; gap: 10px;
  text-decoration: none;
}
[dir="rtl"] .btn-whatsapp { letter-spacing: 0; }
.btn-whatsapp:hover { background: #1ebd56; transform: translateY(-1px); }

/* Gift & Desc */
.gift-badge {
  display: flex; align-items: center; gap: 12px; padding: 18px;
  background: rgba(201, 168, 76, 0.05); border-radius: 8px; font-size: 13px; color: var(--charcoal);
  border: 1px solid rgba(201, 168, 76, 0.2); line-height: 1.5; margin-top: 8px;
}
.gift-badge svg { width: 22px; height: 22px; color: var(--gold); flex-shrink: 0; }

.full-description { margin-top: 24px; padding-top: 32px; border-top: 1px solid rgba(58, 53, 48, 0.1); }
.desc-title { font-family: var(--font-serif); font-size: 24px; color: var(--dark); margin-bottom: 20px; font-weight: 400; }
.desc-content { font-size: 15px; font-weight: 300; line-height: 1.8; color: var(--charcoal); }

@media (max-width: 640px) {
  .gallery-main-wrapper { aspect-ratio: 4 / 5; }
  .gallery-thumbs-list { flex-wrap: nowrap; overflow-x: auto; padding-bottom: 8px; scroll-snap-type: x mandatory; gap: 8px; }
  .gallery-thumb-btn { flex-shrink: 0; scroll-snap-align: start; width: 60px; height: 75px; }
  .product-title { font-size: 26px; }
  .price-main { font-size: 26px; }
}
</style>
