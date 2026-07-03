import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import path from 'node:path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app/main.tsx'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        react(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@app': path.resolve(__dirname, 'resources/js/app'),
            '@modules': path.resolve(__dirname, 'resources/js/modules'),
            '@shared': path.resolve(__dirname, 'resources/js/shared'),
            '@components': path.resolve(__dirname, 'resources/js/components'),
            '@layouts': path.resolve(__dirname, 'resources/js/layouts'),
            '@providers': path.resolve(__dirname, 'resources/js/providers'),
            '@hooks': path.resolve(__dirname, 'resources/js/hooks'),
            '@services': path.resolve(__dirname, 'resources/js/services'),
            '@stores': path.resolve(__dirname, 'resources/js/stores'),
            '@themes': path.resolve(__dirname, 'resources/js/themes'),
            '@locales': path.resolve(__dirname, 'resources/js/locales'),
            '@routes': path.resolve(__dirname, 'resources/js/routes'),
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
