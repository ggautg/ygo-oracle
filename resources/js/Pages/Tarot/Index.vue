<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { Sparkles, Loader2, Eye, Hash, ScrollText, Swords, Scale, Shield } from 'lucide-vue-next';
import OraculoNav from '@/Components/OraculoNav.vue';
const POSTURE_ICONS = { Swords, Scale, Shield };

const question = ref('');
const spread = ref(null);
const loading = ref(false);
const error = ref(null);

const counting = ref(false);
const countdownMessage = ref('');

const RITUAL_MESSAGES = [
    'Barajando el destino...',
    'Consultando el mazo...',
    'El oráculo está decidiendo...',
];

function wait(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

async function playCountdown() {
    for (const message of RITUAL_MESSAGES) {
        countdownMessage.value = message;
        await wait(800);
    }
}

const expandedCard = ref(null);
function toggleBreakdown(i) {
    expandedCard.value = expandedCard.value === i ? null : i;
}

async function drawSpread() {
    loading.value = true;
    counting.value = true;
    error.value = null;
    spread.value = null;

    try {
        const [response] = await Promise.all([
            axios.post('/oraculo/tirar', { question: question.value || null }),
            playCountdown(),
        ]);
        spread.value = response.data;
    } catch (e) {
        error.value = 'Algo salió mal. Probá de nuevo.';
    } finally {
        loading.value = false;
        counting.value = false;
    }
}
</script>

<template>
    <div class="min-h-screen bg-obsidian text-stone-200 px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <OraculoNav />
            <h1
                class="text-center font-display font-bold text-4xl sm:text-5xl bg-gradient-to-b from-amber-200 to-gold bg-clip-text text-transparent mb-3">
                El Corazón de las Cartas
            </h1>

            <p class="text-center text-sm text-stone-400 mb-8">
                Escribí o pensa tu pregunta, respira hondo y dejá que el juego saque 3 cartas y descubrí lo que el
                destino tiene para vos.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center mb-12">
                <input v-model="question" type="text" placeholder="¿Sobre qué querés preguntar? (opcional)"
                    @keydown.enter="drawSpread"
                    class="bg-panel border border-white/10 rounded px-4 py-3 text-sm w-full sm:w-80 placeholder:text-stone-500 focus:outline-none focus:border-gold-dim transition-colors" />
                <button @click="drawSpread" :disabled="loading"
                    class="flex items-center justify-center gap-2 bg-gradient-to-b from-amber-300 to-gold text-obsidian font-mono text-xs uppercase tracking-wider font-semibold px-6 py-3 rounded shadow-lg shadow-gold/20 hover:-translate-y-0.5 transition-transform disabled:opacity-60 disabled:translate-y-0">
                    <Loader2 v-if="loading" class="w-4 h-4 animate-spin" />
                    <Sparkles v-else class="w-4 h-4" />
                    {{ loading ? 'Sacando cartas...' : 'Sacar 3 cartas' }}
                </button>
            </div>

            <p v-if="error" class="text-center text-red-400 text-sm mb-8">{{ error }}</p>
            <div v-if="counting" class="text-center py-12">
                <div
                    class="inline-block w-10 h-10 border-2 border-gold-dim border-t-gold rounded-full animate-spin mb-4">
                </div>
                <p class="font-mono text-xs text-gold-dim tracking-wider uppercase">{{ countdownMessage }}</p>
            </div>
            <div v-if="spread" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div v-for="(card, i) in spread.cards" :key="card.name"
                        class="bg-gradient-to-b from-panel to-obsidian border border-white/10 rounded-lg p-5 animate-[fadeIn_0.5s_ease_forwards] opacity-0"
                        :style="{ animationDelay: `${i * 120}ms` }">
                        <div class="font-mono text-[10px] tracking-[0.16em] uppercase text-gold-dim mb-2">
                            {{ card.position }}
                        </div>
                        <img :src="card.image_url" :alt="card.name"
                            class="w-full aspect-[3/4] object-cover rounded-lg mb-3" />
                        <h3 class="font-display text-lg mb-2">{{ card.name }}</h3>
                        <div class="font-mono text-[11px] text-stone-400 border-b border-white/10 pb-3 mb-3">
                            {{ card.race }} · {{ card.attribute }} · Nivel {{ card.level }}
                        </div>
                        <div class="font-mono text-[11px] text-stone-400 border-b border-white/10 pb-3 mb-3">
                            {{ card.description_es || card.description }}
                        </div>
                        <div v-if="card.posture_label"
                            class="inline-flex items-center gap-1.5 border border-white/10 bg-white/5 rounded-full px-3 py-1 text-[11px] font-mono text-stone-300 mb-3">
                            <component :is="POSTURE_ICONS[card.posture_icon]" class="w-3 h-3" />
                            {{ card.posture_label }}
                        </div>
                        <p class="text-[13.5px] italic text-stone-300 leading-relaxed" v-html="card.reading"></p>
                        <button @click="toggleBreakdown(i)"
                            class="mt-3 text-[10px] font-mono text-gold-dim hover:text-gold underline underline-offset-2">
                            {{ expandedCard === i ? 'Ocultar' : '¿Por qué esta lectura?' }}
                        </button>

                        <div v-if="expandedCard === i" class="mt-3 pt-3 border-t border-white/10 space-y-2">
                            <div v-for="(step, j) in card.breakdown" :key="j" class="text-[11px]">
                                <span class="font-mono text-gold-dim">{{ step.label }} ({{ step.value }}):</span>
                                <span class="text-stone-400"> {{ step.essence }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="spread.coincidences.length"
                    class="bg-shadowpurple/10 border border-shadowpurple/40 rounded-lg p-5 flex gap-3">
                    <Eye class="w-4 h-4 text-shadowpurple shrink-0 mt-0.5" />
                    <div class="text-sm space-y-1">
                        <p v-for="(c, i) in spread.coincidences" :key="i">{{ c }}</p>
                    </div>
                </div>

                <div class="bg-panel border border-white/10 rounded-lg p-5 flex items-center gap-5">
                    <div
                        class="w-14 h-14 rounded-full border border-gold-dim flex items-center justify-center font-display font-bold text-2xl text-gold shrink-0">
                        {{ spread.numerology.digit }}
                    </div>
                    <div>
                        <div
                            class="font-mono text-[10px] tracking-[0.16em] uppercase text-gold-dim mb-1 flex items-center gap-1">
                            <Hash class="w-3 h-3" /> Numerología · total {{ spread.numerology.total }}
                        </div>
                        <p class="text-sm">{{ spread.numerology.meaning }}</p>
                    </div>
                </div>

                <div class="bg-panel border border-white/10 rounded-lg p-5">
                    <div
                        class="font-mono text-[10px] tracking-[0.16em] uppercase text-gold-dim mb-2 flex items-center gap-1">
                        <ScrollText class="w-3 h-3" /> Palabras místicas
                    </div>
                    <p class="font-mono text-amber-200 tracking-wide">{{ spread.mystic_message }}</p>
                </div>
                <div class="bg-panel border border-white/10 rounded-lg p-5">
                     <div
                        class="font-mono text-[10px] tracking-[0.16em] uppercase text-gold-dim mb-2 flex items-center gap-1">
                        <ScrollText class="w-3 h-3" /> Sigilo místico
                    </div>
                    <div class="text-center py-4">
                        <div class="inline-block w-24 h-24 opacity-70" v-html="spread.sigil"></div>
                    </div>
                </div>

                <div v-if="spread.uuid" class="text-center">

                    <a :href="`/oraculo/t/${spread.uuid}`" target="_blank"
                        class="inline-flex items-center gap-2 text-xs font-mono text-gold-dim hover:text-gold underline underline-offset-4">
                        Compartir esta tirada
                    </a>
                </div>
            </div>

        </div>
    </div>
</template>

<style>
@keyframes fadeIn {
    to {
        opacity: 1;
    }
}
</style>