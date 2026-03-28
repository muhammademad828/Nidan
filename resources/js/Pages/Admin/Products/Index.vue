<script setup>
import { computed, ref } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const page = usePage()
const locale = computed(() => page.props.locale ?? 'ar')
const isAr = computed(() => locale.value === 'ar')
const products = computed(() => page.props.products ?? { data: [] })
const categories = computed(() => page.props.categories ?? [])
const filters = computed(() => page.props.filters ?? {})

const search = ref(filters.value.search ?? '')
const categoryFilter = ref(filters.value.category ?? '')

function applyFilters() {
    router.get(route('admin.products.index'), {
        search: search.value || undefined,
        category: categoryFilter.value || undefined,
    }, { preserveState: true, replace: true })
}

function deleteProduct(id) {
    if (confirm(isAr.value ? 'هل أنت متأكد من حذف هذا المنتج؟' : 'Delete this product?')) {
        router.delete(route('admin.products.destroy', id))
    }
}

const stockLabels = {
    in_stock:     { en: 'In Stock',     ar: 'متوفر' },
    low_stock:    { en: 'Low Stock',    ar: 'مخزون قليل' },
    out_of_stock: { en: 'Out of Stock', ar: 'نفذ' },
}
</script>

<template>
  <div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
      <h2 style="font-family: var(--admin-font-serif); font-size: 1.4rem; font-weight: 600; color: var(--admin-dark);">
        {{ isAr ? 'المنتجات' : 'Products' }}
      </h2>
      <Link :href="route('admin.products.create')" class="admin-btn admin-btn-gold">
        + {{ isAr ? 'إضافة منتج' : 'Add Product' }}
      </Link>
    </div>

    <!-- Filters -->
    <div class="admin-card" style="margin-bottom: 20px; padding: 16px 20px;">
      <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
        <input
          v-model="search"
          class="admin-input"
          style="max-width: 280px;"
          :placeholder="isAr ? 'بحث بالاسم أو الكود...' : 'Search name or SKU...'"
          @keyup.enter="applyFilters"
        />
        <select v-model="categoryFilter" class="admin-select" style="max-width: 200px;" @change="applyFilters">
          <option value="">{{ isAr ? 'كل الفئات' : 'All Categories' }}</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>
        <button class="admin-btn admin-btn-outline" @click="applyFilters">
          {{ isAr ? 'بحث' : 'Filter' }}
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="admin-card">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th style="width: 56px;"></th>
              <th>{{ isAr ? 'الكود' : 'SKU' }}</th>
              <th>{{ isAr ? 'الاسم' : 'Name' }}</th>
              <th>{{ isAr ? 'الفئة' : 'Category' }}</th>
              <th>{{ isAr ? 'السعر' : 'Price' }}</th>
              <th>{{ isAr ? 'المخزون' : 'Stock' }}</th>
              <th>{{ isAr ? 'نشط' : 'Active' }}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in products.data" :key="p.id">
              <td>
                <img v-if="p.image" :src="p.image" :alt="p.name_en" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;" />
                <div v-else style="width: 40px; height: 40px; background: var(--admin-cream); border-radius: 6px;"></div>
              </td>
              <td style="font-family: monospace; font-size: 0.8rem; font-weight: 600;">{{ p.sku }}</td>
              <td>{{ p.name_en }}</td>
              <td>{{ p.category }}</td>
              <td style="font-weight: 600;">{{ p.base_price }} EGP</td>
              <td>
                <span class="admin-badge" :class="p.stock_status === 'in_stock' ? 'paid' : p.stock_status === 'low_stock' ? 'pending' : 'cancelled'">
                  {{ isAr ? stockLabels[p.stock_status]?.ar : stockLabels[p.stock_status]?.en }}
                </span>
              </td>
              <td>{{ p.is_active ? '✓' : '—' }}</td>
              <td>
                <div style="display: flex; gap: 6px;">
                  <Link :href="route('admin.products.edit', p.id)" class="admin-btn admin-btn-ghost" style="padding: 6px 10px; font-size: 0.8rem;">
                    {{ isAr ? 'تعديل' : 'Edit' }}
                  </Link>
                  <button class="admin-btn admin-btn-ghost" style="padding: 6px 10px; font-size: 0.8rem; color: var(--admin-danger);" @click="deleteProduct(p.id)">
                    {{ isAr ? 'حذف' : 'Delete' }}
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!products.data?.length">
              <td colspan="8" style="text-align: center; padding: 32px; color: var(--admin-text-muted);">
                {{ isAr ? 'لا توجد منتجات' : 'No products found' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="products.links?.length > 3" style="display: flex; justify-content: center; gap: 4px; padding-top: 16px;">
        <Link
          v-for="link in products.links"
          :key="link.label"
          :href="link.url || '#'"
          :class="['admin-btn admin-btn-ghost', { 'admin-btn-gold': link.active }]"
          style="padding: 6px 12px; font-size: 0.8rem;"
          v-html="link.label"
          preserve-scroll
        />
      </div>
    </div>
  </div>
</template>
