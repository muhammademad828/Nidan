import '../css/app.css'
import { createApp, h } from 'vue'
import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js'
import { initVisualEditor } from './visual-editor.js'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'NIDAN'

function bootVisualEditor() {
    queueMicrotask(() => initVisualEditor())
}

createInertiaApp({
    title: (title) => `${title} — ${appName}`,
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)

        app.mount(el)
        bootVisualEditor()
        router.on('finish', bootVisualEditor)
        return app
    },
    progress: {
        color: '#c9a84c',
        showSpinner: false,
    },
})
