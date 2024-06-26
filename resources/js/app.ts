import './bootstrap';
import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import * as Sentry from "@sentry/vue";
import VMdEditor from '@kangc/v-md-editor';
import '@kangc/v-md-editor/lib/style/base-editor.css';
import githubTheme from '@kangc/v-md-editor/lib/theme/github.js';
import '@kangc/v-md-editor/lib/theme/style/github.css';
import enUS from '@kangc/v-md-editor/lib/lang/en-US';

// highlightjs
import hljs from 'highlight.js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)

            VMdEditor.use(githubTheme,{
                Hljs: hljs,
            }).lang.use('en-US', enUS);

            vueApp.use(VMdEditor);

            Sentry.init({
                dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
            });
            vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
