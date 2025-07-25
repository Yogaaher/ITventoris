import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/dashboard.css',
                'resources/js/dashboard.js',
                'resources/css/perusahaan.css',
                'resources/js/perusahaan.js',
                'resources/css/serah_terima.css',
                'resources/js/serah_terima.js',
                'resources/css/usermanage.css',
                'resources/js/usermanage.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
