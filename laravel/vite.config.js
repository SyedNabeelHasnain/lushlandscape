import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/js/app.js',
                'resources/js/admin.js',
            ],
            publicDirectory: '../public_html',
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        emptyOutDir: true,
        chunkSizeWarningLimit: 650,
        rolldownOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules/lucide')) return 'vendor-lucide';
                    if (id.includes('node_modules/swiper')) return 'vendor-swiper';
                    if (id.includes('node_modules/alpinejs') || id.includes('node_modules/@alpinejs')) return 'vendor-alpine';
                    if (id.includes('node_modules/@tiptap') || id.includes('node_modules/prosemirror')) return 'vendor-tiptap';
                    if (id.includes('node_modules/apexcharts')) return 'vendor-apexcharts';
                },
            },
        },
    },
});
