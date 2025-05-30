import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/tourist-dashboard.css', 'resources/js/tourist-dashboard.js', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
