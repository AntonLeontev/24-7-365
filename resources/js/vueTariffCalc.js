import { createApp } from "vue";
import AddContract from "./components/contracts/add/AddContract.vue";

createApp({
    data() {
        return {
            amount: null,
            tariffId: null,
            errors: {},
            notice: false,
            message: "",
        };
    },
    methods: {
		
    },
    components: { AddContract },
}).mount("#app");


