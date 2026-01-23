import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            refresh: true,
            server: {
                origin: process.env.APP_URL,
            },
            build: {
                manifest: true,
            },
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: process.env.APP_URL,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
});