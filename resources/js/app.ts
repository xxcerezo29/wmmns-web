import "./bootstrap";
import "../css/app.css";

import { createSSRApp, DefineComponent, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/src/js";
import Toast, { PluginOptions } from "vue-toastification";
import 'vue-toastification/dist/index.css';

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createSSRApp({ render: () => h(App, props) });

        const options = {
            toastClassName: "bg-white rounded-lg shadow-lg p-4",
            bodyClassName: "text-gray-700",
            closeButtonClassName: "text-gray-500 hover:text-gray-800",
            iconClassName: "text-green-500", // Modify based on toast type
            transition: "fade",
            position: "top-right",
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
        };
        app.use(plugin).use(ZiggyVue).use(Toast, options).mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
