<script setup>
import { ref, computed, watch } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useCms } from '@/Composables/useCms'
import { useCurrency } from '@/Composables/useCurrency'

const props = defineProps({
    cart:          { type: Object, required: true },
    regions:       { type: Array,  default: () => [] },
    currentRegion: { type: Object, default: null },
    deliverySlots: { type: Array,  default: () => [] },
    governorates:  { type: Array,  default: () => [] },
})

const { t, locale } = useCms()
const { fmt } = useCurrency()
const isAr = computed(() => locale.value === 'ar')

const form = useForm({
    company_name:      '',
    contact_person:    '',
    contact_phone:     '',
    region_id:         props.currentRegion?.id ?? '',
    governorate_id:    '',
    city_id:           '',
    address:           '',
    delivery_date:     '',
    delivery_slot_id:  '',
    notes:             '',
    is_gift:           false,
    is_surprise:       false,
    recipient_name:    '',
    recipient_phone:   '',
    recipient_address: '',
    gift_message:      '',
    payment_method:    'cod',
    preferred_delivery_time: '',
    terms:             false,
})

// Compute selected region
const selectedRegion = computed(() =>
    props.regions.find(r => r.id == form.region_id) ?? props.currentRegion
)

// Governorate / City cascading
const selectedGovernorate = computed(() =>
    props.governorates.find(g => g.id == form.governorate_id) ?? null
)

const availableCities = computed(() =>
    selectedGovernorate.value?.cities ?? []
)

const selectedCity = computed(() =>
    availableCities.value.find(c => c.id == form.city_id) ?? null
)

// Reset city when governorate changes
watch(() => form.governorate_id, () => { form.city_id = '' })

// Available slots filtered by region and date
const availableSlots = computed(() => {
    if (!form.region_id) return props.deliverySlots
    return props.deliverySlots.filter(s => s.region_id == form.region_id)
})

const slotsForDate = computed(() => {
    if (!form.delivery_date) return []
    return availableSlots.value.filter(s => s.date === form.delivery_date)
})

const uniqueDates = computed(() => {
    const dates = [...new Set(availableSlots.value.map(s => s.date))]
    return dates.map(d => {
        const slot = availableSlots.value.find(s => s.date === d)
        return { value: d, label: slot?.date_label ?? d }
    })
})

// Reset slot when date changes
watch(() => form.delivery_date, () => { form.delivery_slot_id = '' })

// Totals — city fee takes priority over region fee
const deliveryFee = computed(() => {
    if (selectedCity.value) return parseFloat(selectedCity.value.delivery_fee ?? 0)
    return parseFloat(selectedRegion.value?.delivery_fee ?? 0)
})
const tax   = computed(() => parseFloat((props.cart.subtotal * 0.15).toFixed(2)))
const total = computed(() => (props.cart.subtotal + deliveryFee.value + tax.value).toFixed(2))

function submit() {
    form.post(route('checkout.store'))
}
</script>

<template>
  <AppLayout>
    <div class="checkout-page">
      <div class="checkout-inner">

        <!-- ── Header ── -->
        <div class="checkout-header">
          <h1 class="checkout-title">{{ t('checkout.title', 'Checkout') }}</h1>
          <Link :href="route('products.index')" class="checkout-back">
            ← {{ t('cart.empty_cta', 'Continue Shopping') }}
          </Link>
        </div>

        <form @submit.prevent="submit" class="checkout-layout">

          <!-- ═══════════════ LEFT COLUMN ═══════════════ -->
          <div class="checkout-form-col">

            <!-- ── Contact Details ── -->
            <div class="form-card">
              <h2 class="form-card-title">{{ t('checkout.company_details', 'Delivery Details') }}</h2>

              <div class="form-row">
                <div class="form-field">
                  <label class="field-label">{{ t('checkout.contact_person', 'Contact Person') }} *</label>
                  <input v-model="form.contact_person" type="text" class="field-input-light"
                         :placeholder="t('checkout.contact_person', 'Full Name')" />
                  <span v-if="form.errors.contact_person" class="form-error">{{ form.errors.contact_person }}</span>
                </div>
                <div class="form-field">
                  <label class="field-label">{{ t('checkout.phone', 'Phone') }} *</label>
                  <input v-model="form.contact_phone" type="tel" class="field-input-light"
                         :placeholder="isAr ? '01xxxxxxxxx' : '01xxxxxxxxx'" dir="ltr" />
                  <span v-if="form.errors.contact_phone" class="form-error">{{ form.errors.contact_phone }}</span>
                </div>
              </div>

              <div class="form-field">
                <label class="field-label">{{ t('checkout.company_name', 'Company Name') }} ({{ t('checkout.optional', 'Optional') }})</label>
                <input v-model="form.company_name" type="text" class="field-input-light"
                       :placeholder="t('checkout.company_name', 'Company name (optional)')" />
              </div>
            </div>

            <!-- ── Delivery ── -->
            <div class="form-card">
              <h2 class="form-card-title">{{ t('checkout.delivery_title', 'Delivery') }}</h2>

              <!-- Governorate / City dependent dropdowns -->
              <div class="form-row" v-if="governorates.length">
                <div class="form-field">
                  <label class="field-label">{{ t('checkout.governorate', 'Governorate') }} *</label>
                  <select v-model="form.governorate_id" class="field-input-light field-select">
                    <option value="">{{ t('checkout.select_governorate', 'Select Governorate') }}</option>
                    <option v-for="g in governorates" :key="g.id" :value="g.id">
                      {{ isAr ? g.name_ar : g.name_en }}
                    </option>
                  </select>
                  <span v-if="form.errors.governorate_id" class="form-error">{{ form.errors.governorate_id }}</span>
                </div>
                <div class="form-field">
                  <label class="field-label">{{ t('checkout.city', 'City') }} *</label>
                  <select v-model="form.city_id" class="field-input-light field-select"
                          :disabled="!form.governorate_id || !availableCities.length">
                    <option value="">{{ form.governorate_id ? t('checkout.select_city', 'Select City') : t('checkout.select_governorate_first', 'Select governorate first') }}</option>
                    <option v-for="c in availableCities" :key="c.id" :value="c.id">
                      {{ isAr ? c.name_ar : c.name_en }}
                    </option>
                  </select>
                  <span v-if="form.errors.city_id" class="form-error">{{ form.errors.city_id }}</span>
                  <!-- Live fee hint -->
                  <span v-if="selectedCity" class="city-fee-hint">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/></svg>
                    {{ t('checkout.delivery_fee', 'Delivery Fee') }}: <strong>{{ fmt(selectedCity.delivery_fee) }}</strong>
                  </span>
                </div>
              </div>

              <!-- Legacy region dropdown (shown if no governorates) -->
              <div v-else class="form-row">
                <div class="form-field">
                  <label class="field-label">{{ t('checkout.region', 'Region') }} *</label>
                  <select v-model="form.region_id" class="field-input-light field-select">
                    <option value="">{{ t('region.select', 'Select region') }}</option>
                    <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
                  </select>
                  <span v-if="form.errors.region_id" class="form-error">{{ form.errors.region_id }}</span>
                </div>
              </div>

              <div v-if="cart.requires_delivery_slot">
                <div class="form-field" style="margin-top: 24px;">
                  <label class="field-label">{{ t('checkout.date', 'Preferred Date') }} *</label>
                  <template v-if="uniqueDates.length">
                    <select v-model="form.delivery_date" class="field-input-light field-select">
                      <option value="">{{ t('checkout.select_date', 'Select date') }}</option>
                      <option v-for="d in uniqueDates" :key="d.value" :value="d.value">{{ d.label }}</option>
                    </select>
                  </template>
                  <template v-else>
                    <input type="date" v-model="form.delivery_date" class="field-input-light" :min="new Date().toISOString().split('T')[0]" />
                  </template>
                  <span v-if="form.errors.delivery_date" class="form-error">{{ form.errors.delivery_date }}</span>
                </div>

                <div v-if="slotsForDate.length" class="form-field" style="margin-top: 24px;">
                  <label class="field-label">{{ t('checkout.time_slot', 'Time Slot') }}</label>
                  <div class="slot-grid">
                    <button
                      v-for="slot in slotsForDate"
                      :key="slot.id"
                      type="button"
                      :class="['slot-btn', { active: form.delivery_slot_id === slot.id }]"
                      @click="form.delivery_slot_id = slot.id"
                    >
                      {{ slot.time }}
                      <span class="slot-avail">{{ slot.available }} {{ t('checkout.slots_left', 'left') }}</span>
                    </button>
                  </div>
                </div>
              </div>

              <div v-if="cart.requires_delivery_time" class="form-field">
                <label class="field-label">{{ t('checkout.preferred_time', 'Preferred Delivery Time') }} *</label>
                <input v-model="form.preferred_delivery_time" type="time" class="field-input-light" required />
                <span v-if="form.errors.preferred_delivery_time" class="form-error">{{ form.errors.preferred_delivery_time }}</span>
              </div>

              <div class="form-field">
                <label class="field-label">{{ t('checkout.address', 'Delivery Address') }} *</label>
                <textarea v-model="form.address" class="field-input-light field-textarea" rows="3"
                          :placeholder="t('checkout.address_placeholder', 'Building, street, district details…')"></textarea>
                <span v-if="form.errors.address" class="form-error">{{ form.errors.address }}</span>
              </div>

              <div class="form-field">
                <label class="field-label">{{ t('checkout.instructions', 'Special Instructions') }}</label>
                <textarea v-model="form.notes" class="field-input-light field-textarea" rows="2"
                          :placeholder="t('checkout.instructions_placeholder', 'Any delivery notes…')"></textarea>
              </div>
            </div>

            <!-- ── Gift Options ── -->
            <div class="form-card">
              <label class="toggle-row">
                <input v-model="form.is_gift" type="checkbox" class="toggle-checkbox" />
                <span class="toggle-track"><span class="toggle-thumb"></span></span>
                <div class="toggle-label">
                  <span class="toggle-title">{{ t('checkout.is_gift', 'This is a gift') }}</span>
                  <span class="toggle-sub">{{ t('checkout.gift_sub', 'Add recipient details, message & surprise option') }}</span>
                </div>
              </label>

              <transition name="gift-expand">
                <div v-if="form.is_gift" class="gift-fields">
                  <div class="form-row">
                    <div class="form-field">
                      <label class="field-label">{{ t('checkout.recipient_name', 'Recipient Name') }} *</label>
                      <input v-model="form.recipient_name" type="text" class="field-input-light" />
                      <span v-if="form.errors.recipient_name" class="form-error">{{ form.errors.recipient_name }}</span>
                    </div>
                    <div class="form-field">
                      <label class="field-label">{{ t('checkout.recipient_phone', 'Recipient Phone') }} *</label>
                      <input v-model="form.recipient_phone" type="tel" class="field-input-light" dir="ltr" />
                      <span v-if="form.errors.recipient_phone" class="form-error">{{ form.errors.recipient_phone }}</span>
                    </div>
                  </div>

                  <div class="form-field">
                    <label class="field-label">{{ t('checkout.recipient_address', 'Recipient Address') }} *</label>
                    <textarea v-model="form.recipient_address" class="field-input-light field-textarea" rows="2"></textarea>
                    <span v-if="form.errors.recipient_address" class="form-error">{{ form.errors.recipient_address }}</span>
                  </div>

                  <div class="form-field">
                    <label class="field-label">{{ t('checkout.gift_message', 'Gift Message') }}</label>
                    <textarea v-model="form.gift_message" class="field-input-light field-textarea" rows="3"
                              :placeholder="t('checkout.gift_message_placeholder', 'Write a personal message…')"></textarea>
                  </div>

                  <label class="surprise-row">
                    <input v-model="form.is_surprise" type="checkbox" class="addon-checkbox" />
                    <span class="surprise-text">{{ t('checkout.surprise_label', 'Hide my identity from recipient (Surprise Mode)') }}</span>
                  </label>
                </div>
              </transition>
            </div>

            <!-- ── Payment ── -->
            <div class="form-card">
              <h2 class="form-card-title">{{ t('checkout.payment_method', 'Payment Method') }}</h2>
              <div class="payment-options">
                <label class="payment-option" :class="{ active: form.payment_method === 'cod' }">
                  <input v-model="form.payment_method" type="radio" value="cod" />
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                    <path d="M2 10h20"/>
                  </svg>
                  <span>{{ t('checkout.cod', 'Cash on Delivery') }}</span>
                </label>
                <label class="payment-option disabled-option">
                  <input type="radio" disabled />
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                    <path d="M2 10h20"/><path d="M6 15h3M15 15h3"/>
                  </svg>
                  <span>Visa / Mastercard</span>
                  <span class="badge-soon">{{ t('checkout.soon', 'Coming Soon') }}</span>
                </label>
              </div>
            </div>

          </div>

          <!-- ═══════════════ RIGHT COLUMN ═══════════════ -->
          <div class="checkout-summary-col">
            <div class="summary-card">
              <h2 class="form-card-title">{{ t('checkout.order_summary', 'Order Summary') }}</h2>

              <!-- Items -->
              <div class="summary-items">
                <div v-for="item in cart.items" :key="item.id" class="summary-item">
                  <div class="summary-item-name">
                    <span class="summary-item-title">{{ item.product_name }}</span>
                    <span v-if="item.variation_name" class="summary-item-variant">{{ item.variation_name }}</span>
                    <span class="summary-item-sku">{{ item.product_sku }}</span>
                  </div>
                  <div class="summary-item-right">
                    <span class="summary-item-qty">×{{ item.quantity }}</span>
                    <span class="summary-item-price">{{ fmt(item.total_price) }}</span>
                  </div>
                </div>
              </div>

              <!-- Totals -->
              <div class="summary-totals">
                <div class="summary-row">
                  <span>{{ t('checkout.subtotal', 'Subtotal') }}</span>
                  <span>{{ fmt(cart.subtotal) }}</span>
                </div>
                <div class="summary-row">
                  <span>{{ t('checkout.delivery_fee', 'Delivery Fee') }}</span>
                  <span :class="{ 'fee-highlight': selectedCity }">{{ fmt(deliveryFee) }}</span>
                </div>
                <div v-if="selectedCity" class="summary-row city-label-row">
                  <span style="font-size:11px; color:var(--text-light);">
                    {{ isAr ? selectedCity.name_ar : selectedCity.name_en }}
                  </span>
                </div>
                <div class="summary-row">
                  <span>{{ t('checkout.tax', 'VAT (15%)') }}</span>
                  <span>{{ fmt(tax) }}</span>
                </div>
                <div class="summary-row summary-total">
                  <span>{{ t('checkout.total', 'Total') }}</span>
                  <span>{{ fmt(parseFloat(total)) }}</span>
                </div>
              </div>

              <!-- Terms -->
              <label class="terms-row">
                <input v-model="form.terms" type="checkbox" class="addon-checkbox" />
                <span class="terms-text">{{ t('checkout.terms', 'I agree to the Terms & Conditions') }}</span>
              </label>
              <span v-if="form.errors.terms" class="form-error">{{ form.errors.terms }}</span>

              <!-- Submit -->
              <button
                type="submit"
                class="btn-place-order"
                :disabled="form.processing || !form.terms"
              >
                <span v-if="form.processing">{{ t('checkout.processing', 'Processing…') }}</span>
                <span v-else>{{ t('checkout.place_order', 'Place Order') }}</span>
              </button>

              <!-- Security note -->
              <p class="security-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                {{ t('checkout.secure_note', 'Secure & encrypted checkout') }}
              </p>
            </div>
          </div>

        </form>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.checkout-page {
  padding-top: 0;
  min-height: 100vh;
  background: var(--cream-light);
}

.checkout-inner {
  max-width: 1100px;
  margin-inline: auto;
  padding: 48px var(--section-padding-inline) 80px;
}

.checkout-header {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  margin-bottom: 36px;
  flex-wrap: wrap;
  gap: 12px;
}

.checkout-title {
  font-family: var(--font-serif);
  font-size: clamp(26px, 3vw, 36px);
  font-weight: 400;
  color: var(--dark);
}

.checkout-back {
  font-size: 11px;
  font-weight: 500;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--text-light);
  transition: color 0.2s;
}
.checkout-back:hover { color: var(--gold); }
[dir='rtl'] .checkout-back { letter-spacing: 0; }

/* ── Layout ── */
.checkout-layout {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 28px;
  align-items: start;
}

/* ── Cards ── */
.form-card,
.summary-card {
  background: var(--white);
  border: 1px solid rgba(58, 53, 48, 0.08);
  border-radius: 8px;
  padding: 28px 28px 24px;
  margin-bottom: 16px;
}

.form-card-title {
  font-family: var(--font-serif);
  font-size: 18px;
  font-weight: 400;
  color: var(--dark);
  margin-bottom: 20px;
}

/* ── Form Fields ── */
.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.form-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 16px;
}

.field-label {
  font-size: 10px;
  font-weight: 600;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: var(--charcoal);
}
[dir='rtl'] .field-label { letter-spacing: 0; }

.field-input-light {
  width: 100%;
  background: var(--cream-light);
  border: 1px solid rgba(58, 53, 48, 0.15);
  color: var(--dark);
  font-family: var(--font-sans);
  font-size: 14px;
  font-weight: 300;
  padding: 11px 14px;
  outline: none;
  border-radius: 4px;
  transition: border-color 0.2s;
}
.field-input-light:focus { border-color: var(--gold); }
.field-input-light:disabled { opacity: 0.5; cursor: not-allowed; }

.field-select { appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%237a7570' stroke-width='1.5' fill='none'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-inline-end: 32px; }
[dir='rtl'] .field-select { background-position: left 12px center; }

.field-textarea { resize: vertical; min-height: 70px; }

.form-error { font-size: 11px; color: #dc2626; }

/* City delivery fee hint */
.city-fee-hint {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 11px;
  color: var(--gold);
  font-weight: 500;
  padding: 4px 0 0;
}
.city-fee-hint strong { font-weight: 700; }

/* ── Slot Grid ── */
.slot-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 8px; }

.slot-btn {
  display: flex; flex-direction: column; align-items: center; gap: 3px;
  padding: 10px 12px; border: 1.5px solid rgba(58, 53, 48, 0.15); border-radius: 4px;
  background: var(--white); cursor: pointer; font-size: 12px; font-weight: 500;
  color: var(--charcoal); transition: border-color 0.2s, background 0.2s;
}
.slot-btn:hover { border-color: var(--gold); }
.slot-btn.active { border-color: var(--gold); background: rgba(201,168,76,0.08); color: var(--dark); }
.slot-avail { font-size: 9px; font-weight: 400; color: var(--text-light); }

/* ── Toggle (gift) ── */
.toggle-row { display: flex; align-items: center; gap: 14px; cursor: pointer; user-select: none; padding: 4px 0; }
.toggle-checkbox { display: none; }
.toggle-track { width: 44px; height: 24px; background: rgba(58,53,48,0.15); border-radius: 12px; position: relative; flex-shrink: 0; transition: background 0.2s; }
.toggle-checkbox:checked + .toggle-track { background: var(--gold); }
.toggle-thumb { position: absolute; top: 3px; inset-inline-start: 3px; width: 18px; height: 18px; background: var(--white); border-radius: 50%; transition: inset-inline-start 0.2s; box-shadow: 0 1px 4px rgba(0,0,0,0.15); }
.toggle-checkbox:checked + .toggle-track .toggle-thumb { inset-inline-start: calc(100% - 21px); }
.toggle-title { font-size: 14px; font-weight: 500; color: var(--dark); display: block; }
.toggle-sub { font-size: 12px; font-weight: 300; color: var(--text-light); display: block; }
.toggle-label { display: flex; flex-direction: column; gap: 2px; }

.gift-fields { margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(58,53,48,0.08); }
.gift-expand-enter-active, .gift-expand-leave-active { transition: all 0.3s ease; overflow: hidden; }
.gift-expand-enter-from, .gift-expand-leave-to { opacity: 0; max-height: 0; }
.gift-expand-enter-to, .gift-expand-leave-from { opacity: 1; max-height: 600px; }

.surprise-row { display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; color: var(--charcoal); }
.addon-checkbox { width: 16px; height: 16px; accent-color: var(--gold); cursor: pointer; flex-shrink: 0; }

/* ── Payment ── */
.payment-options { display: flex; gap: 12px; flex-wrap: wrap; }
.payment-option {
  display: flex; align-items: center; gap: 10px; padding: 12px 18px;
  border: 1.5px solid rgba(58, 53, 48, 0.15); border-radius: 6px; cursor: pointer;
  font-size: 13px; font-weight: 500; color: var(--charcoal); transition: border-color 0.2s, background 0.2s;
  flex: 1; min-width: 140px; position: relative;
}
.payment-option input[type="radio"] { display: none; }
.payment-option.active { border-color: var(--gold); background: rgba(201,168,76,0.06); color: var(--dark); }
.payment-option svg { width: 20px; height: 20px; flex-shrink: 0; }
.payment-option.disabled-option { opacity: 0.6; cursor: not-allowed; background: var(--cream-light); }
.badge-soon { position: absolute; top: -8px; inset-inline-end: -8px; background: var(--gold); color: #fff; font-size: 10px; font-weight: 600; padding: 3px 6px; border-radius: 4px; }

/* ── Summary ── */
.summary-items { border-block: 1px solid rgba(58, 53, 48, 0.08); margin-block: 16px; padding-block: 16px; display: flex; flex-direction: column; gap: 12px; }
.summary-item { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
.summary-item-name { display: flex; flex-direction: column; gap: 2px; flex: 1; }
.summary-item-title { font-size: 13px; font-weight: 500; color: var(--dark); line-height: 1.3; }
.summary-item-variant, .summary-item-sku { font-size: 11px; color: var(--text-light); }
.summary-item-right { display: flex; flex-direction: column; align-items: flex-end; gap: 2px; flex-shrink: 0; }
.summary-item-qty { font-size: 11px; color: var(--text-light); }
.summary-item-price { font-size: 13px; font-weight: 500; color: var(--dark); }

.summary-totals { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
.summary-row { display: flex; justify-content: space-between; font-size: 13px; color: var(--charcoal); }
.city-label-row { padding-top: 0; margin-top: -6px; }
.fee-highlight { color: var(--gold); font-weight: 600; }
.summary-total { border-top: 1px solid rgba(58, 53, 48, 0.12); padding-top: 12px; margin-top: 4px; font-size: 16px; font-weight: 600; color: var(--dark); }

.terms-row { display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 12px; color: var(--charcoal); margin-bottom: 16px; }
.terms-text { line-height: 1.4; }

/* ── Submit Button ── */
.btn-place-order {
  width: 100%; padding: 16px; background: var(--gold-btn); color: var(--white);
  font-family: var(--font-sans); font-size: 11px; font-weight: 600; letter-spacing: 0.2em;
  text-transform: uppercase; border: none; border-radius: 3px; cursor: pointer;
  transition: background 0.25s, transform 0.2s, box-shadow 0.25s; margin-bottom: 16px;
}
[dir='rtl'] .btn-place-order { letter-spacing: 0; }
.btn-place-order:hover:not(:disabled) { background: var(--gold); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(184, 150, 62, 0.35); }
.btn-place-order:disabled { opacity: 0.5; cursor: not-allowed; }

.security-note { display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 11px; color: var(--text-light); font-weight: 300; }
.security-note svg { width: 13px; height: 13px; flex-shrink: 0; }

/* ── Responsive ── */
@media (max-width: 900px) {
  .checkout-layout { grid-template-columns: 1fr; }
  .checkout-summary-col { order: -1; }
}
@media (max-width: 600px) {
  .form-row { grid-template-columns: 1fr; }
  .form-card, .summary-card { padding: 20px 16px; }
}
</style>
