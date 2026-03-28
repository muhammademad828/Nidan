<script setup>
import { computed } from 'vue'
import { useForm, usePage, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
    product: { type: Object, default: null },
    categories: { type: Array, default: () => [] },
})

const page = usePage()
const locale = computed(() => page.props.locale ?? 'ar')
const isAr = computed(() => locale.value === 'ar')
const isEditing = computed(() => !!props.product)

const form = useForm({
    name_en: props.product?.name_en ?? '',
    name_ar: props.product?.name_ar ?? '',
    sku: props.product?.sku ?? '',
    category_id: props.product?.category_id ?? '',
    base_price: props.product?.base_price ?? '',
    compare_at_price: props.product?.compare_at_price ?? '',
    short_description_en: props.product?.short_description_en ?? '',
    short_description_ar: props.product?.short_description_ar ?? '',
    description_en: props.product?.description_en ?? '',
    description_ar: props.product?.description_ar ?? '',
    stock_status: props.product?.stock_status ?? 'in_stock',
    stock_quantity: props.product?.stock_quantity ?? 0,
    is_active: props.product?.is_active ?? true,
    is_featured: props.product?.is_featured ?? false,
    is_giftable: props.product?.is_giftable ?? true,
    requires_delivery_slot: props.product?.requires_delivery_slot ?? true,
    has_delivery_time: props.product?.has_delivery_time ?? false,
    gallery_existing: props.product?.gallery?.map(g => g.path) ?? [],
    gallery_new: [],
})

const galleryPreviews = computed(() => {
    return props.product?.gallery ?? []
})

function removeExistingImage(index) {
    props.product.gallery.splice(index, 1)
    form.gallery_existing.splice(index, 1)
}

function submit() {
    if (isEditing.value) {
        form.transform((data) => ({ ...data, _method: 'put' }))
            .post(route('admin.products.update', props.product.id), {
                forceFormData: true,
            })
    } else {
        form.post(route('admin.products.store'), { forceFormData: true })
    }
}
</script>

<template>
  <div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
      <h2 style="font-family: var(--admin-font-serif); font-size: 1.4rem; font-weight: 600; color: var(--admin-dark);">
        {{ isEditing ? (isAr ? 'تعديل المنتج' : 'Edit Product') : (isAr ? 'إضافة منتج' : 'New Product') }}
      </h2>
      <Link :href="route('admin.products.index')" class="admin-btn admin-btn-outline">
        ← {{ isAr ? 'العودة' : 'Back' }}
      </Link>
    </div>

    <form @submit.prevent="submit">
      <!-- Basic Info -->
      <div class="admin-card" style="margin-bottom: 20px;">
        <h3 class="admin-card-title" style="border: none; margin-bottom: 16px; padding: 0;">{{ isAr ? 'المعلومات الأساسية' : 'Basic Info' }}</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="admin-form-group">
            <label class="admin-label">{{ isAr ? 'الاسم (EN)' : 'Name (EN)' }} *</label>
            <input v-model="form.name_en" class="admin-input" required />
            <span v-if="form.errors.name_en" style="color: var(--admin-danger); font-size: 0.8rem;">{{ form.errors.name_en }}</span>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">{{ isAr ? 'الاسم (AR)' : 'Name (AR)' }} *</label>
            <input v-model="form.name_ar" class="admin-input" dir="rtl" required />
            <span v-if="form.errors.name_ar" style="color: var(--admin-danger); font-size: 0.8rem;">{{ form.errors.name_ar }}</span>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">SKU *</label>
            <input v-model="form.sku" class="admin-input" placeholder="NIDAN-ROSE-01" required style="font-family: monospace;" />
            <span v-if="form.errors.sku" style="color: var(--admin-danger); font-size: 0.8rem;">{{ form.errors.sku }}</span>
            <span class="admin-input-hint">{{ isAr ? 'كود فريد يدوي — مثال: NIDAN-ROSE-01' : 'Unique manual code — e.g. NIDAN-ROSE-01' }}</span>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">{{ isAr ? 'الفئة' : 'Category' }} *</label>
            <select v-model="form.category_id" class="admin-select" required>
              <option value="">{{ isAr ? 'اختر الفئة' : 'Select category' }}</option>
              <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Pricing -->
      <div class="admin-card" style="margin-bottom: 20px;">
        <h3 class="admin-card-title" style="border: none; margin-bottom: 16px; padding: 0;">{{ isAr ? 'السعر والمخزون' : 'Pricing & Stock' }}</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="admin-form-group">
            <label class="admin-label">{{ isAr ? 'السعر الأساسي (EGP)' : 'Base Price (EGP)' }} *</label>
            <input v-model="form.base_price" type="number" step="0.01" class="admin-input" required />
          </div>
          <div class="admin-form-group">
            <label class="admin-label">{{ isAr ? 'السعر قبل التخفيض' : 'Compare At Price' }}</label>
            <input v-model="form.compare_at_price" type="number" step="0.01" class="admin-input" />
          </div>
          <div class="admin-form-group">
            <label class="admin-label">{{ isAr ? 'حالة المخزون' : 'Stock Status' }} *</label>
            <select v-model="form.stock_status" class="admin-select">
              <option value="in_stock">In Stock</option>
              <option value="low_stock">Low Stock</option>
              <option value="out_of_stock">Out of Stock</option>
            </select>
          </div>
          <div class="admin-form-group">
            <label class="admin-label">{{ isAr ? 'الكمية' : 'Stock Qty' }}</label>
            <input v-model="form.stock_quantity" type="number" class="admin-input" />
          </div>
        </div>
      </div>

      <!-- Description -->
      <div class="admin-card" style="margin-bottom: 20px;">
        <h3 class="admin-card-title" style="border: none; margin-bottom: 16px; padding: 0;">{{ isAr ? 'الوصف' : 'Description' }}</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div class="admin-form-group">
            <label class="admin-label">Short (EN)</label>
            <textarea v-model="form.short_description_en" class="admin-textarea" rows="2" />
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Short (AR)</label>
            <textarea v-model="form.short_description_ar" class="admin-textarea" rows="2" dir="rtl" />
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Full (EN)</label>
            <textarea v-model="form.description_en" class="admin-textarea" rows="4" />
          </div>
          <div class="admin-form-group">
            <label class="admin-label">Full (AR)</label>
            <textarea v-model="form.description_ar" class="admin-textarea" rows="4" dir="rtl" />
          </div>
        </div>
      </div>

      <!-- Images -->
      <div class="admin-card" style="margin-bottom: 20px;">
        <h3 class="admin-card-title" style="border: none; margin-bottom: 16px; padding: 0;">{{ isAr ? 'الصور' : 'Images' }}</h3>
        
        <div v-if="galleryPreviews.length" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
          <div v-for="(img, idx) in galleryPreviews" :key="idx" style="position: relative;">
            <img
              :src="img.url"
              alt="Gallery Preview"
              style="width: 88px; height: 88px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(0,0,0,.08);"
            />
            <button type="button" @click="removeExistingImage(idx)" style="position: absolute; top: -5px; right: -5px; background: white; border-radius: 50%; border: 1px solid #ccc; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; color: red; cursor: pointer; font-size: 14px; font-weight: bold; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
              &times;
            </button>
          </div>
        </div>
        <img v-else-if="product?.image" :src="product.image" alt="Current Primary" style="max-width: 120px; max-height: 120px; border-radius: 8px; margin-bottom: 12px; display: block;" />
        
        <input
          type="file"
          accept="image/*"
          multiple
          @change="form.gallery_new = Array.from($event.target.files || [])"
        />
        <p style="margin-top: 8px; font-size: 0.82rem; color: var(--admin-text-light);">
          {{ isAr ? 'إضافة إلى المعرض (يمكن رفع أكثر من صورة)' : 'Add to Gallery (multiple allowed)' }}
        </p>
        <span v-if="form.errors.gallery_new" style="color: var(--admin-danger); font-size: 0.8rem; display:block;">{{ form.errors.gallery_new }}</span>
      </div>

      <!-- Toggles -->
      <div class="admin-card" style="margin-bottom: 20px;">
        <div style="display: flex; gap: 24px; flex-wrap: wrap;">
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.875rem;">
            <input type="checkbox" v-model="form.is_active" style="accent-color: var(--admin-gold); width: 18px; height: 18px;" />
            {{ isAr ? 'نشط' : 'Active' }}
          </label>
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.875rem;">
            <input type="checkbox" v-model="form.is_featured" style="accent-color: var(--admin-gold); width: 18px; height: 18px;" />
            {{ isAr ? 'مميز' : 'Featured' }}
          </label>
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.875rem;">
            <input type="checkbox" v-model="form.is_giftable" style="accent-color: var(--admin-gold); width: 18px; height: 18px;" />
            {{ isAr ? 'قابل للإهداء' : 'Giftable' }}
          </label>
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.875rem;">
            <input type="checkbox" v-model="form.requires_delivery_slot" style="accent-color: var(--admin-gold); width: 18px; height: 18px;" />
            {{ isAr ? 'يتطلب موعد توصيل' : 'Requires Delivery Slot' }}
          </label>
          <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.875rem;">
            <input type="checkbox" v-model="form.has_delivery_time" style="accent-color: var(--admin-gold); width: 18px; height: 18px;" />
            {{ isAr ? 'تفعيل اختيار وقت الاستلام' : 'Enable Delivery Time Selection' }}
          </label>
        </div>
      </div>

      <button type="submit" class="admin-btn admin-btn-gold" :disabled="form.processing" style="padding: 14px 40px;">
        {{ form.processing ? '...' : (isEditing ? (isAr ? 'حفظ التعديلات' : 'Save Changes') : (isAr ? 'إضافة المنتج' : 'Create Product')) }}
      </button>
    </form>
  </div>
</template>
