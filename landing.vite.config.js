import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/landing/css/app.css',
                'resources/landing/js/app.js'
            ],
            buildDirectory: 'build-landing',
            refresh: true,
        }),
    ],
    css: {
        postcss: {
            plugins: [
                require("tailwindcss/nesting"),
                require("tailwindcss")({
                    config: "./landing.tailwind.config.js",
                }),
                require("autoprefixer"),
            ],
        },
    },
});
