<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { Sparkles, Zap, Settings } from 'lucide-vue-next';

const page = usePage();
const user = page.props.auth?.user ?? null;
</script>

<template>
    <nav class="flex items-center justify-center gap-6 mb-10 font-mono text-[11px] uppercase tracking-wider">
        <Link
            href="/oraculo"
            class="flex items-center gap-1.5 text-stone-400 hover:text-gold transition-colors"
            :class="{ 'text-gold': page.url === '/oraculo' }"
        >
            <Sparkles class="w-3.5 h-3.5" /> Tirada
        </Link>

        <Link
            href="/oraculo/si-no"
            class="flex items-center gap-1.5 text-stone-400 hover:text-gold transition-colors"
            :class="{ 'text-gold': page.url === '/oraculo/si-no' }"
        >
            <Zap class="w-3.5 h-3.5" /> Sí / No
        </Link>

        <Link
            v-if="user"
            href="/admin/oraculo-textos"
            class="flex items-center gap-1.5 text-stone-400 hover:text-gold transition-colors"
            :class="{ 'text-gold': page.url === '/admin/oraculo-textos' }"
        >
            <Settings class="w-3.5 h-3.5" /> Admin
        </Link>
    </nav>
</template>