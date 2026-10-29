import { readFileSync } from 'node:fs';
import { resolve } from 'node:path';
import { defineConfig } from 'vite';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import tailwindcss from '@tailwindcss/vite';

// Shared <head> markup, injected into every page in place of <!-- head -->.
const sharedHead = () => ({
    name: 'shared-head',
    transformIndexHtml: (html) =>
        html.replace('<!-- head -->', readFileSync(resolve(import.meta.dirname, 'src/head.html'), 'utf8')),
});

export default defineConfig({
    plugins: [sharedHead(), svelte(), tailwindcss()],
    server: {
        allowedHosts: true,
    },
    build: {
        rollupOptions: {
            input: {
                index: resolve(import.meta.dirname, 'index.html'),
                about: resolve(import.meta.dirname, 'about.html'),
                notFound: resolve(import.meta.dirname, '404.html'),
            },
        },
    },
});
