import { createApp } from "vue";

createApp({
    data() {
        return {
            errors: {},
            notice: false,
            message: "",
            tab: null,
        };
    },
    mounted() {
        this.getTab();
    },
    methods: {
        getTab() {
            this.tab = this.$refs.root.dataset.tab;
        },
        handleForm(event) {
            let form = event.target.closest("form");
            let data = new FormData(form);

            axios.post(form.action, data).then((response) => {
                window.location.replace('/');
            }).catch((response) => this.handleErrors(response));
        },
        handleErrors(response, message = null) {
            if (response.response?.data?.exception) {
                this.notify(message ?? response.response?.data?.message);
                return;
            }

            if (response.response?.data?.message) {
                const errors = response.response.data.errors;
                for (const key in errors) {
                    this.errors[key] = errors[key][0];
                }
                return;
            }
        },
    },
    props: {},
    watch: {
        tab() {
            setTimeout(() => {
                let tab = this.$refs.tabs.querySelector(".auth-tab_active");
                let left =
                    tab.getBoundingClientRect().left -
                    this.$refs.tabs.getBoundingClientRect().left;

                this.$refs.tabMarker.style.left = left + "px";
                this.$refs.tabMarker.style.width =
                    tab.getBoundingClientRect().width + "px";
            }, 50);
        },
    },
    components: {},
}).mount("#app");
