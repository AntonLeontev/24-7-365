import { createApp } from "vue";
import EditContract from "./components/contracts/EditContract.vue";

const app = createApp({
    components: { EditContract },
});

app.mount("#app");
