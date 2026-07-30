<script setup>
import { Head } from '@inertiajs/vue3';
import OraculoNav from '@/Components/OraculoNav.vue';

defineProps({
    races: Array,
    attributes: Array,
    postures: Array,
    numbers: Array,
});
</script>

<template>
    <Head title="Grimorio" />

    <div class="min-h-screen bg-obsidian text-stone-200 px-4 py-12">
        <div class="max-w-2xl mx-auto">
            <p class="text-center font-mono text-[11px] tracking-[0.2em] uppercase text-gold-dim mb-2">
                Consulta libre
            </p>
            <h1 class="text-center font-display font-bold text-4xl bg-gradient-to-b from-amber-200 to-gold bg-clip-text text-transparent mb-3">
                Grimorio
            </h1>
            <p class="text-center text-stone-400 mb-10 text-sm">
                Todos los significados que usa el oráculo, sin tener que sacar cartas para verlos.
            </p>

            <OraculoNav />

            <section class="mb-10">
                <h2 class="font-display text-lg text-gold mb-3">Razas</h2>
                <div class="space-y-2">
                    <div v-for="r in races" :key="r.id" class="bg-panel border border-white/10 rounded p-3 flex gap-4">
                        <span class="font-mono text-xs text-gold-dim w-28 shrink-0">{{ r.race }}</span>
                        <span class="text-sm text-stone-300">{{ r.essence }}</span>
                    </div>
                </div>
            </section>

            <section class="mb-10">
                <h2 class="font-display text-lg text-gold mb-3">Atributos</h2>
                <div class="space-y-2">
                    <div v-for="a in attributes" :key="a.id" class="bg-panel border border-white/10 rounded p-3 flex gap-4">
                        <span class="font-mono text-xs text-gold-dim w-28 shrink-0">{{ a.attribute }}</span>
                        <span class="text-sm text-stone-300">{{ a.essence }}</span>
                    </div>
                </div>
            </section>

            <section class="mb-10">
                <h2 class="font-display text-lg text-gold mb-3">Posturas</h2>
                <div class="space-y-2">
                    <div v-for="p in postures" :key="p.id" class="bg-panel border border-white/10 rounded p-3 flex gap-4">
    <span class="font-mono text-xs text-gold-dim w-28 shrink-0">{{ p.label }}</span>
    <span class="text-sm text-stone-300">{{ p.description }}</span>
</div>
                </div>
            </section>

            <section>
                <h2 class="font-display text-lg text-gold mb-3">Numerología</h2>
                <div class="space-y-2">
                    <div v-for="n in numbers" :key="n.id" class="bg-panel border border-white/10 rounded p-3 flex gap-4">
                        <span class="font-mono text-xs text-gold-dim w-10 shrink-0">{{ n.number }}</span>
                        <span class="text-sm text-stone-300">{{ n.meaning }}</span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>