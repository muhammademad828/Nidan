<script setup>
import '../../css/admin.css'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import axios from 'axios'

const page = usePage()
const auth = computed(() => page.props.auth ?? {})
const user = computed(() => auth.value.user)
const flash = computed(() => page.props.flash ?? {})
const locale = computed(() => page.props.locale ?? 'ar')

const sidebarOpen = ref(false)
const showFlash = ref(false)
let flashTimer = null
const unreadCount = ref(0)
let pollTimer = null

const currentPath = computed(() => {
    try { return new URL(page.url, window.location.origin).pathname }
    catch { return page.url }
})

function isActive(path) {
    if (path === '/dashboard') return currentPath.value === '/dashboard'
    return currentPath.value.startsWith(path)
}

function closeSidebar() {
    sidebarOpen.value = false
}

function handleResize() {
    if (window.innerWidth > 768) sidebarOpen.value = false
}

function logout() {
    router.post(route('admin.logout'))
}

function userInitial() {
    return user.value?.name?.charAt(0)?.toUpperCase() || 'A'
}

async function fetchUnreadCount() {
    try {
        const { data } = await axios.get(route('admin.notifications.unread'))
        unreadCount.value = data.count ?? 0
    } catch {}
}

onMounted(() => {
    window.addEventListener('resize', handleResize)
    if (flash.value.success || flash.value.error) {
        showFlash.value = true
        flashTimer = setTimeout(() => showFlash.value = false, 4000)
    }
    fetchUnreadCount()
    pollTimer = setInterval(fetchUnreadCount, 30000)
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize)
    if (flashTimer) clearTimeout(flashTimer)
    if (pollTimer) clearInterval(pollTimer)
})

const navSections = [
    {
        title: { en: 'Main', ar: 'الرئيسية' },
        items: [
            {
                label: { en: 'Dashboard', ar: 'لوحة التحكم' },
                href: '/dashboard',
                icon: 'dashboard',
            },
        ],
    },
    {
        title: { en: 'Catalog', ar: 'المتجر' },
        items: [
            {
                label: { en: 'Products', ar: 'المنتجات' },
                href: '/dashboard/products',
                icon: 'products',
            },
            {
                label: { en: 'Orders', ar: 'الطلبات' },
                href: '/dashboard/orders',
                icon: 'orders',
                badge: true,
            },
            {
                label: { en: 'Shipping', ar: 'الشحن' },
                href: '/dashboard/shipping',
                icon: 'shipping',
            },
        ],
    },
    {
        title: { en: 'Content', ar: 'المحتوى' },
        items: [
            {
                label: { en: 'Settings', ar: 'الإعدادات' },
                href: '/dashboard/settings',
                icon: 'settings',
            },
        ],
    },
]

const pageTitles = {
    '/dashboard':           { en: 'Dashboard',          ar: 'لوحة التحكم' },
    '/dashboard/products':  { en: 'Products',            ar: 'المنتجات' },
    '/dashboard/orders':    { en: 'Orders',              ar: 'الطلبات' },
    '/dashboard/settings':  { en: 'Site Settings',       ar: 'إعدادات الموقع' },
    '/dashboard/shipping':  { en: 'Shipping Management', ar: 'إدارة الشحن' },
}

const pageTitle = computed(() => {
    const path = currentPath.value
    let t = pageTitles[path]
    if (!t) {
        const prefix = Object.keys(pageTitles)
            .filter(k => k !== '/dashboard')
            .sort((a, b) => b.length - a.length)
            .find(k => path.startsWith(k))
        t = prefix ? pageTitles[prefix] : null
    }
    if (!t) return locale.value === 'ar' ? 'لوحة التحكم' : 'Dashboard'
    return locale.value === 'ar' ? t.ar : t.en
})
</script>

<template>
  <div class="admin-shell" :dir="locale === 'ar' ? 'rtl' : 'ltr'">
    <!-- Mobile overlay -->
    <div
        class="admin-sidebar-overlay"
        :class="{ open: sidebarOpen }"
        @click="closeSidebar"
    />

    <!-- Sidebar -->
    <aside class="admin-sidebar" :class="{ open: sidebarOpen }">
      <div class="admin-sidebar-brand">
        <span class="admin-sidebar-brand-text">NIDAN</span>
        <span class="admin-sidebar-brand-badge">{{ locale === 'ar' ? 'لوحة التحكم' : 'Admin' }}</span>
      </div>

      <nav class="admin-sidebar-nav">
        <div v-for="section in navSections" :key="section.title.en" class="admin-nav-section">
          <div class="admin-nav-section-title">
            {{ locale === 'ar' ? section.title.ar : section.title.en }}
          </div>
          <Link
              v-for="item in section.items"
              :key="item.href"
              :href="item.href"
              class="admin-nav-link"
              :class="{ active: isActive(item.href) }"
              @click="closeSidebar"
          >
            <!-- Dashboard icon -->
            <svg v-if="item.icon === 'dashboard'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7" rx="1"/>
              <rect x="14" y="3" width="7" height="7" rx="1"/>
              <rect x="3" y="14" width="7" height="7" rx="1"/>
              <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
            <!-- Products icon -->
            <svg v-if="item.icon === 'products'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
              <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <!-- Orders icon -->
            <svg v-if="item.icon === 'orders'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
            </svg>
            <!-- Settings icon -->
            <svg v-if="item.icon === 'settings'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3"/>
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            <!-- Shipping icon -->
            <svg v-if="item.icon === 'shipping'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="1" y="3" width="15" height="13" rx="1"/>
              <path d="M16 8h4l3 3v5h-7V8z"/>
              <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
            </svg>
            <span>{{ locale === 'ar' ? item.label.ar : item.label.en }}</span>
            <!-- Unread badge -->
            <span
              v-if="item.badge && unreadCount > 0"
              class="nav-badge"
            >{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
          </Link>
        </div>
      </nav>

      <div class="admin-sidebar-footer">
        <button class="admin-nav-link" @click="logout">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          <span>{{ locale === 'ar' ? 'تسجيل الخروج' : 'Logout' }}</span>
        </button>
      </div>
    </aside>

    <!-- Main -->
    <div class="admin-main">
      <header class="admin-header">
        <div class="admin-header-left">
          <button class="admin-menu-toggle" @click="sidebarOpen = !sidebarOpen">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <line x1="3" y1="6" x2="21" y2="6"/>
              <line x1="3" y1="12" x2="21" y2="12"/>
              <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
          </button>
          <h1 class="admin-header-title">{{ pageTitle }}</h1>
        </div>
        <div class="admin-header-right">
          <div class="admin-header-user">
            <span>{{ user?.name }}</span>
            <div class="admin-header-avatar">{{ userInitial() }}</div>
          </div>
        </div>
      </header>

      <main class="admin-content">
        <slot />
      </main>
    </div>

    <!-- Flash messages -->
    <transition name="admin-fade">
      <div v-if="showFlash && flash.success" class="admin-flash success">
        {{ flash.success }}
      </div>
      <div v-else-if="showFlash && flash.error" class="admin-flash error">
        {{ flash.error }}
      </div>
    </transition>
  </div>
</template>
