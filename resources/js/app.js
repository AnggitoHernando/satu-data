import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

const appName = import.meta.env.VITE_APP_NAME || "Pusat Data";

import "toastify-js/src/toastify.css";
import Toastify from "toastify-js";
import Swal from "sweetalert2";

// opsional: jadikan global biar bisa dipakai di mana pun
window.Toastify = Toastify;
window.Swal = Swal;

window.toast = (text, type = "info") => {
    const colors = {
        success: "#4CAF50",
        error: "#f44336",
        info: "#2196F3",
        warning: "#FFC107",
    };

    Toastify({
        text,
        duration: 3000,
        gravity: "top",
        position: "right",
        close: true,
        style: {
            background: colors[type] || colors.info,
        },
    }).showToast();
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
