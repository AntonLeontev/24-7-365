import { createApp } from "vue";
import Invoices from "./components/admin/invoices/Invoices.vue";



const app = createApp({
    methods: {
        toast(options) {
            this.$refs.toasts.fire(options);
        },
    },
	components: { Invoices },
});

Object.entries(import.meta.glob("./components/common/*.vue", { eager: true })).forEach(
    ([path, definition]) => {
        app.component(
            path
                .split("/")
                .pop()
                .replace(/\.\w+$/, ""),
            definition.default
        );
    }
);

app.mount("#app");



