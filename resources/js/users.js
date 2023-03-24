import { createApp } from "vue";
import Users from "./components/admin/users/Users.vue";



const app = createApp({
    methods: {
        toast(options) {
            this.$refs.toasts.fire(options);
        },
    },
	components: {Users},
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



