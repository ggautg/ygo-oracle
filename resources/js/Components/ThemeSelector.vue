<script setup>
import { ref, onMounted } from 'vue';
import { Palette } from 'lucide-vue-next';

const themes = [
    { id: 'default', name: 'Por defecto', color: '#c9a227' },
    { id: 'yugi', name: 'Yugi Muto', color: '#a855f7' },
    { id: 'atem', name: 'Faraón Atem', color: '#f59e0b' },
    { id: 'kaiba', name: 'Seto Kaiba', color: '#3b82f6' },
    { id: 'joey', name: 'Joey Wheeler', color: '#ef4444' },
    { id: 'jaden', name: 'Jaden Yuki', color: '#22c55e' },
    { id: 'yusei', name: 'Yusei Fudo', color: '#06b6d4' },
    { id: 'yuma', name: 'Yuma Tsukumo', color: '#ec4899' },
    { id: 'yuya', name: 'Yuya Sakaki', color: '#a855f7' },
    { id: 'yusaku', name: 'Yusaku Fujiki', color: '#2563eb' },
    { id: 'yuga', name: 'Yuga Ohdo', color: '#84cc16' },
];

const current = ref('default');
const open = ref(false);

onMounted(() => {
    current.value = localStorage.getItem('ygo-theme') || 'default';
    if (current.value !== 'default') {
        document.documentElement.setAttribute('data-theme', current.value);
    }
});

function setTheme(id) {
    current.value = id;
    if (id === 'default') {
        localStorage.removeItem('ygo-theme');
        document.documentElement.removeAttribute('data-theme');
    } else {
        localStorage.setItem('ygo-theme', id);
        document.documentElement.setAttribute('data-theme', id);
    }
    open.value = false;
}
</script>

<template>
    <div class="fixed bottom-4 right-4 z-50">
        <button
            @click="open = !open"
            class="flex items-center gap-2 bg-panel border border-white/10 rounded-full px-4 py-2 text-xs font-mono text-stone-300 shadow-lg hover:border-gold/40 transition-colors"
        >
            <Palette class="w-3.5 h-3.5" />
            Tema por personaje
        </button>

        <div
            v-if="open"
            class="absolute bottom-12 right-0 w-64 bg-panel border border-white/10 rounded-lg shadow-xl overflow-hidden"
        >
            <div class="p-3 border-b border-white/10">
                <p class="text-xs font-mono text-stone-400">Colores por personaje</p>
            </div>
            <div class="max-h-80 overflow-y-auto">
                <button
                    v-for="theme in themes"
                    :key="theme.id"
                    @click="setTheme(theme.id)"
                    class="w-full flex items-center gap-3 px-3 py-2 hover:bg-white/5 transition-colors text-left"
                    :class="{ 'bg-white/5': current === theme.id }"
                >
                    <span class="w-4 h-4 rounded-full border border-white/20" :style="{ background: theme.color }"></span>
                    <span class="text-sm text-stone-300">{{ theme.name }}</span>
                </button>
            </div>
        </div>
    </div>
</template>
