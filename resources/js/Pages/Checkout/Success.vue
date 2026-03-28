<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useCms } from '@/Composables/useCms'

defineProps({
    orderNumber: { type: String, required: true },
    orderId:     { type: Number, default: null },
})

const { t } = useCms()
</script>

<template>
  <AppLayout>
    <div class="success-page">
      <div class="success-inner">

        <!-- Checkmark Animation -->
        <div class="success-icon" aria-hidden="true">
          <svg viewBox="0 0 80 80" fill="none">
            <circle cx="40" cy="40" r="38" stroke="var(--gold)" stroke-width="2" opacity="0.3"/>
            <circle cx="40" cy="40" r="38" stroke="var(--gold)" stroke-width="2"
                    stroke-dasharray="239" stroke-dashoffset="0"
                    class="success-circle-anim"/>
            <path d="M24 40l12 12 20-20" stroke="var(--gold)" stroke-width="2.5"
                  stroke-linecap="round" stroke-linejoin="round"
                  class="success-check-anim"/>
          </svg>
        </div>

        <p class="success-eyebrow">{{ t('order_success.title', 'Order Confirmed!') }}</p>
        <h1 class="success-title">Thank you for your order</h1>
        <p class="success-message">{{ t('order_success.message', 'Our team will contact you shortly to confirm your delivery.') }}</p>

        <!-- Order Number Badge -->
        <div class="order-number-card">
          <span class="order-number-label">{{ t('order_success.order_number', 'Order Number') }}</span>
          <span class="order-number-value">{{ orderNumber }}</span>
        </div>

        <!-- Next Steps -->
        <div class="success-steps">
          <div class="success-step">
            <div class="step-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
              </svg>
            </div>
            <div>
              <p class="step-title">We'll call you soon</p>
              <p class="step-desc">Our team will confirm your order within 2 hours</p>
            </div>
          </div>
          <div class="success-step">
            <div class="step-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="1" y="3" width="15" height="13" rx="2"/>
                <path d="M16 8h4l3 5v4h-7V8z"/>
                <circle cx="5.5" cy="18.5" r="2.5"/>
                <circle cx="18.5" cy="18.5" r="2.5"/>
              </svg>
            </div>
            <div>
              <p class="step-title">Your gift is being prepared</p>
              <p class="step-desc">Crafted with care and elegant packaging</p>
            </div>
          </div>
          <div class="success-step">
            <div class="step-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20 12v10H4V12"/><path d="M22 7H2v5h20V7z"/>
                <path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/>
                <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
              </svg>
            </div>
            <div>
              <p class="step-title">Delivered on your chosen date</p>
              <p class="step-desc">On-time delivery to your specified address</p>
            </div>
          </div>
        </div>

        <!-- CTAs -->
        <div class="success-ctas">
          <Link :href="route('home')" class="btn-primary">Back to Home</Link>
          <Link :href="route('products.index')" class="btn-outline-dark">Continue Shopping</Link>
        </div>

      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.success-page {
  padding-top: 0;
  min-height: 100vh;
  background: var(--cream-light);
  display: flex;
  align-items: center;
}

.success-inner {
  max-width: 620px;
  margin-inline: auto;
  padding: 60px var(--section-padding-inline) 80px;
  text-align: center;
}

/* ── Icon ── */
.success-icon {
  width: 88px;
  height: 88px;
  margin-inline: auto;
  margin-bottom: 28px;
}

.success-icon svg { width: 100%; height: 100%; }

.success-circle-anim {
  animation: drawCircle 0.8s ease-out 0.2s both;
}

.success-check-anim {
  stroke-dasharray: 40;
  stroke-dashoffset: 40;
  animation: drawCheck 0.5s ease-out 0.9s both;
}

@keyframes drawCircle {
  from { stroke-dashoffset: 239; }
  to   { stroke-dashoffset: 0; }
}

@keyframes drawCheck {
  from { stroke-dashoffset: 40; }
  to   { stroke-dashoffset: 0; }
}

/* ── Text ── */
.success-eyebrow {
  font-size: 10px;
  font-weight: 600;
  letter-spacing: 0.3em;
  text-transform: uppercase;
  color: var(--gold-light);
  margin-bottom: 12px;
}

[dir='rtl'] .success-eyebrow { letter-spacing: 0; }

.success-title {
  font-family: var(--font-serif);
  font-size: clamp(28px, 4vw, 42px);
  font-weight: 400;
  color: var(--dark);
  margin-bottom: 16px;
}

.success-message {
  font-size: 14px;
  font-weight: 300;
  color: var(--text-light);
  line-height: 1.7;
  margin-bottom: 32px;
}

/* ── Order Badge ── */
.order-number-card {
  display: inline-flex;
  align-items: center;
  gap: 16px;
  padding: 16px 28px;
  background: var(--white);
  border: 1px solid rgba(201, 168, 76, 0.3);
  border-radius: 8px;
  margin-bottom: 40px;
}

.order-number-label {
  font-size: 10px;
  font-weight: 600;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--text-light);
}

[dir='rtl'] .order-number-label { letter-spacing: 0; }

.order-number-value {
  font-family: var(--font-serif);
  font-size: 22px;
  font-weight: 600;
  color: var(--gold);
  letter-spacing: 0.1em;
}

/* ── Steps ── */
.success-steps {
  display: flex;
  flex-direction: column;
  gap: 20px;
  text-align: start;
  margin-bottom: 40px;
  background: var(--white);
  border: 1px solid rgba(58, 53, 48, 0.08);
  border-radius: 8px;
  padding: 24px;
}

.success-step {
  display: flex;
  align-items: flex-start;
  gap: 14px;
}

.step-icon {
  width: 40px;
  height: 40px;
  flex-shrink: 0;
  background: rgba(201, 168, 76, 0.1);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.step-icon svg {
  width: 18px;
  height: 18px;
  color: var(--gold);
}

.step-title {
  font-size: 14px;
  font-weight: 500;
  color: var(--dark);
  margin-bottom: 2px;
}

.step-desc {
  font-size: 12px;
  font-weight: 300;
  color: var(--text-light);
  line-height: 1.5;
}

/* ── CTAs ── */
.success-ctas {
  display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
}
</style>
