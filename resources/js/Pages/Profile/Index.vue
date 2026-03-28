<script setup>
import { ref, computed, watch } from 'vue'
import { Link, usePage, useForm, router } from '@inertiajs/vue3'
import AccountLayout from '@/Layouts/AccountLayout.vue'
import { useCms } from '@/Composables/useCms'
import BrandMark from '@/Components/BrandMark.vue'
import { useCurrency } from '@/Composables/useCurrency'

const props = defineProps({
    orders: { type: Object, required: true },
    addresses: { type: Array, default: () => [] },
    initialTab: { type: String, default: 'orders' },
    user: { type: Object, required: true },
})

const page = usePage()
const { locale } = useCms()
const { fmt } = useCurrency()
const isAr = computed(() => locale.value === 'ar')

const activeSection = ref(props.initialTab)

watch(
    () => props.initialTab,
    (v) => {
        activeSection.value = v
    },
)

/**
 * Instant section switch: rAF avoids layout thrash; History API updates URL without Inertia (no full-page jitter).
 */
function goSection(id) {
    requestAnimationFrame(() => {
        activeSection.value = id
        const url = new URL(window.location.href)
        url.searchParams.set('tab', id)
        window.history.replaceState({}, '', url.toString())
    })
}

function logoutAccount() {
    router.post(route('customer.logout'))
}

const statusLabels = {
    pending: { en: 'Pending', ar: 'معلق' },
    paid: { en: 'Paid', ar: 'مدفوع' },
    delivered: { en: 'Delivered', ar: 'تم التوصيل' },
    cancelled: { en: 'Cancelled', ar: 'ملغي' },
}

function statusText(status) {
    const s = statusLabels[status] || { en: status, ar: status }
    return isAr.value ? s.ar : s.en
}

const regions = computed(() => page.props.regions ?? [])

const showForm = ref(false)
const editingId = ref(null)

const form = useForm({
    label: 'home',
    recipient_name: '',
    phone: '',
    region_id: '',
    address_line: '',
    city: '',
    building: '',
    floor: '',
    apartment: '',
    landmark: '',
    is_default: false,
})

function openNew() {
    editingId.value = null
    form.reset()
    showForm.value = true
}

function openEdit(addr) {
    editingId.value = addr.id
    form.label = addr.label
    form.recipient_name = addr.recipient_name
    form.phone = addr.phone
    form.region_id = addr.region_id ?? ''
    form.address_line = addr.address_line
    form.city = addr.city ?? ''
    form.building = addr.building ?? ''
    form.floor = addr.floor ?? ''
    form.apartment = addr.apartment ?? ''
    form.landmark = addr.landmark ?? ''
    form.is_default = addr.is_default
    showForm.value = true
}

function submitAddress() {
    if (editingId.value) {
        form.put(route('profile.addresses.update', editingId.value), {
            onSuccess: () => {
                showForm.value = false
                editingId.value = null
            },
        })
    } else {
        form.post(route('profile.addresses.store'), {
            onSuccess: () => {
                showForm.value = false
                form.reset()
            },
        })
    }
}

function deleteAddress(id) {
    if (confirm(isAr.value ? 'هل أنت متأكد من حذف هذا العنوان؟' : 'Delete this address?')) {
        router.delete(route('profile.addresses.destroy', id))
    }
}

const labelOptions = [
    { value: 'home', en: 'Home', ar: 'المنزل' },
    { value: 'work', en: 'Work', ar: 'العمل' },
    { value: 'other', en: 'Other', ar: 'أخرى' },
]

const sidebarItems = computed(() => [
    { id: 'profile', icon: 'person', labelEn: 'Profile', labelAr: 'الملف الشخصي' },
    { id: 'orders', icon: 'receipt_long', labelEn: 'Orders', labelAr: 'الطلبات' },
    { id: 'addresses', icon: 'location_on', labelEn: 'Addresses', labelAr: 'العناوين' },
])

function sidebarLabel(item) {
    return isAr.value ? item.labelAr : item.labelEn
}
</script>

<template>
  <AccountLayout :user="user">
    <div class="account-dashboard-grid font-body">
      <!-- Desktop: permanent sidebar -->
      <aside class="account-sidebar" aria-label="Account navigation">
        <div class="account-sidebar-inner">
          <p class="account-sidebar-eyebrow flex justify-center font-account-serif">
            <BrandMark
              :as-link="false"
              img-class="h-8 w-auto max-w-[min(160px,85%)] object-contain"
              text-class="text-center text-base font-account-serif text-[var(--ink,#252320)]"
            />
          </p>
          <nav class="account-sidebar-nav" role="navigation">
            <button
              v-for="item in sidebarItems"
              :key="item.id"
              type="button"
              class="profile-nav-item"
              :class="{ 'is-active': activeSection === item.id }"
              @click="goSection(item.id)"
            >
              <span class="material-symbols-outlined profile-nav-icon" aria-hidden="true">{{ item.icon }}</span>
              <span class="profile-nav-text">{{ sidebarLabel(item) }}</span>
            </button>
          </nav>
          <button type="button" class="account-nav-logout" @click="logoutAccount">
            <span class="material-symbols-outlined profile-nav-icon" aria-hidden="true">logout</span>
            <span class="profile-nav-text">{{ isAr ? 'تسجيل الخروج' : 'Log out' }}</span>
          </button>
        </div>
      </aside>

      <div class="account-dashboard-main">
        <div class="account-main-inner">
          <header class="account-page-hero">
            <h1 class="account-page-title font-account-serif">
              {{ isAr ? 'حسابي' : 'My Account' }}
            </h1>
            <p class="account-greeting font-account-serif">
              {{ isAr ? `مرحبًا، ${user.name}` : `Hello, ${user.name}` }}
            </p>
            <p class="account-page-email">{{ user.email }}</p>
          </header>

          <div class="profile-main-card rounded-2xl bg-white p-6 shadow-[0_12px_40px_-12px_rgba(37,35,32,0.12)] md:p-10 md:px-12">
            <header
              v-show="['orders', 'addresses'].includes(activeSection)"
              class="profile-card-head mb-8 flex flex-col gap-6 border-b border-stone-200/60 pb-6"
            >
              <!-- Orders / Addresses: elegant text links (instant toggle) -->
              <div class="flex flex-wrap gap-8" role="tablist">
                <button
                  type="button"
                  role="tab"
                  class="account-link-tab"
                  :class="{ 'is-active': activeSection === 'orders' }"
                  :aria-selected="activeSection === 'orders'"
                  @click="goSection('orders')"
                >
                  {{ isAr ? 'الطلبات' : 'Orders' }}
                </button>
                <button
                  type="button"
                  role="tab"
                  class="account-link-tab"
                  :class="{ 'is-active': activeSection === 'addresses' }"
                  :aria-selected="activeSection === 'addresses'"
                  @click="goSection('addresses')"
                >
                  {{ isAr ? 'العناوين' : 'Addresses' }}
                </button>
              </div>
            </header>

          <!-- Profile -->
          <div v-show="activeSection === 'profile'" class="profile-section font-body text-sm text-[var(--ink-muted,#5a5550)]">
            <dl class="account-dl grid gap-4 sm:grid-cols-2">
              <div>
                <dt class="account-dt">{{ isAr ? 'الاسم' : 'Name' }}</dt>
                <dd class="account-dd text-[var(--ink,#252320)]">{{ user.name }}</dd>
              </div>
              <div>
                <dt class="account-dt">{{ isAr ? 'البريد' : 'Email' }}</dt>
                <dd class="account-dd text-[var(--ink,#252320)]">{{ user.email }}</dd>
              </div>
              <div v-if="user.phone" class="sm:col-span-2">
                <dt class="account-dt">{{ isAr ? 'الهاتف' : 'Phone' }}</dt>
                <dd class="account-dd text-[var(--ink,#252320)]" dir="ltr">{{ user.phone }}</dd>
              </div>
            </dl>
          </div>

          <!-- Orders -->
          <div v-show="activeSection === 'orders'">
            <div v-if="orders.data.length === 0" class="orders-empty-stage">
              <div class="orders-empty">
                <div class="orders-empty-blob-layer" aria-hidden="true">
                  <div class="orders-empty-blob"></div>
                </div>
                <div class="orders-empty-icon-wrap">
                  <span class="material-symbols-outlined orders-empty-icon">local_florist</span>
                </div>
                <p class="orders-empty-title font-account-serif text-xl text-[var(--ink,#252320)]">
                  {{ isAr ? 'لا توجد طلبات بعد' : 'No orders yet' }}
                </p>
                <p class="orders-empty-hint mt-2 flex max-w-sm flex-wrap items-center justify-center gap-x-1 text-center text-sm text-[var(--ink-muted,#5a5550)]">
                  <template v-if="isAr">
                    <span>اكتشف مجموعتنا وابدأ رحلتك مع</span>
                    <BrandMark inline :as-link="false" />
                    <span>.</span>
                  </template>
                  <template v-else>
                    <span>Discover our collection and begin your journey with</span>
                    <BrandMark inline :as-link="false" />
                    <span>.</span>
                  </template>
                </p>
                <Link :href="route('products.index')" class="account-cta-gold mt-8">
                  {{ isAr ? 'ابدأ التسوق' : 'Start shopping' }}
                </Link>
              </div>
            </div>

            <template v-else>
              <div class="orders-list flex flex-col gap-4">
                <div v-for="order in orders.data" :key="order.id" class="order-card">
                  <div class="order-card-header">
                    <div>
                      <span class="order-number">{{ order.order_number }}</span>
                      <span class="order-date">{{ order.created_at }}</span>
                    </div>
                    <span class="order-status" :class="order.status">{{ statusText(order.status) }}</span>
                  </div>
                  <div class="order-items">
                    <div v-for="item in order.items" :key="item.product_sku" class="order-item-row">
                      <div class="order-item-info">
                        <span class="order-item-name">{{ item.product_name }}</span>
                        <span class="order-item-sku">{{ item.product_sku }}</span>
                      </div>
                      <div class="order-item-nums">
                        <span class="order-item-qty">×{{ item.quantity }}</span>
                        <span class="order-item-price">{{ fmt(item.total_price) }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="order-card-footer">
                    <span>{{ isAr ? 'المنطقة' : 'Region' }}: {{ order.region }}</span>
                    <span class="order-total font-account-serif">{{ fmt(order.total) }}</span>
                  </div>
                </div>
              </div>

              <div v-if="orders.links?.length > 3" class="pagination">
                <Link
                  v-for="link in orders.links"
                  :key="link.label"
                  :href="link.url || '#'"
                  :class="['page-link', { active: link.active, disabled: !link.url }]"
                  v-html="link.label"
                  preserve-scroll
                />
              </div>
            </template>
          </div>

          <!-- Addresses -->
          <div v-show="activeSection === 'addresses'">
            <button v-if="!showForm" type="button" class="account-cta-gold account-cta-gold--compact mb-6" @click="openNew">
              + {{ isAr ? 'إضافة عنوان جديد' : 'Add new address' }}
            </button>

            <transition name="gift-expand">
              <div v-if="showForm" class="addr-form-card">
                <h3 class="form-card-title">
                  {{ editingId ? (isAr ? 'تعديل العنوان' : 'Edit address') : (isAr ? 'عنوان جديد' : 'New address') }}
                </h3>
                <form @submit.prevent="submitAddress">
                  <div class="form-row">
                    <div class="form-field">
                      <label class="field-label">{{ isAr ? 'النوع' : 'Label' }}</label>
                      <select v-model="form.label" class="field-input-light field-select">
                        <option v-for="l in labelOptions" :key="l.value" :value="l.value">{{ isAr ? l.ar : l.en }}</option>
                      </select>
                    </div>
                    <div class="form-field">
                      <label class="field-label">{{ isAr ? 'المنطقة' : 'Region' }} *</label>
                      <select v-model="form.region_id" class="field-input-light field-select" required>
                        <option value="">{{ isAr ? 'اختر المنطقة' : 'Select region' }}</option>
                        <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
                      </select>
                      <span v-if="form.errors.region_id" class="form-error">{{ form.errors.region_id }}</span>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-field">
                      <label class="field-label">{{ isAr ? 'اسم المستلم' : 'Recipient name' }} *</label>
                      <input v-model="form.recipient_name" type="text" class="field-input-light" required />
                      <span v-if="form.errors.recipient_name" class="form-error">{{ form.errors.recipient_name }}</span>
                    </div>
                    <div class="form-field">
                      <label class="field-label">{{ isAr ? 'رقم الهاتف' : 'Phone' }} *</label>
                      <input v-model="form.phone" type="tel" class="field-input-light" placeholder="01012345678" dir="ltr" required />
                      <span v-if="form.errors.phone" class="form-error">{{ form.errors.phone }}</span>
                    </div>
                  </div>

                  <div class="form-field">
                    <label class="field-label">{{ isAr ? 'العنوان' : 'Address' }} *</label>
                    <textarea v-model="form.address_line" class="field-input-light field-textarea" rows="2" required />
                    <span v-if="form.errors.address_line" class="form-error">{{ form.errors.address_line }}</span>
                  </div>

                  <div class="form-row">
                    <div class="form-field"><label class="field-label">{{ isAr ? 'المدينة' : 'City' }}</label><input v-model="form.city" type="text" class="field-input-light" /></div>
                    <div class="form-field"><label class="field-label">{{ isAr ? 'المبنى' : 'Building' }}</label><input v-model="form.building" type="text" class="field-input-light" /></div>
                  </div>

                  <div class="form-row">
                    <div class="form-field"><label class="field-label">{{ isAr ? 'الطابق' : 'Floor' }}</label><input v-model="form.floor" type="text" class="field-input-light" /></div>
                    <div class="form-field"><label class="field-label">{{ isAr ? 'الشقة' : 'Apartment' }}</label><input v-model="form.apartment" type="text" class="field-input-light" /></div>
                  </div>

                  <div class="form-field">
                    <label class="field-label">{{ isAr ? 'علامة مميزة' : 'Landmark' }}</label>
                    <input v-model="form.landmark" type="text" class="field-input-light" />
                  </div>

                  <label class="surprise-row" style="margin-bottom: 16px;">
                    <input v-model="form.is_default" type="checkbox" class="addon-checkbox" />
                    <span>{{ isAr ? 'عنوان افتراضي' : 'Set as default' }}</span>
                  </label>

                  <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-primary" :disabled="form.processing" style="flex:1;">
                      {{ form.processing ? '...' : (editingId ? (isAr ? 'حفظ التعديلات' : 'Save') : (isAr ? 'إضافة' : 'Add')) }}
                    </button>
                    <button type="button" class="btn-outline" style="flex:0.5;" @click="showForm = false">
                      {{ isAr ? 'إلغاء' : 'Cancel' }}
                    </button>
                  </div>
                </form>
              </div>
            </transition>

            <div class="addr-list">
              <div v-for="addr in addresses" :key="addr.id" class="addr-card">
                <div class="addr-card-top">
                  <div>
                    <span class="addr-label">{{ addr.label }}</span>
                    <span v-if="addr.is_default" class="addr-default">{{ isAr ? 'افتراضي' : 'Default' }}</span>
                  </div>
                  <div class="addr-actions">
                    <button type="button" class="addr-action" @click="openEdit(addr)">{{ isAr ? 'تعديل' : 'Edit' }}</button>
                    <button type="button" class="addr-action danger" @click="deleteAddress(addr.id)">{{ isAr ? 'حذف' : 'Delete' }}</button>
                  </div>
                </div>
                <p class="addr-name">{{ addr.recipient_name }} — {{ addr.phone }}</p>
                <p class="addr-text">{{ addr.address_line }}</p>
                <p v-if="addr.region_name" class="addr-region">{{ addr.region_name }}</p>
              </div>
            </div>

            <div v-if="!addresses.length && !showForm" class="profile-empty-muted text-center text-sm text-[var(--ink-muted,#5a5550)]">
              <p>{{ isAr ? 'لا توجد عناوين محفوظة' : 'No saved addresses' }}</p>
            </div>
          </div>

          </div>
        </div>
      </div>

      <!-- Mobile: bottom navigation -->
      <nav class="account-bottom-nav lg:hidden" aria-label="Account quick navigation">
        <button
          v-for="item in sidebarItems"
          :key="item.id"
          type="button"
          class="account-bottom-nav-item"
          :class="{ 'is-active': activeSection === item.id }"
          @click="goSection(item.id)"
        >
          <span class="material-symbols-outlined">{{ item.icon }}</span>
          <span class="account-bottom-nav-label">{{ sidebarLabel(item) }}</span>
        </button>
        <button type="button" class="account-bottom-nav-item" @click="logoutAccount">
          <span class="material-symbols-outlined">logout</span>
          <span class="account-bottom-nav-label">{{ isAr ? 'خروج' : 'Log out' }}</span>
        </button>
      </nav>
    </div>
  </AccountLayout>
</template>

<style scoped>
.font-account-serif {
    font-family: "Cormorant Garamond", var(--font-serif), Georgia, serif;
}

.account-dashboard-grid {
    --account-sidebar-w: 272px;
    position: relative;
    min-height: calc(100vh - var(--account-header-h, 3.5rem));
    padding-bottom: calc(4.25rem + env(safe-area-inset-bottom, 0px));
}

@media (min-width: 1024px) {
    .account-dashboard-grid {
        padding-bottom: 0;
    }
}

.account-sidebar {
    display: none;
}

@media (min-width: 1024px) {
    .account-sidebar {
        display: flex;
        position: fixed;
        top: 0;
        bottom: 0;
        inset-inline-start: 0;
        z-index: 50;
        width: var(--account-sidebar-w);
        flex-direction: column;
        background: var(--account-surface, var(--cream-light));
        border-inline-end: 1px solid rgba(58, 53, 48, 0.1);
        padding: 1.35rem 0;
    }
}

.account-sidebar-inner {
    display: flex;
    height: 100%;
    flex-direction: column;
    padding-inline: 1.35rem;
}

.account-sidebar-eyebrow {
    font-size: 1.35rem;
    font-weight: 500;
    font-style: italic;
    color: var(--account-primary, var(--accent));
    letter-spacing: 0.02em;
}

.account-sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
    margin-top: 1.75rem;
    flex: 1;
}

.account-nav-logout {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin-top: auto;
    padding-top: 1.25rem;
    border-top: 1px solid var(--line);
    background: none;
    border-inline: none;
    border-bottom: none;
    cursor: pointer;
    font-family: "Manrope", ui-sans-serif, system-ui, sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--ink-muted);
    transition: color 0.2s ease;
}

[dir="rtl"] .account-nav-logout {
    letter-spacing: 0.05em;
}

.account-nav-logout:hover {
    color: #a33a32;
}

.account-dashboard-main {
    min-height: calc(100vh - var(--account-header-h, 3.5rem));
    padding-inline: clamp(1.25rem, 4vw, 2.75rem);
    background-color: var(--account-surface, #fffcf7);
}

@media (min-width: 1024px) {
    .account-dashboard-main {
        margin-inline-start: var(--account-sidebar-w);
        max-width: 960px;
    }
}

.account-main-inner {
    margin-inline: auto;
    width: 100%;
    padding-top: clamp(1.5rem, 4vw, 2.75rem);
}

.account-page-hero {
    margin-bottom: clamp(2rem, 5vw, 3.25rem);
    padding-bottom: clamp(1.25rem, 3vw, 1.75rem);
    border-bottom: 1px solid
        color-mix(in srgb, var(--account-primary, var(--accent)) 38%, transparent);
}

.account-page-title {
    font-size: clamp(2.2rem, 5.5vw, 3rem);
    font-weight: 400;
    line-height: 1.12;
    color: var(--ink, #252320);
}

.account-greeting {
    margin-top: 1rem;
    font-size: clamp(1.4rem, 3.2vw, 1.85rem);
    font-weight: 400;
    font-style: italic;
    line-height: 1.35;
    color: var(--ink-muted, #5a5550);
}

.account-page-email {
    margin-top: 0.65rem;
    font-family: "Manrope", ui-sans-serif, system-ui, sans-serif;
    font-size: 14px;
    color: var(--ink-faint, #8a847b);
}

.account-bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 60;
    display: flex;
    align-items: stretch;
    justify-content: space-around;
    gap: 2px;
    padding: 0.4rem 0.2rem calc(0.45rem + env(safe-area-inset-bottom, 0px));
    border-top: 1px solid rgba(58, 53, 48, 0.08);
    background: rgba(255, 252, 247, 0.97);
    backdrop-filter: blur(12px);
}

.account-bottom-nav-item {
    display: flex;
    min-width: 0;
    flex: 1;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
    padding: 0.25rem;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--ink-muted);
    transition: color 0.2s ease;
}

.account-bottom-nav-item .material-symbols-outlined {
    font-size: 22px;
    font-variation-settings: "FILL" 0, "wght" 300;
}

.account-bottom-nav-item.is-active {
    color: var(--account-primary, var(--accent));
}

.account-bottom-nav-label {
    max-width: 100%;
    overflow: hidden;
    text-align: center;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 8px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

[dir="rtl"] .account-bottom-nav-label {
    letter-spacing: 0.04em;
}

.profile-nav-item {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.5rem 0;
    background: none;
    border: none;
    cursor: pointer;
    font-family: "Manrope", ui-sans-serif, system-ui, sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--ink-muted);
    text-align: start;
    position: relative;
    transition: color 0.25s ease;
}

[dir="rtl"] .profile-nav-item {
    text-align: right;
    letter-spacing: 0.06em;
}

.profile-nav-item::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--account-primary, var(--accent));
    transform: scaleX(0);
    transform-origin: left center;
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}

[dir="rtl"] .profile-nav-item::after {
    transform-origin: right center;
}

.profile-nav-item:hover,
.profile-nav-item.is-active {
    color: var(--account-primary, var(--accent));
}

.profile-nav-item:hover::after,
.profile-nav-item.is-active::after {
    transform: scaleX(1);
}

.profile-nav-icon {
    font-size: 22px;
    font-variation-settings: "FILL" 0, "wght" 300;
    opacity: 0.85;
}

.account-link-tab {
    position: relative;
    padding: 0 0 6px;
    background: none;
    border: none;
    cursor: pointer;
    font-family: "Manrope", ui-sans-serif, system-ui, sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--ink-muted);
    transition: color 0.25s ease;
}

[dir="rtl"] .account-link-tab {
    letter-spacing: 0.06em;
}

.account-link-tab::after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 1px;
    background: var(--account-primary, var(--accent));
    transform: scaleX(0);
    transform-origin: left center;
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}

[dir="rtl"] .account-link-tab::after {
    transform-origin: right center;
}

.account-link-tab:hover,
.account-link-tab.is-active {
    color: var(--account-primary, var(--accent));
}

.account-link-tab:hover::after,
.account-link-tab.is-active::after {
    transform: scaleX(1);
}

.account-cta-gold {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 46px;
    padding: 0 1.35rem;
    font-family: "Manrope", ui-sans-serif, system-ui, sans-serif;
    font-size: var(--text-sm);
    font-weight: 500;
    text-decoration: none;
    border-radius: var(--radius-sm);
    background: var(--account-primary, var(--accent));
    color: var(--white);
    border: 1px solid var(--accent-deep);
    transition:
        background var(--transition-fast),
        color var(--transition-fast),
        border-color var(--transition-fast);
}

.account-cta-gold:hover {
    background: var(--accent-deep);
    border-color: var(--accent-deep);
}

.account-cta-gold--compact {
    min-height: 40px;
    font-size: 12px;
}

.orders-empty-stage {
    display: flex;
    min-height: min(420px, 58vh);
    align-items: center;
    justify-content: center;
    padding: 1rem 0 2rem;
}

.orders-empty {
    position: relative;
    display: flex;
    width: 100%;
    max-width: 22rem;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem 1.25rem;
    text-align: center;
}

.orders-empty-blob-layer {
    position: absolute;
    inset: 0;
    z-index: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.orders-empty-blob {
    width: min(280px, 72vw);
    height: min(240px, 58vw);
    border-radius: 45% 55% 52% 48% / 48% 42% 58% 52%;
    background: linear-gradient(
        145deg,
        rgba(166, 124, 95, 0.14),
        rgba(107, 76, 59, 0.07)
    );
    filter: blur(0.5px);
    animation: account-blob-morph 14s ease-in-out infinite;
}

@keyframes account-blob-morph {
    0%,
    100% {
        border-radius: 45% 55% 52% 48% / 48% 42% 58% 52%;
        transform: rotate(0deg) scale(1);
    }
    33% {
        border-radius: 55% 45% 48% 52% / 55% 48% 45% 55%;
        transform: rotate(4deg) scale(1.03);
    }
    66% {
        border-radius: 48% 52% 55% 45% / 42% 55% 52% 48%;
        transform: rotate(-3deg) scale(0.98);
    }
}

.orders-empty-icon-wrap {
    position: relative;
    z-index: 1;
    margin-bottom: 1rem;
}

.orders-empty-icon {
    font-size: 2.5rem;
    color: var(--account-primary, var(--accent));
    font-variation-settings: "FILL" 0, "wght" 300;
}

.orders-empty-title,
.orders-empty-hint {
    position: relative;
    z-index: 1;
}

.account-dt {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--ink-faint);
    margin-bottom: 4px;
}

[dir="rtl"] .account-dt {
    letter-spacing: 0.05em;
}

.account-dd {
    font-size: 15px;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.order-card {
    background: var(--cream-light);
    border: 1px solid var(--line);
    border-radius: 12px;
    overflow: hidden;
}

.order-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid var(--line);
}

.order-number {
    font-weight: 600;
    font-size: 14px;
    color: var(--dark);
    margin-inline-end: 12px;
}

.order-date {
    font-size: 12px;
    color: var(--text-light);
}

.order-status {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
}

.order-status.pending {
    background: rgba(243, 156, 18, 0.1);
    color: #f39c12;
}

.order-status.paid {
    background: rgba(39, 174, 96, 0.1);
    color: #27ae60;
}

.order-status.delivered {
    background: rgba(41, 128, 185, 0.1);
    color: #2980b9;
}

.order-status.cancelled {
    background: rgba(192, 57, 43, 0.1);
    color: #c0392b;
}

.order-items {
    padding: 12px 20px;
    background: var(--white);
}

.order-item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0;
    gap: 12px;
}

.order-item-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.order-item-name {
    font-size: 13px;
    color: var(--dark);
}

.order-item-sku {
    font-size: 10px;
    color: var(--text-light);
    font-weight: 600;
    letter-spacing: 0.06em;
}

[dir="rtl"] .order-item-sku {
    letter-spacing: 0;
}

.order-item-nums {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.order-item-qty {
    font-size: 12px;
    color: var(--text-light);
}

.order-item-price {
    font-size: 13px;
    font-weight: 500;
    color: var(--dark);
}

.order-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    background: var(--cream-light);
    font-size: 12px;
    color: var(--text-light);
}

.order-total {
    font-size: 18px;
    font-weight: 400;
    color: var(--dark);
}

.pagination {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 32px;
}

.page-link {
    padding: 8px 14px;
    font-size: 12px;
    border: 1px solid rgba(58, 53, 48, 0.12);
    border-radius: 4px;
    color: var(--charcoal);
    transition: all 0.2s;
}

.page-link.active {
    background: var(--account-primary, var(--gold));
    color: var(--white);
    border-color: var(--account-primary, var(--gold));
}

.page-link.disabled {
    opacity: 0.4;
    pointer-events: none;
}

.page-link:hover:not(.active):not(.disabled) {
    border-color: var(--account-primary, var(--accent));
}

.addr-form-card {
    background: var(--cream-light);
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 28px;
    margin-bottom: 24px;
}

.form-card-title {
    font-family: "Cormorant Garamond", var(--font-serif), Georgia, serif;
    font-size: 1.35rem;
    font-weight: 500;
    color: var(--dark);
    margin-bottom: 1.25rem;
}

.addr-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.addr-card {
    background: var(--cream-light);
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 16px 20px;
}

.addr-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.addr-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--account-primary, var(--gold));
}

[dir="rtl"] .addr-label {
    letter-spacing: 0;
}

.addr-default {
    font-size: 10px;
    background: var(--account-primary, var(--gold));
    color: var(--white);
    padding: 2px 8px;
    border-radius: 10px;
    margin-inline-start: 8px;
}

.addr-actions {
    display: flex;
    gap: 8px;
}

.addr-action {
    background: none;
    border: none;
    font-size: 12px;
    font-family: "Manrope", ui-sans-serif, system-ui, sans-serif;
    color: var(--text-light);
    cursor: pointer;
    transition: color 0.2s;
}

.addr-action:hover {
    color: var(--dark);
}

.addr-action.danger:hover {
    color: #dc2626;
}

.addr-name {
    font-size: 14px;
    font-weight: 500;
    color: var(--dark);
    margin-bottom: 4px;
}

.addr-text {
    font-size: 13px;
    color: var(--charcoal);
    line-height: 1.5;
}

.addr-region {
    font-size: 12px;
    color: var(--text-light);
    margin-top: 4px;
}

.profile-empty-muted {
    padding: 40px 16px;
}

.btn-outline {
    padding: 12px 24px;
    background: transparent;
    border: 1px solid rgba(58, 53, 48, 0.2);
    border-radius: 3px;
    font-size: 12px;
    font-weight: 500;
    font-family: "Manrope", ui-sans-serif, system-ui, sans-serif;
    color: var(--charcoal);
    cursor: pointer;
    transition: all 0.2s;
}

.btn-outline:hover {
    border-color: var(--account-primary, var(--gold));
    color: var(--dark);
}

.gift-expand-enter-active,
.gift-expand-leave-active {
    transition: all 0.3s ease;
    overflow: hidden;
}

.gift-expand-enter-from,
.gift-expand-leave-to {
    opacity: 0;
    max-height: 0;
}

.gift-expand-enter-to,
.gift-expand-leave-from {
    opacity: 1;
    max-height: 720px;
}
</style>
