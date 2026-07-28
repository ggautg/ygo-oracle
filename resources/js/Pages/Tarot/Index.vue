<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { Sparkles, Loader2, Eye, Hash, ScrollText } from 'lucide-vue-next';

const question = ref('');
const spread = ref(null);
const loading = ref(false);
const error = ref(null);

async function drawSpread() {
    loading.value = true;
    error.value = null;

    try {
        const response = await axios.post('/oraculo/tirar', {
            question: question.value || null,
        });
        spread.value = response.data;
    } catch (e) {
        error.value = 'Algo salió mal. Probá de nuevo.';
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <div class="min-h-screen bg-obsidian text-stone-200 px-4 py-12">
        <div class="max-w-4xl mx-auto">

            <p class="text-center font-mono text-[11px] tracking-[0.2em] uppercase text-gold-dim mb-2">
                Sin IA · datos reales, sin narración generada
            </p>
            <h1 class="text-center font-display font-bold text-4xl sm:text-5xl bg-gradient-to-b from-amber-200 to-gold bg-clip-text text-transparent mb-3">
                El Corazón de las Cartas
            </h1>
            <p class="text-center text-stone-400 max-w-md mx-auto mb-10 text-sm leading-relaxed">
                No es magia, son datos reales de la carta pasando por capas fijas de significado.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center mb-12">
                <input
                    v-model="question"
                    type="text"
                    placeholder="¿Sobre qué querés preguntar? (opcional)"
                    class="bg-panel border border-white/10 rounded px-4 py-3 text-sm w-full sm:w-80 placeholder:text-stone-500 focus:outline-none focus:border-gold-dim transition-colors"
                />
                <button
                    @click="drawSpread"
                    :disabled="loading"
                    class="flex items-center justify-center gap-2 bg-gradient-to-b from-amber-300 to-gold text-obsidian font-mono text-xs uppercase tracking-wider font-semibold px-6 py-3 rounded shadow-lg shadow-gold/20 hover:-translate-y-0.5 transition-transform disabled:opacity-60 disabled:translate-y-0"
                >
                    <Loader2 v-if="loading" class="w-4 h-4 animate-spin" />
                    <Sparkles v-else class="w-4 h-4" />
                    {{ loading ? 'Sacando cartas...' : 'Sacar 3 cartas' }}
                </button>
            </div>

            <p v-if="error" class="text-center text-red-400 text-sm mb-8">{{ error }}</p>

            <div v-if="spread" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div
                        v-for="(card, i) in spread.cards"
                        :key="card.name"
                        class="bg-gradient-to-b from-panel to-obsidian border border-white/10 rounded-lg p-5 animate-[fadeIn_0.5s_ease_forwards] opacity-0"
                        :style="{ animationDelay: `${i * 120}ms` }"
                    >
                        <div class="font-mono text-[10px] tracking-[0.16em] uppercase text-gold-dim mb-2">
                            {{ card.position }}
                        </div>
                        <h3 class="font-display text-lg mb-2">{{ card.name }}</h3>
                        <div class="font-mono text-[11px] text-stone-400 border-b border-white/10 pb-3 mb-3">
                            {{ card.race }} · {{ card.attribute }} · Nivel {{ card.level }}
                        </div>
                        <p class="text-[13.5px] italic text-stone-300 leading-relaxed">{{ card.reading }}</p>
                    </div>
                </div>

                <div v-if="spread.coincidences.length" class="bg-shadowpurple/10 border border-shadowpurple/40 rounded-lg p-5 flex gap-3">
                    <Eye class="w-4 h-4 text-shadowpurple shrink-0 mt-0.5" />
                    <div class="text-sm space-y-1">
                        <p v-for="(c, i) in spread.coincidences" :key="i">{{ c }}</p>
                    </div>
                </div>

                <div class="bg-panel border border-white/10 rounded-lg p-5 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-full border border-gold-dim flex items-center justify-center font-display font-bold text-2xl text-gold shrink-0">
                        {{ spread.numerology.digit }}
                    </div>
                    <div>
                        <div class="font-mono text-[10px] tracking-[0.16em] uppercase text-gold-dim mb-1 flex items-center gap-1">
                            <Hash class="w-3 h-3" /> Numerología · total {{ spread.numerology.total }}
                        </div>
                        <p class="text-sm">{{ spread.numerology.meaning }}</p>
                    </div>
                </div>

                <div class="bg-panel border border-white/10 rounded-lg p-5">
                    <div class="font-mono text-[10px] tracking-[0.16em] uppercase text-gold-dim mb-2 flex items-center gap-1">
                        <ScrollText class="w-3 h-3" /> Mensaje místico
                    </div>
                    <p class="font-mono text-amber-200 tracking-wide">{{ spread.mystic_message }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@keyframes fadeIn {
    to { opacity: 1; }
}
</style>