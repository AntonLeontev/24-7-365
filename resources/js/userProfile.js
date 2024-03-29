import autoComplete from "@tarekraafat/autocomplete.js";
import Choices from "choices.js";
import axios from "axios";
import { createApp } from "vue";
import InputComponent from "./components/common/InputComponent.vue"
import Smscode from "./components/common/Smscode.vue";

createApp({
    data() {
        return {
            errors: {},
            notice: false,
            message: "",
			oldPhone: null,
			newPhone: null,
            spinner: false,
        };
    },
    methods: {
        async handleForm(event) {
            this.spinner = true;
			this.newPhone =
                this.convertPhone(document.querySelector('[name="phone"]').value);

            if (this.phoneChanged()) {
                try {
                    await this.validateInput();

                    if (
                        _.every(_.values(this.errors), (item) => item === null)
                    ) {
                        this.$options.smscodeInterface.confirmPhone();
                    }

                    this.spinner = false;
                    return;
                } catch (error) {
                    this.spinner = false;
                    return;
                }
            }

            await this.submit(event);
            this.spinner = false;
        },
        async submit(event) {
            let form = this.$refs.profileForm;

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
        phoneChanged() {
            return (
                this.convertPhone(this.newPhone) !== this.convertPhone(this.oldPhone)
            );
        },
		convertPhone(phone) {
			return "7" + phone.replace(/\D/g, "").slice(1);
		},
        async validateInput() {
            let form = this.$refs.profileForm;

            let formData = new FormData(form);

            await axios
                .request({
                    url: route("users.profile.validate"),
                    method: "POST",
                    data: formData,
                })
                .catch((response) => {
                    this.handleErrors(response);
                });
        },
		phoneIsConfirmed(phone) {
			this.newPhone = this.convertPhone(phone);
			this.oldPhone = this.convertPhone(phone);
			this.handleForm();
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
        clearError(name) {
            this.errors[name] = null;
        },
    },
    mounted: function () {
		this.oldPhone =
			this.$refs.profileForm.getAttribute("data-phone");


        const inn = new autoComplete({
            selector: "#inn",
            placeHolder: "ИНН",
            data: {
                src: async (query) => {
                    try {
                        // Fetch External Data Source
                        const data = await axios.post(
                            route("suggestions.company"),
                            {
                                inn: query,
                            }
                        );
                        // Returns Fetched data
                        let result = [];
                        data.data.suggestions.forEach((elem) => {
                            result.push({
                                inn: elem.data.inn,
                                title: elem.value.replaceAll('"', ""),
                                kpp: elem.data.kpp ?? "",
                                ogrn: elem.data.ogrn,
                                director: elem.data.management?.name ?? "",
                                directorsPost: elem.data.management?.post ?? "",
                                address: elem.data.address.value,
                            });
                        });
                        return result;
                    } catch (error) {
                        console.log(error);
                        return error;
                    }
                },
                keys: ["inn"],
            },
            resultsList: {
                tag: "ul",
                id: "autoComplete_list",
                class: "results-list",
                destination: "#inn",
                position: "afterend",
                maxResults: 20,
                noResults: true,
                element: (list, data) => {
                    if (!data.results.length) {
                        const message = document.createElement("div");
                        message.setAttribute("class", "no_result");
                        message.innerHTML = `<span>Нет результатов</span>`;
                        list.appendChild(message);
                        return;
                    }
                },
            },
            resultItem: {
                tag: "li",
                class: "results-item",
                element: (item, data) => {
                    item.innerText = data.value.title;
                },
                highlight: false,
                selected: "autoComplete_selected",
            },
            debounce: 100,
            threshold: 4,
        });

        inn.input.addEventListener("selection", function (event) {
            const feedback = event.detail;
            // Replace Input value with the selected value
            inn.input.value = feedback.selection.value[feedback.selection.key];
            let kpp = document.querySelector("#kpp");
            kpp.value = feedback.selection.value.kpp;
            kpp.dispatchEvent(new Event("input", { bubbles: true }));
            let title = document.querySelector("#title");
            title.value = feedback.selection.value.title;
            title.dispatchEvent(new Event("input", { bubbles: true }));
            document.querySelector("#ogrn").value =
                feedback.selection.value.ogrn;
            document.querySelector("#director").value =
                feedback.selection.value.director;
            document.querySelector("#directors_post").value =
                feedback.selection.value.directorsPost;
            document.querySelector("#address").value =
                feedback.selection.value.address;
        });

        const bik = new autoComplete({
            selector: "#bik",
            placeHolder: "БИК",
            data: {
                src: async (query) => {
                    try {
                        // Fetch External Data Source
                        const data = await axios.post(
                            route("suggestions.bank"),
                            {
                                bik: query,
                            }
                        );
                        // Returns Fetched data
                        let result = [];
                        data.data.suggestions.forEach((elem) => {
                            result.push({
                                bik: elem.data.bic,
                                title: elem.value,
                                correspondent_account:
                                    elem.data.correspondent_account,
                            });
                        });
                        return result;
                    } catch (error) {
                        console.log(error);
                        return error;
                    }
                },
                keys: ["bik"],
            },
            resultsList: {
                tag: "ul",
                id: "autoComplete_list",
                class: "results-list",
                destination: "#bik",
                position: "afterend",
                maxResults: 20,
                noResults: true,
                element: (list, data) => {
                    if (!data.results.length) {
                        // Create "No Results" message list element
                        const message = document.createElement("div");
                        message.setAttribute("class", "no_result");
                        // Add message text content
                        message.innerHTML = `<span>Нет результатов</span>`;
                        // Add message list element to the list
                        list.appendChild(message);
                        return;
                    }
                },
            },
            resultItem: {
                tag: "li",
                class: "results-item",
                element: (item, data) => {
                    item.innerText = data.value.title;
                },
                highlight: false,
                selected: "autoComplete_selected",
            },
            debounce: 100,
            threshold: 3,
        });

        bik.input.addEventListener("selection", function (event) {
            const feedback = event.detail;
            bik.input.value = feedback.selection.value[feedback.selection.key];
            let corAccount = document.querySelector("#correspondent_account");
            corAccount.value = feedback.selection.value.correspondent_account;
            corAccount.dispatchEvent(new Event("input", { bubbles: true }));

            let bank = document.querySelector("#bank");
            bank.value = feedback.selection.value.title;
            bank.dispatchEvent(new Event("input", { bubbles: true }));
        });
    },
    computed: {},
    components: { InputComponent, Smscode },
}).mount("#profile-form");



let selects = document.querySelectorAll(".form-select");
selects.forEach((select) => {
    new Choices(select, {
        searchEnabled: false,
        itemSelectText: "",
        shouldSort: false,
        allowHTML: true,
    });
});
