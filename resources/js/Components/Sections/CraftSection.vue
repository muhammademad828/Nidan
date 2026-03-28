<script setup>
import { ref, computed } from 'vue'
import { useCms } from '@/Composables/useCms'
import { useRevealAnimation } from '@/Composables/useRevealAnimation'

const { t } = useCms()

useRevealAnimation()

const name        = ref('')
const selectedMat = ref('Gold')

const matColors = {
    Gold:   { color: '#c9a84c', shadow: 'rgba(201,168,76,0.4)' },
    Silver: { color: '#a0a0a8', shadow: 'rgba(160,160,168,0.4)' },
    Rose:   { color: '#c06070', shadow: 'rgba(192,96,112,0.4)' },
}

const displayName = computed(() => name.value.trim() || 'Your Name')
const nameStyle   = computed(() => {
    const mat = matColors[selectedMat.value]
    return {
        color:      mat.color,
        textShadow: `0 0 30px ${mat.shadow}, 0 2px 8px rgba(0,0,0,0.4)`,
    }
})

const materials = [
    { id: 'Gold',   labelKey: 'craft.material_gold',   class: 'swatch-gold' },
    { id: 'Silver', labelKey: 'craft.material_silver', class: 'swatch-silver' },
    { id: 'Rose',   labelKey: 'craft.material_rose',   class: 'swatch-rose' },
]
</script>

<template>
  <section class="craft">
    <!-- Background decorations -->
    <div class="craft-bg-deco" aria-hidden="true">
      <div class="craft-orb-1"></div>
      <div class="craft-orb-2"></div>
      <svg class="craft-wave" viewBox="0 0 400 600" preserveAspectRatio="none" fill="none">
        <path d="M400 0 Q250 150 400 300 Q550 450 400 600" stroke="rgba(184,150,62,0.5)" stroke-width="1.5"/>
        <path d="M350 0 Q200 150 350 300 Q500 450 350 600" stroke="rgba(184,150,62,0.3)" stroke-width="1"/>
      </svg>
    </div>

    <div class="craft-inner">
      <!-- Form -->
      <div class="craft-form-box reveal-start">
        <h2 class="craft-title">{{ t('craft.title', 'Craft Your Story') }}</h2>
        <p class="craft-desc">{{ t('craft.description') }}</p>

        <label class="field-label">{{ t('craft.name_label', 'The Name') }}</label>
        <input
          v-model="name"
          class="field-input"
          type="text"
          :placeholder="t('craft.name_placeholder', 'Enter name (e.g., Sarah)')"
          maxlength="20"
        />

        <label class="field-label">{{ t('craft.material_label', 'Material') }}</label>
        <div class="material-options">
          <button
            v-for="mat in materials"
            :key="mat.id"
            :class="['material-opt', { active: selectedMat === mat.id }]"
            @click="selectedMat = mat.id"
            type="button"
          >
            <div :class="['material-swatch', mat.class]"></div>
            <span class="material-label">{{ t(mat.labelKey, mat.id) }}</span>
          </button>
                </p>

        <button class="btn-create">{{ t('craft.btn_create', 'Create Your Piece') }}</button>
                <label class="craft-field-label">The Name</label>

      <!-- Necklace Preview -->
      <div class="craft-preview reveal">
        <div class="necklace-preview" aria-label="Necklace Preview">
          <div class="necklace-chain"></div>
          <div class="necklace-arc"></div>
          <div class="necklace-name" :style="nameStyle">{{ displayName }}</div>
          <div class="necklace-chain-bottom"></div>
        </div>
      </div>
    </div>
  </section>
                        <span class="material-label">Gold</span>

<style scoped>
.craft {
  position: relative;
  background: var(--dark-craft);
  padding: 90px 40px;
  overflow: hidden;
}

.craft-bg-deco {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
}

.craft-orb-1 {
  position: absolute;
  width: 500px; height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(184,150,62,0.09), transparent 70%);
  top: -100px; inset-inline-end: -100px;
}

.craft-orb-2 {
  position: absolute;
  width: 300px; height: 300px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(184,150,62,0.06), transparent 70%);
  bottom: -50px; inset-inline-start: 30%;
}

.craft-wave {
  position: absolute;
  inset-inline-end: 0;
  top: 0;
  bottom: 0;
  width: 50%;
  opacity: 0.1;
}

.craft-form-box {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  padding: 44px;
}

.craft-title {
  font-family: var(--font-serif);
  font-size: 32px;
  font-weight: 400;
  color: var(--white);
  margin-bottom: 12px;
}

.craft-desc {
  font-size: 13px;
  font-weight: 300;
  color: rgba(255, 255, 255, 0.6);
  line-height: 1.7;
  margin-bottom: 32px;
}

.material-options {
  display: flex;
  gap: 20px;
  margin-top: 12px;
  margin-bottom: 32px;
}

.material-opt {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  background: none;
  border: none;
}

.material-swatch {
  width: 40px; height: 40px;
  border-radius: 50%;
  border: 2px solid transparent;
  transition: border-color 0.2s, transform 0.2s;
}

.material-opt:hover .material-swatch { transform: scale(1.08); }
.material-opt.active .material-swatch { border-color: var(--white); }

.swatch-gold   { background: radial-gradient(circle at 35% 35%, #f0d060, #c9903c); }
.swatch-silver { background: radial-gradient(circle at 35% 35%, #f0f0f0, #a0a0a0); }
.swatch-rose   { background: radial-gradient(circle at 35% 35%, #e8909a, #c06070); }

.material-label {
  font-family: var(--font-sans);
  font-size: 10px;
  color: rgba(255, 255, 255, 0.6);
}

.btn-create {
  width: 100%;
  background: var(--gold-btn);
  color: var(--white);
  font-family: var(--font-sans);
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  padding: 16px;
  border: none;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}

.btn-create:hover { background: var(--gold); transform: translateY(-1px); }

.craft-preview {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  min-height: 320px;
}

.necklace-preview {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.necklace-chain {
  width: 2px;
  height: 80px;
  background: linear-gradient(to bottom, transparent, rgba(184,150,62,0.6));
}

.necklace-arc {
  width: 140px;
  height: 70px;
  border: 2px solid rgba(184,150,62,0.5);
  border-bottom: none;
  border-radius: 140px 140px 0 0;
}

.necklace-name {
  font-family: var(--font-serif);
  font-size: 36px;
  font-style: italic;
  font-weight: 600;
  color: var(--gold-light);
  text-shadow: 0 0 30px rgba(184,150,62,0.4), 0 2px 8px rgba(0,0,0,0.4);
  margin-top: -4px;
  transition: all 0.3s ease;
}

.necklace-chain-bottom {
  width: 2px;
  height: 40px;
  background: linear-gradient(to bottom, rgba(184,150,62,0.6), transparent);
}

@media (max-width: 1000px) {
  .craft-preview { display: none; }
}
</style>
                    </div>
                    <div
                        class="material-opt"
                        :class="{ active: activeMaterial === 'Silver' }"
                        @click="selectMaterial('Silver')"
                    >
                        <div class="material-swatch swatch-silver"></div>
                        <span class="material-label">Silver</span>
                    </div>
                    <div
                        class="material-opt"
                        :class="{ active: activeMaterial === 'Rose' }"
                        @click="selectMaterial('Rose')"
                    >
                        <div class="material-swatch swatch-rose"></div>
                        <span class="material-label">Rose</span>
                    </div>
                </div>
                <button class="btn-create">Create Your Piece</button>
            </div>
            <div class="craft-preview reveal">
                <div class="necklace-preview">
                    <div class="necklace-chain"></div>
                    <div
                        class="necklace-arc"
                        :style="{
                            borderColor:
                                activeMaterial === 'Gold'
                                    ? 'rgba(184,150,62,0.5)'
                                    : activeMaterial === 'Silver'
                                      ? 'rgba(200,200,200,0.5)'
                                      : 'rgba(232,144,154,0.5)',
                        }"
                    ></div>
                    <div
                        class="necklace-name"
                        :style="{
                            color:
                                activeMaterial === 'Gold'
                                    ? 'var(--gold-light)'
                                    : activeMaterial === 'Silver'
                                      ? '#e0e0e0'
                                      : '#e8909a',
                        }"
                    >
                        {{ nameInput || "Nidan" }}
                    </div>
                    <div
                        class="necklace-chain-bottom"
                        :style="{
                            background:
                                activeMaterial === 'Gold'
                                    ? 'linear-gradient(to bottom, rgba(184,150,62,0.6), transparent)'
                                    : activeMaterial === 'Silver'
                                      ? 'linear-gradient(to bottom, rgba(200,200,200,0.6), transparent)'
                                      : 'linear-gradient(to bottom, rgba(232,144,154,0.6), transparent)',
                        }"
                    ></div>
                </div>
            </div>
        </div>
    </section>
</template>
