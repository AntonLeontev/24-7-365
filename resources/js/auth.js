import { createApp } from "vue";
import Auth from './components/auth/Auth.vue'

const app = createApp({
    components: { Auth },
})
    .mount("#auth");
