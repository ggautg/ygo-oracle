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
                obsidian: '#0b0e1a',
                panel: '#12162c',
                gold: {
                    DEFAULT: '#c9a227',
                    dim: '#8a7327',
                },
                shadowpurple: '#5b3e96',
            },
            fontFamily: {
                display: ['Cinzel', 'serif'],
                mono: ['"IBM Plex Mono"', 'monospace'],
            },
        },
    },
    plugins: [forms],
};
