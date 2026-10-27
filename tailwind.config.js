import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                obsidian: 'var(--color-obsidian)',
                panel: 'var(--color-panel)',
                gold: {
                    DEFAULT: 'var(--color-gold)',
                    dim: 'var(--color-gold-dim)',
                },
                shadowpurple: 'var(--color-accent)',
                accent: 'var(--color-accent)',
            },
            fontFamily: {
                display: ['Cinzel', 'serif'],
                mono: ['"IBM Plex Mono"', 'monospace'],
            },
        },
    },
    plugins: [forms],
};
