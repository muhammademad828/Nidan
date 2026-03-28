<script setup>
import { computed } from 'vue'
import { useForm, usePage, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const page = usePage()
const locale = computed(() => page.props.locale ?? 'ar')
const isAr = computed(() => locale.value === 'ar')
const order = computed(() => page.props.order ?? {})

const statusForm = useForm({
    status: '',
    notes: '',
})

function updateStatus() {
    statusForm.patch(route('admin.orders.status', order.value.id), {
        preserveScroll: true,
        onSuccess: () => statusForm.reset(),
    })
}

const statusLabels = {
    pending:   { en: 'Pending',   ar: 'معلق' },
    paid:      { en: 'Paid',      ar: 'مدفوع' },
    delivered: { en: 'Delivered', ar: 'تم التوصيل' },
    cancelled: { en: 'Cancelled', ar: 'ملغي' },
}

const transitions = {
    pending:   ['paid', 'cancelled'],
    paid:      ['delivered', 'cancelled'],
    delivered: [],
    cancelled: [],
}

const availableTransitions = computed(() => transitions[order.value.status] ?? [])
</script>

<template>
  <div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
      <div>
        <h2 style="font-family: var(--admin-font-serif); font-size: 1.4rem; font-weight: 600; color: var(--admin-dark);">
          {{ isAr ? 'تفاصيل الطلب' : 'Order Details' }} #{{ order.order_number }}
        </h2>
        <span class="admin-badge" :class="order.status" style="margin-top: 8px;">
          {{ isAr ? statusLabels[order.status]?.ar : statusLabels[order.status]?.en }}
        </span>
      </div>
      <Link :href="route('admin.orders.index')" class="admin-btn admin-btn-outline">← {{ isAr ? 'العودة' : 'Back' }}</Link>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
      <!-- Customer Info -->
      <div class="admin-card">
        <h3 class="admin-card-title" style="border: none; margin-bottom: 12px; padding: 0;">{{ isAr ? 'معلومات العميل' : 'Customer Info' }}</h3>
        <p style="margin-bottom: 4px;"><strong>{{ order.contact_person }}</strong></p>
        <p style="font-size: 0.875rem; color: var(--admin-text-muted); margin-bottom: 4px;" dir="ltr">{{ order.contact_phone }}</p>
        <p v-if="order.company_name" style="font-size: 0.875rem; color: var(--admin-text-muted);">{{ order.company_name }}</p>
        <p style="font-size: 0.875rem; color: var(--admin-text-muted); margin-top: 8px;">{{ isAr ? 'المنطقة' : 'Region' }}: {{ order.region }}</p>
      </div>

      <!-- Totals -->
      <div class="admin-card">
        <h3 class="admin-card-title" style="border: none; margin-bottom: 12px; padding: 0;">{{ isAr ? 'الإجمالي' : 'Totals' }}</h3>
        <div style="display: flex; flex-direction: column; gap: 6px; font-size: 0.875rem;">
          <div style="display: flex; justify-content: space-between;"><span>{{ isAr ? 'المجموع الجزئي' : 'Subtotal' }}</span><span>{{ order.subtotal }} {{ order.currency }}</span></div>
          <div style="display: flex; justify-content: space-between;"><span>{{ isAr ? 'التوصيل' : 'Delivery' }}</span><span>{{ order.delivery_fee }} {{ order.currency }}</span></div>
          <div style="display: flex; justify-content: space-between;"><span>{{ isAr ? 'الضريبة' : 'Tax' }}</span><span>{{ order.tax_amount }} {{ order.currency }}</span></div>
          <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1rem; padding-top: 8px; border-top: 1px solid var(--admin-border);">
            <span>{{ isAr ? 'الإجمالي' : 'Total' }}</span><span>{{ order.total }} {{ order.currency }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Items -->
    <div class="admin-card" style="margin-top: 20px;">
      <h3 class="admin-card-title" style="border: none; margin-bottom: 12px; padding: 0;">{{ isAr ? 'المنتجات' : 'Items' }}</h3>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead><tr><th>{{ isAr ? 'المنتج' : 'Product' }}</th><th>SKU</th><th>{{ isAr ? 'الكمية' : 'Qty' }}</th><th>{{ isAr ? 'سعر الوحدة' : 'Unit' }}</th><th>{{ isAr ? 'الإجمالي' : 'Total' }}</th></tr></thead>
          <tbody>
            <tr v-for="item in order.items" :key="item.product_sku">
              <td>{{ item.product_name }}<br v-if="item.variation_name"/><small v-if="item.variation_name" style="color: var(--admin-text-muted);">{{ item.variation_name }}</small></td>
              <td style="font-family: monospace; font-size: 0.8rem;">{{ item.product_sku }}</td>
              <td>{{ item.quantity }}</td>
              <td>{{ item.unit_price }} {{ order.currency }}</td>
              <td style="font-weight: 600;">{{ item.total_price }} {{ order.currency }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Gift / Delivery Details -->
    <div v-if="order.gift_detail" class="admin-card" style="margin-top: 20px;">
      <h3 class="admin-card-title" style="border: none; margin-bottom: 12px; padding: 0;">{{ isAr ? 'تفاصيل الهدية' : 'Gift Details' }}</h3>
      <p>{{ order.gift_detail.recipient_name }} — {{ order.gift_detail.recipient_phone }}</p>
      <p v-if="order.gift_detail.gift_message" style="margin-top: 8px; font-style: italic; color: var(--admin-charcoal);">"{{ order.gift_detail.gift_message }}"</p>
    </div>

    <!-- Status Update -->
    <div v-if="availableTransitions.length" class="admin-card" style="margin-top: 20px;">
      <h3 class="admin-card-title" style="border: none; margin-bottom: 12px; padding: 0;">{{ isAr ? 'تحديث الحالة' : 'Update Status' }}</h3>
      <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
        <div class="admin-form-group" style="flex: 1; min-width: 200px;">
          <label class="admin-label">{{ isAr ? 'الحالة الجديدة' : 'New Status' }}</label>
          <select v-model="statusForm.status" class="admin-select">
            <option value="">{{ isAr ? 'اختر' : 'Select' }}</option>
            <option v-for="s in availableTransitions" :key="s" :value="s">
              {{ isAr ? statusLabels[s]?.ar : statusLabels[s]?.en }}
            </option>
          </select>
        </div>
        <div class="admin-form-group" style="flex: 2; min-width: 200px;">
          <label class="admin-label">{{ isAr ? 'ملاحظة' : 'Notes' }}</label>
          <input v-model="statusForm.notes" class="admin-input" />
        </div>
        <button class="admin-btn admin-btn-gold" :disabled="!statusForm.status || statusForm.processing" @click="updateStatus" style="margin-bottom: 20px;">
          {{ isAr ? 'تحديث' : 'Update' }}
        </button>
      </div>
    </div>

    <!-- Status History -->
    <div v-if="order.status_history?.length" class="admin-card" style="margin-top: 20px;">
      <h3 class="admin-card-title" style="border: none; margin-bottom: 12px; padding: 0;">{{ isAr ? 'سجل الحالة' : 'Status History' }}</h3>
      <div v-for="h in order.status_history" :key="h.date" style="display: flex; gap: 12px; padding: 8px 0; border-bottom: 1px solid var(--admin-border); font-size: 0.85rem;">
        <span style="color: var(--admin-text-muted); min-width: 120px;">{{ h.date }}</span>
        <span><strong>{{ h.from || '—' }}</strong> → <strong>{{ h.to }}</strong></span>
        <span v-if="h.notes" style="color: var(--admin-text-muted);">{{ h.notes }}</span>
        <span style="color: var(--admin-text-muted); margin-inline-start: auto;">{{ h.changed_by }}</span>
      </div>
    </div>
  </div>
</template>
