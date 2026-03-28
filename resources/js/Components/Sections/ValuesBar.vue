<script setup>
import { useCms } from '@/Composables/useCms'
import { useRevealAnimation } from '@/Composables/useRevealAnimation'

const { t } = useCms()
useRevealAnimation()

const values = [
    {
        titleKey: 'values.quality_title',
        descKey:  'values.quality_desc',
        icon:     `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`,
    },
    {
        titleKey: 'values.delivery_title',
        descKey:  'values.delivery_desc',
        icon:     `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v4h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>`,
    },
    {
        titleKey: 'values.custom_title',
        descKey:  'values.custom_desc',
        icon:     `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>`,
    },
    {
        titleKey: 'values.packaging_title',
        descKey:  'values.packaging_desc',
        icon:     `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>`,
    },
]
</script>

<template>
  <section class="values">
    <div class="values-inner">
      <div
        v-for="(val, i) in values"
        :key="val.titleKey"
        :class="['value-item', 'reveal', `d${i + 1}`]"
      >
        <div class="value-icon" v-html="val.icon" aria-hidden="true"></div>
        <div class="value-title">{{ t(val.titleKey) }}</div>
        <div class="value-desc">{{ t(val.descKey) }}</div>
      </div>
    </div>
  </section>
</template>
<style scoped>
.values {
  background: var(--cream);
  border-top: 1px solid rgba(58, 53, 48, 0.08);
  border-bottom: 1px solid rgba(58, 53, 48, 0.08);
  padding: 64px 40px;
}

.values-inner {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  max-width: var(--max-width);
  margin: 0 auto;
}

.value-item {
  text-align: center;
  padding: 0 28px;
  border-inline-end: 1px solid rgba(58, 53, 48, 0.12);
}
.value-item:last-child { border-inline-end: none; }

.value-icon {
  width: 36px;
  height: 36px;
  margin: 0 auto 18px;
  color: var(--gold);
}
.value-icon :deep(svg) { width: 100%; height: 100%; }

.value-title {
  font-family: var(--font-serif);
  font-size: 18px;
  font-weight: 400;
  color: var(--dark);
  margin-bottom: 8px;
}

.value-desc {
  font-size: 12px;
  font-weight: 300;
  color: var(--text-light);
  line-height: 1.65;
}

@media (max-width: 900px) {
  .values-inner { grid-template-columns: repeat(2, 1fr); row-gap: 40px; }
  .value-item:nth-child(2) { border-inline-end: none; }
  .value-item:nth-child(3) { border-inline-end: 1px solid rgba(58,53,48,0.12); }
  @media (max-width: 640px) {
    .value-item:nth-child(3) { border-inline-end: none; }
  }
}

@media (max-width: 640px) {
  .values { padding: 48px 20px; }
  .values-inner { grid-template-columns: 1fr; row-gap: 32px; }
  .value-item { border-inline-end: none; padding: 0; }
}
</style>

