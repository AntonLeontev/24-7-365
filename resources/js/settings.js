import { createApp } from "vue";
import PaymentsStart from "./components/admin/settings/PaymentsStart.vue";

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({
    data() {
        return {
            errors: {},
            notice: false,
			message: '',
        };
    },
    methods: {
		toast(message){
			this.notify(message.message, 1500);
		},
        notify(message, delay = null) {
            this.message = message;
            this.notice = true;

            if (!_.isNull(delay)) {
                setTimeout(() => this.hideNotice(), delay);
            }
        },
        hideNotice() {
            this.notice = false;
        },
        async saveSettings(event) {
            let form = this.$refs.form;

            let formData = new FormData(form);

            await axios
                .request({
                    url: form.action,
                    method: form.method,
                    data: formData,
                })
                .then((response) => {
                    if (response.error) {
                        this.notify(response.message);
                        return;
                    }

                    this.notify("Сохранено", 1500);
                })
                .catch((response) => {
                    this.handleErrors(
                        response,
                        "Ошибка сохранения. Попробуйте позже"
                    );
                });
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
