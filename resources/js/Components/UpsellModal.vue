<script setup>
import { ref, computed, watch } from 'vue'
import { useCms } from '@/Composables/useCms'
import { useCurrency } from '@/Composables/useCurrency'
import { useCart } from '@/Composables/useCart'

const props = defineProps({
    show: { type: Boolean, default: false },
    addons: { type: Array, default: () => [] },
    productName: { type: String, default: '' },
})

const emit = defineEmits(['close'])

const { locale } = useCms()
const { fmt } = useCurrency()
const { addToCart } = useCart()
const isAr = computed(() => locale.value === 'ar')

const selectedAddons = ref([])

watch(() => props.show, (val) => {
    if (val) selectedAddons.value = []
})

function toggleAddon(addon) {
    const idx = selectedAddons.value.findIndex(a => a.id === addon.id)
    if (idx >= 0) {
        selectedAddons.value.splice(idx, 1)
    } else {
        selectedAddons.value.push(addon)
    }
}

function isSelected(addon) {
    return selectedAddons.value.some(a => a.id === addon.id)
}

const totalAddonPrice = computed(() =>
    selectedAddons.value.reduce((sum, a) => sum + a.price, 0)
)

function addSelected() {
    emit('close')
}

function skipAll() {
    emit('close')
}
</script>

<template>
  <Transition name="fade">
    <div v-if="show" class="upsell-backdrop" @click.self="skipAll">
      <Transition name="upsell-slide">
        <div v-if="show" class="upsell-modal">
          <button class="upsell-close" @click="skipAll">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
          </button>

          <div class="upsell-header">
            <div class="upsell-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20 12v10H4V12"/><path d="M22 7H2v5h20V7z"/>
                <path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/>
                <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
              </svg>
            </div>
            <h3 class="upsell-title">
              {{ isAr ? 'اجعل هديتك أجمل' : 'Make It Extra Special' }}
            </h3>
            <p class="upsell-subtitle">
              {{ isAr
                   ? `تمت إضافة "${productName}" — أضف لمسة فاخرة`
                   : `"${productName}" added — add a luxury touch`
              }}
            </p>
          </div>

          <div class="upsell-items">
            <button
              v-for="addon in addons"
              :key="addon.id"
              :class="['upsell-item', { selected: isSelected(addon) }]"
              @click="toggleAddon(addon)"
            >
              <div class="upsell-item-info">
                <span class="upsell-item-name">{{ addon.name }}</span>
                <span v-if="addon.description" class="upsell-item-desc">{{ addon.description }}</span>
              </div>
              <span class="upsell-item-price">+{{ fmt(addon.price) }}</span>
              <div class="upsell-check">
                <svg v-if="isSelected(addon)" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <polyline points="20 6 9 17 4 12"/>
                </svg>
              </div>
            </button>
          </div>

          <div class="upsell-footer">
            <button class="upsell-btn-add" @click="addSelected" :disabled="!selectedAddons.length">
              {{ isAr ? 'إضافة المختار' : 'Add Selected' }}
              <span v-if="totalAddonPrice > 0">(+{{ fmt(totalAddonPrice) }})</span>
            </button>
            <button class="upsell-btn-skip" @click="skipAll">
              {{ isAr ? 'لا شكرًا، متابعة' : 'No thanks, continue' }}
            </button>
          </div>
        </div>
      </Transition>
    </div>
  </Transition>
</template>

<style scoped>
.upsell-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  z-index: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  backdrop-filter: blur(3px);
}

.upsell-modal {
  background: var(--white);
  border-radius: 12px;
  width: 100%;
  max-width: 440px;
  max-height: 85vh;
  overflow-y: auto;
  position: relative;
  box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}

.upsell-close {
  position: absolute;
  top: 16px;
  inset-inline-end: 16px;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-light);
  padding: 6px;
  border-radius: 50%;
  transition: background 0.15s;
}

.upsell-close svg { width: 18px; height: 18px; }
.upsell-close:hover { background: var(--cream-light); color: var(--dark); }

.upsell-header {
  text-align: center;
  padding: 32px 28px 20px;
}

.upsell-icon {
  width: 48px;
  height: 48px;
  margin: 0 auto 12px;
  background: rgba(201,168,76,0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--gold);
}

.upsell-icon svg { width: 24px; height: 24px; }

.upsell-title {
  font-family: var(--font-serif);
  font-size: 22px;
  font-weight: 400;
  color: var(--dark);
  margin-bottom: 6px;
}

.upsell-subtitle {
  font-size: 13px;
  color: var(--text-light);
  font-weight: 300;
}

.upsell-items {
  padding: 0 20px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.upsell-item {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 16px;
  border: 1.5px solid rgba(58,53,48,0.12);
  border-radius: 8px;
  cursor: pointer;
  background: none;
  text-align: start;
  transition: all 0.2s;
  width: 100%;
}

.upsell-item:hover { border-color: var(--gold); }

.upsell-item.selected {
  border-color: var(--gold);
  background: rgba(201,168,76,0.06);
}

.upsell-item-info { flex: 1; }
.upsell-item-name { display: block; font-size: 14px; font-weight: 500; color: var(--dark); }
.upsell-item-desc { display: block; font-size: 11px; color: var(--text-light); margin-top: 2px; }
.upsell-item-price { font-size: 14px; font-weight: 600; color: var(--gold); flex-shrink: 0; }

.upsell-check {
  width: 24px;
  height: 24px;
  border: 2px solid rgba(58,53,48,0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: all 0.2s;
}

.upsell-item.selected .upsell-check {
  background: var(--gold);
  border-color: var(--gold);
  color: var(--white);
}

.upsell-check svg { width: 14px; height: 14px; }

.upsell-footer { padding: 20px 20px 28px; }

.upsell-btn-add {
  width: 100%;
  padding: 15px;
  background: var(--gold-btn);
  color: var(--white);
  font-family: var(--font-sans);
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
  margin-bottom: 8px;
}

[dir='rtl'] .upsell-btn-add { letter-spacing: 0; }
.upsell-btn-add:hover:not(:disabled) { background: var(--gold); transform: translateY(-1px); }
.upsell-btn-add:disabled { opacity: 0.4; cursor: default; }

.upsell-btn-skip {
  width: 100%;
  padding: 10px;
  background: none;
  border: none;
  font-size: 12px;
  color: var(--text-light);
  cursor: pointer;
  transition: color 0.2s;
}

.upsell-btn-skip:hover { color: var(--dark); }

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.upsell-slide-enter-active { transition: all 0.35s var(--ease-luxury); }
.upsell-slide-leave-active { transition: all 0.2s ease; }
.upsell-slide-enter-from { opacity: 0; transform: translateY(30px) scale(0.96); }
.upsell-slide-leave-to { opacity: 0; transform: scale(0.96); }
</style>
