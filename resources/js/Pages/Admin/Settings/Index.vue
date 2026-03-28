<script setup>
import { ref, computed, reactive } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })

const page = usePage()
const locale = computed(() => page.props.locale ?? 'ar')
const isAr = computed(() => locale.value === 'ar')
const groups = computed(() => page.props.groups ?? {})

const editedValues = reactive({})
const saving = ref(false)
const uploadingId = ref(null)

function initValue(setting) {
    if (!(setting.id in editedValues)) {
        editedValues[setting.id] = setting.value ?? ''
    }
    return editedValues[setting.id]
}

function updateValue(id, val) {
    editedValues[id] = val
}

const groupLabels = {
    branding: { en: 'Branding', ar: 'الهوية البصرية', icon: 'palette' },
    general:  { en: 'General',  ar: 'عام', icon: 'globe' },
    contact:  { en: 'Contact',  ar: 'التواصل', icon: 'phone' },
    social:   { en: 'Social',   ar: 'التواصل الاجتماعي', icon: 'share' },
    delivery: { en: 'Delivery', ar: 'التوصيل', icon: 'truck' },
    payment:  { en: 'Payment',  ar: 'الدفع', icon: 'credit' },
}

function groupLabel(key) {
    const g = groupLabels[key]
    if (!g) return key
    return isAr.value ? g.ar : g.en
}

function settingLabel(setting) {
    if (setting.label) return setting.label
    return setting.key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
}

function hasChanges() {
    for (const [, items] of Object.entries(groups.value)) {
        for (const s of items) {
            if (s.id in editedValues && editedValues[s.id] !== (s.value ?? '')) {
                return true
            }
        }
    }
    return false
}

function saveAll() {
    const changed = []
    for (const [, items] of Object.entries(groups.value)) {
        for (const s of items) {
            if (s.id in editedValues) {
                changed.push({ id: s.id, value: editedValues[s.id] })
            }
        }
    }

    if (changed.length === 0) return

    saving.value = true
    router.put(route('admin.settings.update'), { settings: changed }, {
        preserveScroll: true,
        onFinish: () => saving.value = false,
    })
}

function uploadImage(event, setting) {
    const file = event.target.files?.[0]
    if (!file) return

    uploadingId.value = setting.id

    const formData = new FormData()
    formData.append('image', file)
    formData.append('id', setting.id)

    router.post(route('admin.settings.upload-image'), formData, {
        preserveScroll: true,
        forceFormData: true,
        onFinish: () => uploadingId.value = null,
    })
}

function imageUrl(val) {
    if (!val) return null
    if (val.startsWith('http')) return val
    return `/storage/${val}`
}

const groupOrder = ['branding', 'general', 'contact', 'social', 'delivery', 'payment']

const sortedGroups = computed(() => {
    const result = {}
    for (const key of groupOrder) {
        if (groups.value[key]) result[key] = groups.value[key]
    }
    for (const key in groups.value) {
        if (!result[key]) result[key] = groups.value[key]
    }
    return result
})
</script>

<template>
  <div>
    <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
      <div>
        <h2 style="font-family: var(--admin-font-serif); font-size: 1.4rem; font-weight: 600; color: var(--admin-dark);">
          {{ isAr ? 'إعدادات الموقع' : 'Site Settings' }}
        </h2>
        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-top: 4px;">
          {{ isAr ? 'إدارة الشعار والألوان ومعلومات التواصل والإعدادات العامة' : 'Manage logo, colors, contact info, and general settings' }}
        </p>
      </div>
      <button
          class="admin-btn admin-btn-gold"
          :disabled="saving || !hasChanges()"
          @click="saveAll"
      >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
          <polyline points="17 21 17 13 7 13 7 21"/>
          <polyline points="7 3 7 8 15 8"/>
        </svg>
        {{ saving
             ? (isAr ? 'جاري الحفظ...' : 'Saving...')
             : (isAr ? 'حفظ التغييرات' : 'Save Changes')
        }}
      </button>
    </div>

    <div v-for="(items, groupKey) in sortedGroups" :key="groupKey" class="admin-settings-group">
      <h3 class="admin-settings-group-title">
        <svg v-if="groupLabels[groupKey]?.icon === 'palette'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--admin-gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="13.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="10.5" r="2.5"/>
          <circle cx="8.5" cy="7.5" r="2.5"/><circle cx="6.5" cy="12.5" r="2.5"/>
          <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>
        </svg>
        <svg v-if="groupLabels[groupKey]?.icon === 'globe'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--admin-gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/>
          <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
        <svg v-if="groupLabels[groupKey]?.icon === 'phone'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--admin-gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
        <svg v-if="groupLabels[groupKey]?.icon === 'share'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--admin-gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
          <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
        </svg>
        <svg v-if="groupLabels[groupKey]?.icon === 'truck'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--admin-gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
          <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
        </svg>
        <svg v-if="groupLabels[groupKey]?.icon === 'credit'" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--admin-gold)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>
        </svg>
        {{ groupLabel(groupKey) }}
      </h3>

      <div class="admin-settings-grid">
        <div
            v-for="setting in items"
            :key="setting.id"
            class="admin-setting-item"
        >
          <label class="admin-label">{{ settingLabel(setting) }}</label>

          <!-- Image type -->
          <template v-if="setting.type === 'image'">
            <img
                v-if="setting.value"
                :src="imageUrl(setting.value)"
                :alt="setting.key"
                class="admin-image-preview"
            />
            <div class="admin-image-upload">
              <input
                  type="file"
                  accept="image/*"
                  @change="uploadImage($event, setting)"
              />
              <p style="font-size: 0.8rem; color: var(--admin-text-muted);">
                {{ uploadingId === setting.id
                     ? (isAr ? 'جاري الرفع...' : 'Uploading...')
                     : (isAr ? 'اضغط لرفع صورة' : 'Click to upload image')
                }}
              </p>
            </div>
          </template>

          <!-- Boolean type -->
          <template v-else-if="setting.type === 'boolean'">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
              <input
                  type="checkbox"
                  :checked="initValue(setting) === '1' || initValue(setting) === 'true'"
                  @change="updateValue(setting.id, $event.target.checked ? '1' : '0')"
                  style="accent-color: var(--admin-gold); width: 18px; height: 18px;"
              />
              <span style="font-size: 0.85rem; color: var(--admin-text-muted);">
                {{ initValue(setting) === '1' || initValue(setting) === 'true'
                     ? (isAr ? 'مفعل' : 'Enabled')
                     : (isAr ? 'معطل' : 'Disabled')
                }}
              </span>
            </label>
          </template>

          <!-- Textarea type -->
          <template v-else-if="setting.type === 'textarea' || setting.type === 'json'">
            <textarea
                class="admin-textarea"
                :value="initValue(setting)"
                @input="updateValue(setting.id, $event.target.value)"
                rows="4"
            />
          </template>

          <!-- Default text -->
          <template v-else>
            <input
                type="text"
                class="admin-input"
                :value="initValue(setting)"
                @input="updateValue(setting.id, $event.target.value)"
            />
          </template>

          <p class="admin-input-hint">{{ setting.key }}</p>
        </div>
      </div>
    </div>

    <div v-if="Object.keys(groups).length === 0" style="text-align: center; padding: 64px 24px; color: var(--admin-text-muted);">
      <p style="font-size: 1.1rem; margin-bottom: 8px;">
        {{ isAr ? 'لا توجد إعدادات بعد' : 'No settings found' }}
      </p>
      <p style="font-size: 0.85rem;">
        {{ isAr ? 'قم بتشغيل seeders لإضافة الإعدادات الافتراضية' : 'Run seeders to add default settings' }}
      </p>
    </div>
  </div>
</template>
