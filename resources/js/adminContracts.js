import { createApp } from "vue";
import Contracts from "./components/admin/contracts/ContractsIndex.vue";

const app = createApp({
    methods: {
        toast(options) {
            this.$refs.toasts.fire(options);
        },
    },
    components: { Contracts },
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
