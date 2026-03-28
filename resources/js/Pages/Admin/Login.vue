<script setup>
import '../../../css/admin.css'
import { useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const locale = computed(() => page.props.locale ?? 'ar')

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

function submit() {
    form.post(route('admin.login.submit'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
  <div class="admin-login-page" :dir="locale === 'ar' ? 'rtl' : 'ltr'">
    <div class="admin-login-card">
      <div class="admin-login-brand">NIDAN</div>
      <div class="admin-login-divider" />
      <p class="admin-login-subtitle">
        {{ locale === 'ar' ? 'تسجيل الدخول إلى لوحة التحكم' : 'Sign in to your dashboard' }}
      </p>

      <form class="admin-login-form" @submit.prevent="submit">
        <div v-if="form.errors.email" class="admin-login-error">
          {{ form.errors.email }}
        </div>

        <div class="admin-form-group">
          <label class="admin-label" for="email">
            {{ locale === 'ar' ? 'البريد الإلكتروني' : 'Email' }}
          </label>
          <input
              id="email"
              v-model="form.email"
              type="email"
              class="admin-input"
              autocomplete="email"
              autofocus
              required
          />
        </div>

        <div class="admin-form-group">
          <label class="admin-label" for="password">
            {{ locale === 'ar' ? 'كلمة المرور' : 'Password' }}
          </label>
          <input
              id="password"
              v-model="form.password"
              type="password"
              class="admin-input"
              autocomplete="current-password"
              required
          />
        </div>

        <div class="admin-form-group" style="display: flex; align-items: center; gap: 8px;">
          <input
              id="remember"
              v-model="form.remember"
              type="checkbox"
              style="accent-color: var(--admin-gold);"
          />
          <label for="remember" style="font-size: 0.825rem; color: var(--admin-text-muted); cursor: pointer;">
            {{ locale === 'ar' ? 'تذكرني' : 'Remember me' }}
          </label>
        </div>

        <button type="submit" class="admin-btn admin-btn-gold" :disabled="form.processing">
          {{ form.processing
               ? (locale === 'ar' ? 'جاري الدخول...' : 'Signing in...')
               : (locale === 'ar' ? 'تسجيل الدخول' : 'Sign In')
          }}
        </button>
      </form>
    </div>
  </div>
</template>
