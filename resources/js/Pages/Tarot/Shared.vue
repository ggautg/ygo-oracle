<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Sparkles, Loader2, Eye, Hash, ScrollText, Swords, Scale, Shield } from 'lucide-vue-next';

const POSTURE_ICONS = { Swords, Scale, Shield };

const props = defineProps({
    reading: {
        type: Object,
        required: true,
    },
});
</script>

<template>

    <Head title="Una tirada del Corazón de las Cartas" />

    <div class="min-h-screen bg-obsidian text-stone-200 px-4 py-12">
        <div class="max-w-4xl mx-auto">

            <p class="text-center font-mono text-[11px] tracking-[0.2em] uppercase text-gold-dim mb-2">
                Tirada compartida
            </p>
            <h1
                class="text-center font-display font-bold text-4xl sm:text-5xl bg-gradient-to-b from-amber-200 to-gold bg-clip-text text-transparent mb-3">
                El Corazón de las Cartas
            </h1>

            <p v-if="reading.question"
                class="text-center text-stone-400 max-w-md mx-auto mb-10 text-sm leading-relaxed italic">
                "{{ reading.question }}"
            </p>
            <p v-else class="text-center text-stone-500 max-w-md mx-auto mb-10 text-xs leading-relaxed">
                Tirada general, sin pregunta puntual.
            </p>

            <div class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div v-for="(card, i) in reading.cards" :key="card.name"
                        class="bg-gradient-to-b from-panel to-obsidian border border-white/10 rounded-lg p-5 animate-[fadeIn_0.5s_ease_forwards] opacity-0"
                        :style="{ animationDelay: `${i * 120}ms` }">
                        <div class="font-mono text-[10px] tracking-[0.16em] uppercase text-gold-dim mb-2">
                            {{ card.position }}
                        </div>
                        <img v-if="card.image_url" :src="card.image_url" :alt="card.name"
                            class="w-full aspect-[3/4] object-cover rounded-lg mb-3" />
                        <h3 class="font-display text-lg mb-2">{{ card.name }}</h3>
                        <div class="font-mono text-[11px] text-stone-400 border-b border-white/10 pb-3 mb-3">
                            {{ card.race }} · {{ card.attribute }} · Nivel {{ card.level }}
                        </div>
                        <div class="font-mono text-[11px] text-stone-400 border-b border-white/10 pb-3 mb-3">
                            {{ card.description_es || card.description }}
                        </div>
                        <div v-if="card.posture_label"
                            class="inline-flex items-center gap-1.5 bg-white/5 border border-white/10 rounded-full px-3 py-1 text-[11px] font-mono text-stone-300 mb-3">
                            <component :is="POSTURE_ICONS[card.posture_icon]" class="w-3 h-3" />
                            {{ card.posture_label }}
                        </div>
                        <p class="text-[13.5px] italic text-stone-300 leading-relaxed" v-html="card.reading"></p>
                    </div>
                </div>

                <div v-if="reading.coincidences.length"
                    class="bg-shadowpurple/10 border border-shadowpurple/40 rounded-lg p-5 flex gap-3">
                    <Eye class="w-4 h-4 text-shadowpurple shrink-0 mt-0.5" />
                    <div class="text-sm space-y-1">
                        <p v-for="(c, i) in reading.coincidences" :key="i">{{ c }}</p>
                    </div>
                </div>

                <div class="bg-panel border border-white/10 rounded-lg p-5 flex items-center gap-5">
                    <div
                        class="w-14 h-14 rounded-full border border-gold-dim flex items-center justify-center font-display font-bold text-2xl text-gold shrink-0">
                        {{ reading.numerology.digit }}
                    </div>
                    <div>
                        <div
                            class="font-mono text-[10px] tracking-[0.16em] uppercase text-gold-dim mb-1 flex items-center gap-1">
                            <Hash class="w-3 h-3" /> Numerología · total {{ reading.numerology.total }}
                        </div>
                        <p class="text-sm">{{ reading.numerology.meaning }}</p>
                    </div>
                </div>

                <div class="bg-panel border border-white/10 rounded-lg p-5">
                    <div
                        class="font-mono text-[10px] tracking-[0.16em] uppercase text-gold-dim mb-2 flex items-center gap-1">
                        <ScrollText class="w-3 h-3" /> Mensaje místico
                    </div>
                    <p class="font-mono text-amber-200 tracking-wide">{{ reading.mystic_message }}</p>
                </div>
            </div>

            <div class="text-center mt-10">
                <Link href="/oraculo"
                    class="inline-flex items-center gap-2 text-xs font-mono text-gold-dim hover:text-gold underline underline-offset-4">
                    <ArrowLeft class="w-3 h-3" /> Sacar tu propia tirada
                </Link>
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