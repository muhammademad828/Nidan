<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AuthLayout from '@/Layouts/AuthLayout.vue'
import { useCms } from '@/Composables/useCms'
import BrandMark from '@/Components/BrandMark.vue'

const { locale } = useCms()
const isAr = locale.value === 'ar'

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

function submit() {
    form.post(route('customer.login.submit'), {
        onFinish: () => form.reset('password'),
        onSuccess: () => {
            if (window.top !== window.self) {
                window.top.location.href = route('home')
            }
        },
    })
}
</script>

<template>
  <AuthLayout>
    <div class="auth-page">
      <div class="auth-card">
        <h1 class="auth-title">{{ isAr ? 'تسجيل الدخول' : 'Sign In' }}</h1>
        <div class="auth-divider" />
        <p class="auth-subtitle flex flex-wrap items-center justify-center gap-x-1 gap-y-1 text-center">
          <template v-if="isAr">
            <span>مرحبًا بعودتك إلى</span>
            <BrandMark inline :as-link="false" />
          </template>
          <template v-else>
            <span>Welcome back to</span>
            <BrandMark inline :as-link="false" />
          </template>
        </p>

        <form @submit.prevent="submit" class="auth-form">
          <div v-if="form.errors.email" class="auth-error">
            {{ form.errors.email }}
          </div>

          <div class="auth-field">
            <label class="field-label">{{ isAr ? 'البريد الإلكتروني' : 'Email' }} *</label>
            <input v-model="form.email" type="email" class="field-input-light" required autofocus />
          </div>

          <div class="auth-field">
            <label class="field-label">{{ isAr ? 'كلمة المرور' : 'Password' }} *</label>
            <input v-model="form.password" type="password" class="field-input-light" required />
          </div>

          <div class="auth-remember-row">
            <label class="auth-remember">
              <input v-model="form.remember" type="checkbox" class="addon-checkbox" />
              <span>{{ isAr ? 'تذكرني' : 'Remember me' }}</span>
            </label>
            <Link :href="route('password.request')" class="auth-forgot">
              {{ isAr ? 'نسيت كلمة المرور؟' : 'Forgot password?' }}
            </Link>
          </div>

          <button type="submit" class="btn-primary auth-submit" :disabled="form.processing">
            {{ form.processing ? (isAr ? 'جاري الدخول...' : 'Signing in...') : (isAr ? 'تسجيل الدخول' : 'Sign In') }}
          </button>
        </form>

        <p class="auth-switch">
          {{ isAr ? 'ليس لديك حساب؟' : "Don't have an account?" }}
          <Link :href="route('customer.register')" class="auth-switch-link">
            {{ isAr ? 'إنشاء حساب' : 'Create Account' }}
          </Link>
        </p>
      </div>
    </div>
  </AuthLayout>
</template>

<style scoped>
.auth-page {
  padding-top: 0;
  min-height: 100vh;
  background: var(--cream-light);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: calc(var(--layout-header-offset) + 40px) 20px 60px;
}

.auth-card {
  background: var(--white);
  border: 1px solid rgba(58, 53, 48, 0.08);
  border-radius: 8px;
  padding: 44px 36px;
  width: 100%;
  max-width: 440px;
  text-align: center;
}

.auth-title {
  font-family: var(--font-serif);
  font-size: 28px;
  font-weight: 400;
  color: var(--dark);
}

.auth-divider {
  width: 48px;
  height: 2px;
  background: var(--gold);
  margin: 16px auto;
  border-radius: 2px;
}

.auth-subtitle {
  font-size: 13px;
  font-weight: 300;
  color: var(--text-light);
  margin-bottom: 28px;
}

.auth-form { text-align: start; }
.auth-field { margin-bottom: 16px; }

.auth-error {
  background: rgba(220, 38, 38, 0.06);
  color: #dc2626;
  padding: 10px 14px;
  border-radius: 4px;
  font-size: 13px;
  margin-bottom: 16px;
}

.auth-remember-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.auth-remember {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: var(--charcoal);
  cursor: pointer;
}

.auth-forgot {
  font-size: 12px;
  color: var(--gold);
  transition: color 0.2s;
}

.auth-forgot:hover { color: var(--dark); }

.auth-submit { width: 100%; }

.auth-switch {
  margin-top: 20px;
  font-size: 13px;
  color: var(--text-light);
}

.auth-switch-link {
  color: var(--gold);
  font-weight: 500;
  margin-inline-start: 4px;
  transition: color 0.2s;
}

.auth-switch-link:hover { color: var(--dark); }
</style>
