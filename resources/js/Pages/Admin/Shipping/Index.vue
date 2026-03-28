<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const page = usePage()
const locale = computed(() => page.props.locale ?? 'ar')
const isAr = computed(() => locale.value === 'ar')
const governorates = computed(() => page.props.governorates ?? [])

const t = (ar, en) => isAr.value ? ar : en

// Expandable governorate panels
const expanded = ref({})
function toggle(id) { expanded.value[id] = !expanded.value[id] }

// City fee editing
const editingFees = ref({})  // city_id -> fee value
const savingCity = ref(null)
const savingGov = ref(null)

function startEdit(city) {
    editingFees.value[city.id] = city.delivery_fee
}
function cancelEdit(city) {
    delete editingFees.value[city.id]
}

function saveCity(city) {
    savingCity.value = city.id
    router.patch(route('admin.shipping.city.update', city.id), {
        delivery_fee: editingFees.value[city.id],
        is_active: city.is_active,
    }, {
        preserveScroll: true,
        onSuccess: () => { delete editingFees.value[city.id] },
        onFinish: () => { savingCity.value = null },
    })
}

function toggleCity(city) {
    router.patch(route('admin.shipping.city.update', city.id), {
        delivery_fee: city.delivery_fee,
        is_active: !city.is_active,
    }, { preserveScroll: true })
}

// Bulk update governorate
const bulkFees = ref({}) // gov_id -> fee
function bulkUpdate(gov) {
    if (!bulkFees.value[gov.id] && bulkFees.value[gov.id] !== 0) return
    savingGov.value = gov.id
    router.patch(route('admin.shipping.governorate.bulk', gov.id), {
        delivery_fee: bulkFees.value[gov.id],
    }, {
        preserveScroll: true,
        onSuccess: () => { delete bulkFees.value[gov.id] },
        onFinish: () => { savingGov.value = null },
    })
}

function toggleGovernorate(gov) {
    router.patch(route('admin.shipping.governorate.toggle', gov.id), {}, { preserveScroll: true })
}

function formatFee(fee) {
    return isAr.value ? `${fee} ج.م` : `${fee} EGP`
}

// Search / filter
const search = ref('')
const filteredGovs = computed(() => {
    if (!search.value.trim()) return governorates.value
    const q = search.value.trim().toLowerCase()
    return governorates.value.filter(g =>
        g.name_ar.includes(q) || g.name_en.toLowerCase().includes(q) ||
        g.cities.some(c => c.name_ar.includes(q) || c.name_en.toLowerCase().includes(q))
    )
})
</script>

<template>
  <div>
    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
      <h2 style="font-family:var(--admin-font-serif); font-size:1.4rem; font-weight:600; color:var(--admin-dark);">
        {{ t('إدارة رسوم الشحن', 'Shipping Management') }}
      </h2>
      <input
        v-model="search"
        class="admin-input"
        style="max-width:260px;"
        :placeholder="t('ابحث عن محافظة أو مدينة...', 'Search governorate or city...')"
      />
    </div>

    <div class="admin-card" style="margin-bottom:12px; padding:14px 20px; background: rgba(201,168,76,0.06); border: 1px solid rgba(201,168,76,0.2);">
      <p style="font-size:0.85rem; color:var(--admin-charcoal); margin:0;">
        {{ t(
          'يمكنك تحديد رسوم التوصيل لكل مدينة بشكل مستقل، أو تطبيق رسوم موحدة على كل مدن المحافظة دفعة واحدة.',
          'Set delivery fees per city independently, or apply a uniform fee across all cities in a governorate at once.'
        ) }}
      </p>
    </div>

    <!-- Governorates list -->
    <div v-for="gov in filteredGovs" :key="gov.id" class="admin-card" style="margin-bottom:8px; padding:0; overflow:hidden;">

      <!-- Governorate header -->
      <div
        style="display:flex; align-items:center; justify-content:space-between; padding:14px 20px; cursor:pointer; user-select:none;"
        :style="{ background: gov.is_active ? 'var(--admin-white)' : 'rgba(0,0,0,0.02)' }"
        @click="toggle(gov.id)"
      >
        <div style="display:flex; align-items:center; gap:12px;">
          <svg
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            style="width:16px; height:16px; transition:transform 0.2s; color:var(--admin-gold);"
            :style="{ transform: expanded[gov.id] ? 'rotate(90deg)' : 'rotate(0deg)' }"
          >
            <polyline points="9 18 15 12 9 6"/>
          </svg>
          <span style="font-weight:600; font-size:0.95rem; color:var(--admin-dark);">
            {{ isAr ? gov.name_ar : gov.name_en }}
          </span>
          <span v-if="!gov.is_active" style="font-size:0.75rem; background:#fee2e2; color:#991b1b; padding:2px 8px; border-radius:20px;">
            {{ t('معطل', 'Disabled') }}
          </span>
          <span style="font-size:0.8rem; color:var(--admin-text-muted);">
            {{ gov.cities.length }} {{ t('مدينة', 'cities') }}
          </span>
        </div>

        <div style="display:flex; align-items:center; gap:8px;" @click.stop>
          <!-- Bulk fee input -->
          <input
            v-model.number="bulkFees[gov.id]"
            type="number" min="0" max="9999" step="5"
            class="admin-input"
            style="width:100px; font-size:0.8rem;"
            :placeholder="t('رسوم موحدة', 'Uniform fee')"
          />
          <button
            class="admin-btn admin-btn-outline"
            style="font-size:0.75rem; padding:6px 12px; white-space:nowrap;"
            :disabled="savingGov === gov.id"
            @click="bulkUpdate(gov)"
          >
            {{ t('تطبيق على الكل', 'Apply to all') }}
          </button>
          <button
            class="admin-btn"
            :class="gov.is_active ? 'admin-btn-ghost' : 'admin-btn-gold'"
            style="font-size:0.75rem; padding:6px 12px; white-space:nowrap;"
            @click="toggleGovernorate(gov)"
          >
            {{ gov.is_active ? t('تعطيل', 'Disable') : t('تفعيل', 'Enable') }}
          </button>
        </div>
      </div>

      <!-- Cities table -->
      <transition name="slide-down">
        <div v-if="expanded[gov.id]" style="border-top:1px solid var(--admin-border);">
          <table class="admin-table" style="margin:0;">
            <thead>
              <tr>
                <th>{{ t('المدينة', 'City') }}</th>
                <th>{{ t('رسوم التوصيل', 'Delivery Fee') }}</th>
                <th style="width:160px;">{{ t('الحالة', 'Status') }}</th>
                <th style="width:160px;"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="city in gov.cities" :key="city.id" :style="{ opacity: city.is_active ? 1 : 0.5 }">
                <td style="font-weight:500;">{{ isAr ? city.name_ar : city.name_en }}</td>
                <td>
                  <div v-if="editingFees[city.id] !== undefined" style="display:flex; gap:8px; align-items:center;">
                    <input
                      v-model.number="editingFees[city.id]"
                      type="number" min="0" max="9999" step="5"
                      class="admin-input"
                      style="width:100px;"
                    />
                    <button
                      class="admin-btn admin-btn-gold"
                      style="padding:5px 12px; font-size:0.78rem;"
                      :disabled="savingCity === city.id"
                      @click="saveCity(city)"
                    >
                      {{ t('حفظ', 'Save') }}
                    </button>
                    <button
                      class="admin-btn admin-btn-ghost"
                      style="padding:5px 10px; font-size:0.78rem;"
                      @click="cancelEdit(city)"
                    >✕</button>
                  </div>
                  <span v-else style="font-family:var(--admin-font-serif); font-size:1rem; color:var(--admin-gold);">
                    {{ formatFee(city.delivery_fee) }}
                  </span>
                </td>
                <td>
                  <button
                    class="admin-btn admin-btn-ghost"
                    style="font-size:0.75rem; padding:4px 10px;"
                    @click="toggleCity(city)"
                  >
                    {{ city.is_active ? t('تعطيل', 'Disable') : t('تفعيل', 'Enable') }}
                  </button>
                </td>
                <td>
                  <button
                    v-if="editingFees[city.id] === undefined"
                    class="admin-btn admin-btn-outline"
                    style="font-size:0.75rem; padding:4px 12px;"
                    @click="startEdit(city)"
                  >
                    {{ t('تعديل', 'Edit') }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </transition>
    </div>

    <div v-if="!filteredGovs.length" style="text-align:center; padding:48px; color:var(--admin-text-muted);">
      {{ t('لا توجد نتائج', 'No results found') }}
    </div>
  </div>
</template>

<style scoped>
.slide-down-enter-active, .slide-down-leave-active { transition: all 0.25s ease; overflow: hidden; }
.slide-down-enter-from, .slide-down-leave-to { opacity: 0; max-height: 0; }
.slide-down-enter-to, .slide-down-leave-from { opacity: 1; max-height: 2000px; }
</style>
