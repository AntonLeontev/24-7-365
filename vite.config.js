import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/sass/app.scss",
                "resources/sass/landing.scss",
                "resources/js/app.js",
                "resources/js/landing.js",
                "resources/js/agree.js",
                "resources/js/calculator.js",
                "resources/js/userProfile.js",
                "resources/js/editContract.js",
                "resources/js/newContract.js",
                "resources/js/settings.js",
                "resources/js/statements.js",
                "resources/js/users.js",
                "resources/js/invoices.js",
                "resources/js/auth.js",
                "resources/js/adminContracts.js",
            ],
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
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
});
