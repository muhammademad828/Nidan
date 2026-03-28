<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { useCms } from "@/Composables/useCms";

const { locale } = useCms();
const isAr = locale.value === "ar";

const form = useForm({ email: "", phone: "" });

function submit() {
    form.post(route("password.email"));
}
</script>

<template>
    <AuthLayout>
        <div class="auth-page">
            <div class="auth-card">
                <h1 class="auth-title">
                    {{ isAr ? "نسيت كلمة المرور" : "Forgot Password" }}
                </h1>
                <div class="auth-divider" />
                <p class="auth-subtitle">
                    {{
                        isAr
                            ? "أدخل بريدك الإلكتروني ورقم الهاتف للتحقق من هويتك"
                            : "Enter your email and phone number to verify your identity"
                    }}
                </p>

                <form @submit.prevent="submit" class="auth-form">
                    <div v-if="form.errors.email" class="auth-error">
                        {{ form.errors.email }}
                    </div>

                    <div class="auth-field">
                        <label class="field-label"
                            >{{ isAr ? "البريد الإلكتروني" : "Email" }} *</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            class="field-input-light"
                            required
                            autofocus
                        />
                    </div>

                    <div class="auth-field">
                        <label class="field-label"
                            >{{ isAr ? "رقم الهاتف" : "Phone Number" }} *</label
                        >
                        <input
                            v-model="form.phone"
                            type="tel"
                            class="field-input-light"
                            required
                            placeholder="01xxxxxxxxx"
                        />
                    </div>

                    <button
                        type="submit"
                        class="btn-primary auth-submit"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? isAr
                                    ? "جاري الإرسال..."
                                    : "Sending..."
                                : isAr
                                  ? "تحقق من الهوية"
                                  : "Verify Identity"
                        }}
                    </button>
                </form>

                <p class="auth-switch">
                    <Link
                        :href="route('login')"
                        class="auth-switch-link"
                    >
                        {{
                            isAr
                                ? "← العودة لتسجيل الدخول"
                                : "← Back to Sign In"
                        }}
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
.auth-form {
    text-align: start;
}
.auth-field {
    margin-bottom: 16px;
}
.auth-error {
    background: rgba(220, 38, 38, 0.06);
    color: #dc2626;
    padding: 10px 14px;
    border-radius: 4px;
    font-size: 13px;
    margin-bottom: 16px;
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
    transition: color 0.2s;
}
.auth-switch-link:hover {
    color: var(--dark);
}
</style>
