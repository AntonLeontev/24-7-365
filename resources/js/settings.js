import { createApp } from "vue";
import PaymentsStart from "./components/admin/settings/PaymentsStart.vue";

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({
    methods: {
        toast(options) {
            this.$refs.toasts.fire(options);
        },
    },
    components: { PaymentsStart },
});

Object.entries(
    import.meta.glob("./components/common/*.vue", { eager: true })
).forEach(([path, definition]) => {
    app.component(
        path
            .split("/")
            .pop()
            .replace(/\.\w+$/, ""),
        definition.default
    );
});

app.mount("#app");
