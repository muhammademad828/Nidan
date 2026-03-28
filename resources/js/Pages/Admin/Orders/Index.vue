<script setup>
import { computed, ref } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const page = usePage()
const locale = computed(() => page.props.locale ?? 'ar')
const isAr = computed(() => locale.value === 'ar')
const orders = computed(() => page.props.orders ?? { data: [] })
const filters = computed(() => page.props.filters ?? {})

const search = ref(filters.value.search ?? '')
const statusFilter = ref(filters.value.status ?? '')

function applyFilters() {
    router.get(route('admin.orders.index'), {
        search: search.value || undefined,
        status: statusFilter.value || undefined,
    }, { preserveState: true, replace: true })
}

const statusLabels = {
    pending:   { en: 'Pending',   ar: 'معلق' },
    paid:      { en: 'Paid',      ar: 'مدفوع' },
    delivered: { en: 'Delivered', ar: 'تم التوصيل' },
    cancelled: { en: 'Cancelled', ar: 'ملغي' },
}
</script>

<template>
  <div>
    <h2 style="font-family: var(--admin-font-serif); font-size: 1.4rem; font-weight: 600; color: var(--admin-dark); margin-bottom: 24px;">
      {{ isAr ? 'الطلبات' : 'Orders' }}
    </h2>

    <div class="admin-card" style="margin-bottom: 20px; padding: 16px 20px;">
      <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        <input v-model="search" class="admin-input" style="max-width: 260px;"
               :placeholder="isAr ? 'رقم الطلب أو اسم العميل...' : 'Order # or customer...'"
               @keyup.enter="applyFilters" />
        <select v-model="statusFilter" class="admin-select" style="max-width: 160px;" @change="applyFilters">
          <option value="">{{ isAr ? 'كل الحالات' : 'All Status' }}</option>
          <option value="pending">{{ isAr ? 'معلق' : 'Pending' }}</option>
          <option value="paid">{{ isAr ? 'مدفوع' : 'Paid' }}</option>
          <option value="delivered">{{ isAr ? 'تم التوصيل' : 'Delivered' }}</option>
          <option value="cancelled">{{ isAr ? 'ملغي' : 'Cancelled' }}</option>
        </select>
        <button class="admin-btn admin-btn-outline" @click="applyFilters">{{ isAr ? 'بحث' : 'Filter' }}</button>
      </div>
    </div>

    <div class="admin-card">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>{{ isAr ? 'رقم الطلب' : 'Order #' }}</th>
              <th>{{ isAr ? 'العميل' : 'Customer' }}</th>
              <th>{{ isAr ? 'الهاتف' : 'Phone' }}</th>
              <th>{{ isAr ? 'المنتجات' : 'Items' }}</th>
              <th>{{ isAr ? 'المبلغ' : 'Total' }}</th>
              <th>{{ isAr ? 'الحالة' : 'Status' }}</th>
              <th>{{ isAr ? 'التاريخ' : 'Date' }}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="o in orders.data" :key="o.id" :style="{ background: !o.is_read ? 'rgba(201,168,76,0.04)' : '' }">
              <td>
                <span style="font-weight: 600;">{{ o.order_number }}</span>
                <span v-if="!o.is_read" class="order-new-badge">{{ isAr ? 'جديد' : 'NEW' }}</span>
              </td>
              <td>{{ o.customer }}</td>
              <td dir="ltr" style="font-size: 0.825rem;">{{ o.phone }}</td>
              <td>{{ o.items_count }}</td>
              <td style="font-weight: 600;">{{ o.total }} {{ o.currency }}</td>
              <td>
                <span class="admin-badge" :class="o.status">
                  {{ isAr ? statusLabels[o.status]?.ar : statusLabels[o.status]?.en }}
                </span>
              </td>
              <td style="font-size: 0.825rem; color: var(--admin-text-muted);">{{ o.created_at }}</td>
              <td>
                <Link :href="route('admin.orders.show', o.id)" class="admin-btn admin-btn-ghost" style="padding: 6px 10px; font-size: 0.8rem;">
                  {{ isAr ? 'عرض' : 'View' }}
                </Link>
              </td>
            </tr>
            <tr v-if="!orders.data?.length">
              <td colspan="8" style="text-align: center; padding: 32px; color: var(--admin-text-muted);">
                {{ isAr ? 'لا توجد طلبات' : 'No orders found' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="orders.links?.length > 3" style="display: flex; justify-content: center; gap: 4px; padding-top: 16px;">
        <Link v-for="link in orders.links" :key="link.label" :href="link.url || '#'"
              :class="['admin-btn admin-btn-ghost', { 'admin-btn-gold': link.active }]"
              style="padding: 6px 12px; font-size: 0.8rem;" v-html="link.label" preserve-scroll />
      </div>
    </div>
  </div>
</template>
