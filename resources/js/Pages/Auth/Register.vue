<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AuthLayout from '@/Layouts/AuthLayout.vue'
import { useCms } from '@/Composables/useCms'
import BrandMark from '@/Components/BrandMark.vue'

const { locale } = useCms()
const isAr = locale.value === 'ar'

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
})

function submit() {
    form.post(route('customer.register.submit'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
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
        <h1 class="auth-title">{{ isAr ? 'إنشاء حساب' : 'Create Account' }}</h1>
        <div class="auth-divider" />
        <p class="auth-subtitle flex flex-wrap items-center justify-center gap-x-1 gap-y-1 text-center">
          <template v-if="isAr">
            <span>انضم إلى</span>
            <BrandMark inline :as-link="false" />
            <span>واستمتع بتجربة تسوق فاخرة</span>
          </template>
          <template v-else>
            <span>Join</span>
            <BrandMark inline :as-link="false" />
            <span>for a luxury shopping experience</span>
          </template>
        </p>

        <form @submit.prevent="submit" class="auth-form">
          <div class="auth-field">
            <label class="field-label">{{ isAr ? 'الاسم الكامل' : 'Full Name' }} *</label>
            <input v-model="form.name" type="text" class="field-input-light" required autofocus />
            <span v-if="form.errors.name" class="form-error">{{ form.errors.name }}</span>
          </div>

          <div class="auth-field">
            <label class="field-label">{{ isAr ? 'البريد الإلكتروني' : 'Email' }} *</label>
            <input v-model="form.email" type="email" class="field-input-light" required />
            <span v-if="form.errors.email" class="form-error">{{ form.errors.email }}</span>
          </div>

          <div class="auth-field">
            <label class="field-label">{{ isAr ? 'رقم الهاتف (مصري)' : 'Phone (Egyptian)' }} *</label>
            <input v-model="form.phone" type="tel" class="field-input-light"
                   placeholder="01012345678" dir="ltr" />
            <span v-if="form.errors.phone" class="form-error">{{ form.errors.phone }}</span>
            <span class="auth-hint">{{ isAr ? 'مثال: 01012345678' : 'Example: 01012345678' }}</span>
          </div>

          <div class="auth-field">
            <label class="field-label">{{ isAr ? 'كلمة المرور' : 'Password' }} *</label>
            <input v-model="form.password" type="password" class="field-input-light" required />
            <span v-if="form.errors.password" class="form-error">{{ form.errors.password }}</span>
          </div>

          <div class="auth-field">
            <label class="field-label">{{ isAr ? 'تأكيد كلمة المرور' : 'Confirm Password' }} *</label>
            <input v-model="form.password_confirmation" type="password" class="field-input-light" required />
          </div>

          <button type="submit" class="btn-primary auth-submit" :disabled="form.processing">
            {{ form.processing ? (isAr ? 'جاري التسجيل...' : 'Creating...') : (isAr ? 'إنشاء الحساب' : 'Create Account') }}
          </button>
        </form>

        <p class="auth-switch">
          {{ isAr ? 'لديك حساب؟' : 'Already have an account?' }}
          <Link :href="route('login')" class="auth-switch-link">
            {{ isAr ? 'تسجيل الدخول' : 'Sign In' }}
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

.auth-hint {
  font-size: 11px;
  color: var(--text-light);
  margin-top: 4px;
  display: block;
}

.auth-submit {
  width: 100%;
  margin-top: 8px;
}

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
