import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                display: ["Fraunces", "Georgia", "Times New Roman", "serif"],
                sans: [
                    "IBM Plex Sans",
                    "-apple-system",
                    "BlinkMacSystemFont",
                    "Segoe UI",
                    "sans-serif",
                    "Figtree",
                    ...defaultTheme.fontFamily.sans,
                ],
            },
            colors: {
                primary: "rgb(var(--color-primary) / <alpha-value>)",
                secondary: "rgb(var(--color-secondary) / <alpha-value>)",
                ink: "#12130F",
                "ink-soft": "#4A4A42",
                paper: "#FBFAF6",
                "paper-dim": "#F1EFE7",
                panel: "#10182B",
                brass: "#B9803A",
                "brass-soft": "#E7C8A0",
                border: "#E2DFD2",
                error: "#A3402F",
            },
        },
    },

    plugins: [forms],
};
