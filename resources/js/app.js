import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        const pagePath = `./Pages/${name}.vue`

        if (!pages[pagePath]) {
            throw new Error(
                `Page component not found: ${name}\n` +
                `Expected path: ${pagePath}\n` +
                `Available pages: ${Object.keys(pages).join(', ')}`
            )
        }

        return pages[pagePath]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})