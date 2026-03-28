<script setup>
import { ref, computed, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import { useCms } from '@/Composables/useCms'
import { useCart } from '@/Composables/useCart'
import { useCurrency } from '@/Composables/useCurrency'

const { t } = useCms()
const { isOpen, items, subtotal, close, updateQty, removeItem } = useCart()
const { fmt } = useCurrency()

/* ── Optimistic local qty map ── */
const localQty = ref({})

// Sync localQty whenever cart items change (after server updates)
watch(items, (newItems) => {
    const map = {}
    newItems.forEach(i => { map[i.id] = i.quantity })
    localQty.value = map
}, { immediate: true, deep: true })

function displayQty(item) {
    return localQty.value[item.id] ?? item.quantity
}

function increment(item) {
    const newQty = (localQty.value[item.id] ?? item.quantity) + 1
    if (newQty > 99) return
    localQty.value[item.id] = newQty
    updateQty(item.id, newQty)
}

function decrement(item) {
    const newQty = (localQty.value[item.id] ?? item.quantity) - 1
    if (newQty <= 0) {
        handleRemove(item)
        return
    }
    localQty.value[item.id] = newQty
    updateQty(item.id, newQty)
}

function handleRemove(item) {
    delete localQty.value[item.id]
    removeItem(item.id)
}

/* ── Computed local subtotal ── */
const localSubtotal = computed(() => {
    return items.value.reduce((sum, item) => {
        const qty = localQty.value[item.id] ?? item.quantity
        return sum + (item.unit_price * qty)
    }, 0)
})

function fmtPrice(amount) {
    return fmt(amount)
}
</script>

<template>
  <!-- Backdrop -->
  <Transition name="fade">
    <div v-if="isOpen" class="drawer-backdrop" @click="close" />
  </Transition>

  <!-- Drawer Panel -->
  <Transition name="slide-end">
    <div v-if="isOpen" class="cart-drawer" role="dialog" :aria-label="t('cart.title', 'Your Cart')">

      <!-- Header -->
      <div class="drawer-header">
        <div class="drawer-header-left">
          <h3 class="drawer-title">{{ t('cart.title', 'Your Cart') }}</h3>
          <span v-if="items.length" class="drawer-count">({{ items.length }})</span>
        </div>
        <button class="drawer-close" @click="close" :aria-label="t('cart.close', 'Close')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M18 6 6 18M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Empty State -->
      <div v-if="!items.length" class="drawer-empty">
        <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
          <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        <p class="empty-title">{{ t('cart.empty', 'Your cart is empty') }}</p>
        <p class="empty-sub">Add some luxury pieces to get started.</p>
        <Link :href="route('products.index')" class="btn-primary" @click="close">
          {{ t('cart.empty_cta', 'Start Shopping') }}
        </Link>
      </div>

      <!-- Items List -->
      <div v-else class="drawer-items">
        <TransitionGroup name="cart-item-anim" tag="div">
          <div v-for="item in items" :key="item.id" class="cart-item">
            <!-- Product Image -->
            <div class="item-image">
              <img v-if="item.product_image" :src="item.product_image" :alt="item.product_name" />
              <div v-else class="item-image-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3">
                  <rect x="3" y="3" width="18" height="18" rx="2"/><path d="m9 9 6 6M15 9l-6 6"/>
                </svg>
              </div>
            </div>

            <!-- Details -->
            <div class="item-details">
              <p class="item-name">{{ item.product_name }}</p>
              <p v-if="item.variation_name" class="item-variation">{{ item.variation_name }}</p>
              <p class="item-sku">{{ item.product_sku }}</p>

              <div class="item-controls">
                <div class="qty-control">
                  <button class="qty-btn" @click="decrement(item)" :disabled="displayQty(item) <= 1">−</button>
                  <span class="qty-value">{{ displayQty(item) }}</span>
                  <button class="qty-btn" @click="increment(item)">+</button>
                </div>
                <span class="item-price">{{ fmtPrice(item.unit_price * displayQty(item)) }}</span>
              </div>
            </div>

            <!-- Remove -->
            <button class="item-remove" @click="handleRemove(item)" :aria-label="t('cart.remove', 'Remove')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>
              </svg>
            </button>
          </div>
        </TransitionGroup>
      </div>

      <!-- Footer -->
      <div v-if="items.length" class="drawer-footer">
        <div class="subtotal-row">
          <span class="subtotal-label">{{ t('cart.subtotal', 'Subtotal') }}</span>
          <span class="subtotal-value">{{ fmtPrice(localSubtotal) }}</span>
        </div>
        <p class="subtotal-note">Delivery & VAT calculated at checkout</p>
        <Link :href="route('checkout.index')" class="btn-checkout" @click="close">
          {{ t('cart.checkout_btn', 'Proceed to Checkout') }}
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </Link>
        <Link :href="route('products.index')" class="btn-continue" @click="close">
          {{ t('cart.empty_cta', 'Continue Shopping') }}
        </Link>
      </div>

    </div>
  </Transition>
</template>

<style scoped>
.drawer-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  z-index: 400;
  backdrop-filter: blur(2px);
}

.cart-drawer {
  position: fixed;
  top: 0;
  inset-inline-end: 0;
  bottom: 0;
  width: 420px;
  max-width: 100vw;
  background: var(--white);
  z-index: 500;
  display: flex;
  flex-direction: column;
  box-shadow: -4px 0 40px rgba(0, 0, 0, 0.15);
}

/* ── Header ── */
.drawer-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 24px 28px 20px;
  border-bottom: 1px solid rgba(58, 53, 48, 0.08);
}

.drawer-header-left { display: flex; align-items: baseline; gap: 8px; }

.drawer-title {
  font-family: var(--font-serif);
  font-size: 20px;
  font-weight: 400;
  color: var(--dark);
}

.drawer-count {
  font-size: 12px;
  color: var(--text-light);
  font-weight: 300;
}

.drawer-close {
  background: none; border: none; cursor: pointer;
  color: var(--text-light); display: flex; padding: 6px;
  border-radius: 50%; transition: background 0.15s, color 0.15s;
}
.drawer-close svg { width: 18px; height: 18px; }
.drawer-close:hover { background: var(--cream-light); color: var(--dark); }

/* ── Empty ── */
.drawer-empty {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 12px; padding: 40px 28px; text-align: center;
}
.empty-icon { width: 52px; height: 52px; color: var(--text-light); opacity: 0.5; }
.empty-title { font-family: var(--font-serif); font-size: 20px; color: var(--dark); }
.empty-sub { font-size: 13px; font-weight: 300; color: var(--text-light); margin-bottom: 8px; }

/* ── Items ── */
.drawer-items { flex: 1; overflow-y: auto; }

.cart-item {
  display: flex; gap: 14px; padding: 16px 28px;
  border-bottom: 1px solid rgba(58, 53, 48, 0.06);
  position: relative;
  transition: background 0.15s;
}
.cart-item:hover { background: var(--cream-light); }

.item-image {
  width: 68px; height: 68px; border-radius: 6px;
  overflow: hidden; flex-shrink: 0; background: var(--cream-light);
  display: flex; align-items: center; justify-content: center;
}
.item-image img { width: 100%; height: 100%; object-fit: cover; }
.item-image-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.item-image-placeholder svg { width: 24px; height: 24px; }

.item-details { flex: 1; min-width: 0; }

.item-name {
  font-family: var(--font-serif); font-size: 15px;
  font-weight: 400; color: var(--dark);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  margin-bottom: 2px;
}
.item-variation { font-size: 11px; color: var(--text-light); margin-bottom: 2px; }
.item-sku {
  font-size: 9px; font-weight: 600; letter-spacing: 0.1em;
  color: var(--text-light); text-transform: uppercase; margin-bottom: 10px;
}
[dir='rtl'] .item-sku { letter-spacing: 0; }

.item-controls { display: flex; align-items: center; justify-content: space-between; }

.qty-control {
  display: flex; align-items: center; gap: 0;
  border: 1px solid rgba(58, 53, 48, 0.18); border-radius: 4px; overflow: hidden;
}
.qty-btn {
  width: 28px; height: 28px; background: none; border: none;
  cursor: pointer; font-size: 14px; color: var(--charcoal);
  display: flex; align-items: center; justify-content: center;
  transition: background 0.15s;
}
.qty-btn:hover:not(:disabled) { background: var(--cream-light); }
.qty-btn:disabled { opacity: 0.35; cursor: default; }
.qty-value {
  font-size: 13px; font-weight: 500; color: var(--dark);
  min-width: 28px; text-align: center;
}

.item-price { font-size: 14px; font-weight: 500; color: var(--dark); }

.item-remove {
  position: absolute; top: 14px; inset-inline-end: 14px;
  background: none; border: none; cursor: pointer;
  color: var(--text-light); padding: 4px; border-radius: 4px;
  display: flex; transition: background 0.15s, color 0.15s;
}
.item-remove svg { width: 14px; height: 14px; }
.item-remove:hover { background: rgba(220, 38, 38, 0.08); color: #dc2626; }

/* ── Footer ── */
.drawer-footer {
  padding: 20px 28px 32px;
  border-top: 1px solid rgba(58, 53, 48, 0.08);
}

.subtotal-row {
  display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 4px;
}
.subtotal-label {
  font-size: 11px; font-weight: 600; letter-spacing: 0.12em;
  text-transform: uppercase; color: var(--text-light);
}
[dir='rtl'] .subtotal-label { letter-spacing: 0; }
.subtotal-value {
  font-family: var(--font-serif); font-size: 22px; font-weight: 400; color: var(--dark);
}
.subtotal-note {
  font-size: 11px; font-weight: 300; color: var(--text-light); margin-bottom: 18px;
}

.btn-checkout {
  display: flex; align-items: center; justify-content: center; gap: 10px;
  width: 100%; padding: 15px 20px;
  background: var(--gold-btn); color: var(--white);
  font-family: var(--font-sans); font-size: 11px; font-weight: 600;
  letter-spacing: 0.18em; text-transform: uppercase;
  border-radius: 3px; transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
  margin-bottom: 10px;
}
[dir='rtl'] .btn-checkout { letter-spacing: 0; }
.btn-checkout svg { width: 16px; height: 16px; transition: transform 0.2s; }
.btn-checkout:hover { background: var(--gold); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(184,150,62,0.3); }
.btn-checkout:hover svg { transform: translateX(3px); }
[dir='rtl'] .btn-checkout:hover svg { transform: translateX(-3px); }

.btn-continue {
  display: block; width: 100%; text-align: center;
  font-size: 12px; font-weight: 500; color: var(--text-light);
  padding: 8px; transition: color 0.2s;
}
.btn-continue:hover { color: var(--dark); }

/* ── Transitions ── */
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.slide-end-enter-active, .slide-end-leave-active {
  transition: transform 0.4s var(--ease-luxury);
}
.slide-end-enter-from, .slide-end-leave-to { transform: translateX(100%); }
[dir='rtl'] .slide-end-enter-from,
[dir='rtl'] .slide-end-leave-to { transform: translateX(-100%); }

.cart-item-anim-enter-active { transition: all 0.3s var(--ease-luxury); }
.cart-item-anim-leave-active { transition: all 0.25s ease; }
.cart-item-anim-enter-from { opacity: 0; transform: translateX(20px); }
.cart-item-anim-leave-to   { opacity: 0; transform: translateX(20px); height: 0; padding: 0; }
</style>
