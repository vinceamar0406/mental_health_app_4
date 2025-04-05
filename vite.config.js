import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    optimizeDeps: {
        exclude: ['ziggy'],  // Exclude Ziggy to prevent server-side dependencies from being bundled
    },
    build: {
        rollupOptions: {
            external: ['ziggy-js'],  // Externalize Ziggy to prevent bundling
        },
    },
});
