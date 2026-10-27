import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const applySavedTheme = () => {
    if (typeof window !== 'undefined') {
        const saved = localStorage.getItem('ygo-theme');
        if (saved && saved !== 'default') {
            document.documentElement.setAttribute('data-theme', saved);
        } else {
            document.documentElement.removeAttribute('data-theme');
        }
    }
};

applySavedTheme();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
   title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
