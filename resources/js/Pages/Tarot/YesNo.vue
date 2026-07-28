<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { Zap, Loader2, ArrowLeft } from 'lucide-vue-next';
import OraculoNav from '@/Components/OraculoNav.vue';

const question = ref('');
const result = ref(null);
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

async function drawYesNo() {
    loading.value = true;
    counting.value = true;
    error.value = null;
    result.value = null;

    try {
        const [response] = await Promise.all([
            axios.post('/oraculo/si-no/tirar', { question: question.value || null }),
            playCountdown(),
        ]);
        result.value = response.data;
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
        <div class="max-w-md mx-auto">
            <OraculoNav />
            <p class="text-center font-mono text-[11px] tracking-[0.2em] uppercase text-gold-dim mb-2">
                Modo rápido
            </p>
            <h1
                class="text-center font-display font-bold text-3xl sm:text-4xl bg-gradient-to-b from-amber-200 to-gold bg-clip-text text-transparent mb-3">
                Sí o no
            </h1>
            <p class="text-center text-stone-400 mb-10 text-sm leading-relaxed">
                Una sola carta, una respuesta directa — según su postura ofensiva, defensiva o equilibrada.
            </p>

            <div class="flex flex-col gap-3 mb-10">
                <input v-model="question" type="text" placeholder="Tu pregunta (opcional)"
                    class="bg-panel border border-white/10 rounded px-4 py-3 text-sm w-full placeholder:text-stone-500 focus:outline-none focus:border-gold-dim transition-colors" />
                <button @click="drawYesNo" :disabled="loading"
                    class="flex items-center justify-center gap-2 bg-gradient-to-b from-amber-300 to-gold text-obsidian font-mono text-xs uppercase tracking-wider font-semibold px-6 py-3 rounded shadow-lg shadow-gold/20 hover:-translate-y-0.5 transition-transform disabled:opacity-60 disabled:translate-y-0">
                    <Loader2 v-if="loading" class="w-4 h-4 animate-spin" />
                    <Zap v-else class="w-4 h-4" />
                    {{ loading ? 'Preguntando...' : 'Preguntar' }}
                </button>
            </div>

            <p v-if="error" class="text-center text-red-400 text-sm mb-8">{{ error }}</p>

            <div v-if="counting" class="text-center py-12">
                <div
                    class="inline-block w-10 h-10 border-2 border-gold-dim border-t-gold rounded-full animate-spin mb-4">
                </div>
                <p class="font-mono text-xs text-gold-dim tracking-wider uppercase">{{ countdownMessage }}</p>
            </div>

            <div v-if="result" class="bg-panel border border-gold-dim rounded-lg p-6 text-center">
                <img v-if="result.card.image_url" :src="result.card.image_url" :alt="result.card.name"
                    class="w-64 sm:w-56 mx-auto rounded-lg mb-4 shadow-lg shadow-black/40" />
                <div class="font-display text-4xl font-bold text-gold mb-2">{{ result.answer }}</div>
                <div class="text-xs font-mono text-stone-400 mb-1">{{ result.card.name }}</div>
                <div class="text-[11px] font-mono text-stone-500 mb-3">{{ result.card.posture_label }}</div>
                <div class="flex justify-center gap-4 text-[11px] font-mono text-stone-400">
                    <span>ATK <b class="text-stone-200">{{ result.card.atk }}</b></span>
                    <span>DEF <b class="text-stone-200">{{ result.card.def }}</b></span>
                </div>
            </div>

            <div class="text-center mt-10">
                <Link href="/oraculo"
                    class="inline-flex items-center gap-2 text-xs font-mono text-gold-dim hover:text-gold underline underline-offset-4">
                    <ArrowLeft class="w-3 h-3" /> Ir a la tirada de tres cartas
                </Link>
            </div>
        </div>
    </div>
</template>