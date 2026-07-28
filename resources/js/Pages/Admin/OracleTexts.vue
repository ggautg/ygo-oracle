<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    races: Array,
    attributes: Array,
    numbers: Array,
    postures: Array,
});

const SECTIONS = [
    { key: 'races', title: 'Razas', field: 'essence', labelKey: 'race' },
    { key: 'attributes', title: 'Atributos', field: 'essence', labelKey: 'attribute' },
    { key: 'numbers', title: 'Numerología', field: 'meaning', labelKey: 'number' },
    { key: 'postures', title: 'Posturas', field: 'label', labelKey: 'posture' },
];

const savingId = ref(null);
const savedId = ref(null);

function save(type, row, field) {
    savingId.value = row.id;
    savedId.value = null;

    router.put(`/admin/oraculo-textos/${type}/${row.id}`, {
        [field]: row[field],
    }, {
        preserveScroll: true,
        onSuccess: () => {
            savedId.value = row.id;
            setTimeout(() => { savedId.value = null; }, 1500);
        },
        onFinish: () => {
            savingId.value = null;
        },
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto py-10 px-4">
            <h1 class="text-2xl font-bold mb-1">Textos del oráculo</h1>
            <p class="text-sm text-gray-500 mb-8">
                Editá los significados sin tocar la base de datos a mano. Los cambios se aplican al instante.
            </p>

            <div v-for="section in SECTIONS" :key="section.key" class="mb-10">
                <h2 class="text-lg font-semibold mb-3">{{ section.title }}</h2>

                <div class="space-y-2">
                    <div
                        v-for="row in props[section.key]"
                        :key="row.id"
                        class="flex items-start gap-3 bg-white border rounded-lg p-3"
                    >
                        <div class="w-28 shrink-0 text-xs font-mono text-gray-500 pt-2">
                            {{ row[section.labelKey] }}
                        </div>

                        <textarea
                            v-model="row[section.field]"
                            rows="2"
                            class="flex-1 text-sm border rounded px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-indigo-400"
                        />

                        <button
                            @click="save(section.key, row, section.field)"
                            :disabled="savingId === row.id"
                            class="shrink-0 text-xs px-3 py-1.5 rounded font-medium"
                            :class="savedId === row.id
                                ? 'bg-green-100 text-green-700'
                                : 'bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50'"
                        >
                            {{ savedId === row.id ? 'Guardado' : (savingId === row.id ? '...' : 'Guardar') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>