<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const page = usePage()
const locale = computed(() => page.props.locale ?? 'ar')
const stats = computed(() => page.props.stats ?? {})
const recentOrders = computed(() => page.props.recent_orders ?? [])
const isAr = computed(() => locale.value === 'ar')

const t = (ar, en) => isAr.value ? ar : en

const statCards = computed(() => [
    {
        label: t('طلبات اليوم', "Today's Orders"),
        value: stats.value.today_orders ?? 0,
        icon: 'orders',
        variant: 'gold',
        link: '/dashboard/orders',
    },
    {
        label: t('إيرادات اليوم', "Today's Revenue"),
        value: formatCurrency(stats.value.today_revenue ?? 0),
        icon: 'revenue',
        variant: 'success',
    },
    {
        label: t('طلبات معلقة', 'Pending Orders'),
        value: stats.value.pending_orders ?? 0,
        icon: 'clock',
        variant: (stats.value.pending_orders ?? 0) > 10 ? 'danger' : 'warning',
        link: '/dashboard/orders?status=pending',
    },
    {
        label: t('إيرادات الشهر', 'Month Revenue'),
        value: formatCurrency(stats.value.month_revenue ?? 0),
        icon: 'chart',
        variant: 'info',
    },
    {
        label: t('منتجات نشطة', 'Active Products'),
        value: stats.value.active_products ?? 0,
        icon: 'products',
        variant: 'gold',
        link: '/dashboard/products',
    },
    {
        label: t('المشتركين', 'Subscribers'),
        value: stats.value.subscribers ?? 0,
        icon: 'users',
        variant: 'info',
    },
])

function formatCurrency(amount) {
    const num = Number(amount).toFixed(0)
    return isAr.value ? `${num} ج.م` : `${num} EGP`
}

const statusLabels = {
    pending:   { en: 'Pending',   ar: 'معلق' },
    paid:      { en: 'Paid',      ar: 'مدفوع' },
    delivered: { en: 'Delivered', ar: 'تم التوصيل' },
    cancelled: { en: 'Cancelled', ar: 'ملغي' },
}
function statusText(status) {
    const s = statusLabels[status] || { en: status, ar: status }
    return isAr.value ? s.ar : s.en
}
</script>

<template>
  <div class="dash-root">

    <!-- Quick stats -->
    <div class="dash-stats-grid">
      <component
        v-for="card in statCards"
        :key="card.label"
        :is="card.link ? 'a' : 'div'"
        :href="card.link"
        class="dash-stat-card"
        :class="[card.variant, card.link ? 'dash-stat-link' : '']"
      >
        <div class="dash-stat-icon" :class="card.variant">
          <!-- Orders -->
          <svg v-if="card.icon === 'orders'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
          </svg>
          <!-- Revenue -->
          <svg v-if="card.icon === 'revenue'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
          </svg>
          <!-- Clock -->
          <svg v-if="card.icon === 'clock'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
          <!-- Chart -->
          <svg v-if="card.icon === 'chart'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
          </svg>
          <!-- Products -->
          <svg v-if="card.icon === 'products'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
          </svg>
          <!-- Users -->
          <svg v-if="card.icon === 'users'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>
        <div class="dash-stat-body">
          <div class="dash-stat-value">{{ card.value }}</div>
          <div class="dash-stat-label">{{ card.label }}</div>
        </div>
        <svg v-if="card.link" class="dash-stat-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </component>
    </div>

    <!-- Recent Orders -->
    <div class="admin-card">
      <div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h2 class="admin-card-title">{{ t('أحدث الطلبات', 'Recent Orders') }}</h2>
        <a href="/dashboard/orders" style="font-size:0.8rem; color:var(--admin-gold); text-decoration:none; font-weight:500;">
          {{ t('عرض الكل', 'View all') }} →
        </a>
      </div>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>{{ t('رقم الطلب', 'Order #') }}</th>
              <th>{{ t('العميل', 'Customer') }}</th>
              <th>{{ t('المنتجات', 'Items') }}</th>
              <th>{{ t('المبلغ', 'Total') }}</th>
              <th>{{ t('الحالة', 'Status') }}</th>
              <th>{{ t('التاريخ', 'Date') }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in recentOrders" :key="order.id">
              <td>
                <span style="font-weight:600;">{{ order.order_number }}</span>
                <span v-if="!order.is_read" class="order-new-badge">{{ t('جديد', 'NEW') }}</span>
              </td>
              <td>{{ order.customer }}</td>
              <td>{{ order.items_count }}</td>
              <td>{{ formatCurrency(order.total) }}</td>
              <td>
                <span class="admin-badge" :class="order.status">
                  {{ statusText(order.status) }}
                </span>
              </td>
              <td style="color:var(--admin-text-muted); font-size:0.825rem;">{{ order.created_at }}</td>
            </tr>
            <tr v-if="recentOrders.length === 0">
              <td colspan="6" style="text-align:center; padding:32px; color:var(--admin-text-muted);">
                {{ t('لا توجد طلبات بعد', 'No orders yet') }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>

<style scoped>
.dash-root { display: flex; flex-direction: column; gap: 24px; }

/* Stats grid */
.dash-stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}
@media (max-width: 1024px) { .dash-stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px)  { .dash-stats-grid { grid-template-columns: 1fr; } }

.dash-stat-card {
  background: var(--admin-white);
  border: 1px solid var(--admin-border);
  border-radius: var(--admin-radius-lg);
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 16px;
  transition: box-shadow var(--admin-transition), transform var(--admin-transition);
}
.dash-stat-link { text-decoration: none; cursor: pointer; }
.dash-stat-link:hover { box-shadow: var(--admin-shadow-md); transform: translateY(-2px); }

.dash-stat-icon {
  width: 52px; height: 52px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.dash-stat-icon svg { width: 22px; height: 22px; }

.dash-stat-icon.gold    { background: rgba(201,168,76,0.12); color: var(--admin-gold-dark); }
.dash-stat-icon.success { background: rgba(39,174,96,0.1);   color: #1a7a45; }
.dash-stat-icon.warning { background: rgba(243,156,18,0.1);  color: #b27800; }
.dash-stat-icon.danger  { background: rgba(192,57,43,0.1);   color: var(--admin-danger); }
.dash-stat-icon.info    { background: rgba(41,128,185,0.1);  color: #1a5a8a; }

.dash-stat-body { flex: 1; }
.dash-stat-value { font-family: var(--admin-font-serif); font-size: 1.6rem; font-weight: 600; color: var(--admin-dark); line-height: 1; }
.dash-stat-label { font-size: 0.78rem; color: var(--admin-text-muted); margin-top: 4px; font-weight: 400; }

.dash-stat-arrow { width: 16px; height: 16px; color: var(--admin-text-muted); flex-shrink: 0; }
</style>
