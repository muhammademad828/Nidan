<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { useCms } from "@/Composables/useCms";

const props = defineProps({
    email: { type: String, default: "" },
    direct: { type: Boolean, default: false },
});

const { locale } = useCms();
const isAr = locale.value === "ar";

const otpDigits = ref(["", "", "", "", "", ""]);
const otpInputs = ref([]);

const form = useForm({
    email: props.email,
    otp: props.direct ? "bypass" : "", // Use bypass token for direct mode
    password: "",
    password_confirmation: "",
});

function handleOtpInput(index, event) {
    const val = event.target.value.replace(/\D/g, "");
    otpDigits.value[index] = val.charAt(0) || "";

    if (val && index < 5) {
        otpInputs.value[index + 1]?.focus();
    }

    form.otp = otpDigits.value.join("");
}

function handleOtpKeydown(index, event) {
    if (event.key === "Backspace" && !otpDigits.value[index] && index > 0) {
        otpInputs.value[index - 1]?.focus();
    }
}

function handlePaste(event) {
    const text = event.clipboardData
        .getData("text")
        .replace(/\D/g, "")
        .slice(0, 6);
    for (let i = 0; i < 6; i++) {
        otpDigits.value[i] = text[i] || "";
    }
    form.otp = otpDigits.value.join("");
    event.preventDefault();
}

function submit() {
    // Use direct route for signed URL bypass, otherwise use OTP route
    const routeName = props.direct
        ? "password.reset.process"
        : "password.update";
    form.post(route(routeName), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
}
</script>

<template>
    <AuthLayout>
        <div class="auth-page">
            <div class="auth-card">
                <h1 class="auth-title">
                    {{ isAr ? "إعادة تعيين كلمة المرور" : "Reset Password" }}
                </h1>
                <div class="auth-divider" />
                <p class="auth-subtitle">
                    {{
                        props.direct
                            ? isAr
                                ? "قم بتعيين كلمة المرور الجديدة"
                                : "Set your new password"
                            : isAr
                              ? "أدخل رمز التحقق المرسل إلى بريدك"
                              : "Enter the OTP sent to your email"
                    }}
                </p>

                <form @submit.prevent="submit" class="auth-form">
                    <div v-if="form.errors.otp" class="auth-error">
                        {{ form.errors.otp }}
                    </div>

                    <input type="hidden" v-model="form.email" />

                    <div v-if="!props.direct" class="auth-field">
                        <label class="field-label">{{
                            isAr ? "رمز التحقق" : "Verification Code"
                        }}</label>
                        <div class="otp-inputs" @paste="handlePaste">
                            <input
                                v-for="(_, i) in 6"
                                :key="i"
                                :ref="(el) => (otpInputs[i] = el)"
                                type="text"
                                inputmode="numeric"
                                maxlength="1"
                                class="otp-digit"
                                :value="otpDigits[i]"
                                @input="handleOtpInput(i, $event)"
                                @keydown="handleOtpKeydown(i, $event)"
                            />
                        </div>
                    </div>

                    <div class="auth-field">
                        <label class="field-label"
                            >{{
                                isAr ? "كلمة المرور الجديدة" : "New Password"
                            }}
                            *</label
                        >
                        <input
                            v-model="form.password"
                            type="password"
                            class="field-input-light"
                            required
                        />
                        <span v-if="form.errors.password" class="form-error">{{
                            form.errors.password
                        }}</span>
                    </div>

                    <div class="auth-field">
                        <label class="field-label"
                            >{{
                                isAr ? "تأكيد كلمة المرور" : "Confirm Password"
                            }}
                            *</label
                        >
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            class="field-input-light"
                            required
                        />
                    </div>

                    <button
                        type="submit"
                        class="btn-primary auth-submit"
                        :disabled="
                            form.processing ||
                            (!props.direct && form.otp.length !== 6)
                        "
                    >
                        {{
                            form.processing
                                ? isAr
                                    ? "جاري التحديث..."
                                    : "Resetting..."
                                : isAr
                                  ? "تغيير كلمة المرور"
                                  : "Reset Password"
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
    max-width: 480px;
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
    text-align: center;
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

.otp-inputs {
    display: flex;
    gap: 8px;
    justify-content: center;
    direction: ltr;
    margin: 8px 0;
}

.otp-digit {
    width: 48px;
    height: 56px;
    text-align: center;
    font-size: 24px;
    font-weight: 600;
    font-family: monospace;
    color: var(--dark);
    background: var(--cream-light);
    border: 2px solid rgba(58, 53, 48, 0.15);
    border-radius: 8px;
    outline: none;
    transition: border-color 0.2s;
}

.otp-digit:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.12);
}
</style>
