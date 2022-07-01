import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel([
            'resources/sass/app.scss',
            'resources/js/app.js',
        ]),
        react(),
    ],
});
