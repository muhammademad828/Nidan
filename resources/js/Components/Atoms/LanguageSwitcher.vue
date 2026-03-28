<script setup>
import { computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const page   = usePage()
const locale = computed(() => page.props.locale ?? 'en')
const isAr   = computed(() => locale.value === 'ar')

function switchLocale(lang) {
    router.post(route('locale.switch', lang), {}, { preserveScroll: true })
}
</script>

<template>
  <div class="lang-switcher" role="navigation" aria-label="Language Switcher">
    <button
      :class="['lang-btn', { active: !isAr }]"
      @click="switchLocale('en')"
      :aria-current="!isAr ? 'true' : undefined"
    >
      EN
    </button>
    <span class="lang-divider">|</span>
    <button
      :class="['lang-btn', { active: isAr }]"
      @click="switchLocale('ar')"
      :aria-current="isAr ? 'true' : undefined"
    >
      ع
    </button>
  </div>
</template>

<style scoped>
.lang-switcher {
  display: flex;
  align-items: center;
  gap: 4px;
  direction: ltr;
}

.lang-btn {
  font-family: var(--font-sans);
  font-size: 10px;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--charcoal);
  background: none;
  border: none;
  cursor: pointer;
  padding: 2px 4px;
  transition: color 0.2s;
}

.lang-btn:hover,
.lang-btn.active {
  color: var(--gold);
}

.lang-divider {
  font-size: 10px;
  color: rgba(58, 53, 48, 0.3);
}
</style>
