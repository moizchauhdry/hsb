import './bootstrap';
import '../css/app.css';

// Bootstrap CSS
import '../css/bootstrap.min.css';
import '../css/bootstrap-extended.css';
import '../css/main.css'; // app.css
import '../css/icons.css';

// Theme Style CSS
import '../css/dark-theme.css';
import '../css/semi-dark.css';
import '../css/header-colors.css';

import '../plugins/bs-stepper/css/bs-stepper.css';



import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
