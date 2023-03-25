import autoComplete from "@tarekraafat/autocomplete.js";
import Choices from "choices.js";
import axios from "axios";
import { createApp } from "vue";

createApp({
    data() {
        return {
            errors: {},
            notice: false,
            message: "",
            userData: {},
            smsCodeModal: null,
			spinner: false,

			// TODO delete
			smscode: null,
        };
    },
    methods: {
		async handleForm(event) {
			this.spinner = true;

			this.preparePhone();
            if (this.phoneChanged()) {
				try {
					await this.validateInput();

					if(_.every(_.values(this.errors), (item) => item === null)) {
						await this.askSmsCode();
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
		async submit(event){
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

					this.notify('Сохранено', 1500);
                })
                .catch((response) => {
					this.handleErrors(response, 'Ошибка сохранения. Попробуйте позже');
                });
		},
		notify (message, delay = null) {
			this.message = message;
			this.notice = true;

			if (! _.isNull(delay)) {
				setTimeout(() => this.hideNotice(), delay)
			}
		},
		hideNotice () {
			this.notice = false;
		},
		preparePhone () {
			let phone = document.querySelector('[name="phone"]');

			if (phone.value === '') return;

			phone.value = '+7' + phone.value.replace(/\D/g, '').slice(1);

			if (phone.value.length !== 12) {
				this.errors.phone = 'Должно быть 11 цифр'
            }
		},
		phoneChanged () {
			return document.querySelector('[name="phone"]').value.slice(1) !== this.userData.phone;
		},
		async askSmsCode () {
			let form = this.$refs.profileForm;

            let formData = new FormData(form);

            await axios
                .request({
                    url: route("smscode.create", "phone_confirmation"),
                    method: "POST",
                    data: formData,
                })
                .then((response) => {
                    this.smscode = response.data.code;
                    this.smsCodeModal.show();
                })
                .catch((response) => {
                    this.handleErrors(response);
                });
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
		async checkCode() {
			const form = this.$refs.checkCodeForm;

			let formData = new FormData(form);

            if (!_.isObjectLike(formData)) {
                return;
            }

            await axios
                .request({
                    url: route("smscode.check", "phone_confirmation"),
                    method: "POST",
                    data: formData,
                })
                .then((response) => {
                    if (!response.data.ok) {
                        this.errors.code = response.data.message;
                        return;
                    }

                    this.smsCodeModal.hide();
                    this.userData.phone =
                        document.querySelector('[name="phone"]').value.slice(1);
                    document.querySelector("#profile-save-button").click();
                })
                .catch((response) => {
                    this.handleErrors(response);
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
		}
	},
	mounted: function(){
		this.userData['phone'] = this.$refs.profileForm.getAttribute("data-phone");

		this.smsCodeModal = new bootstrap.Modal("#smscode", { keyboard: false });
	},
	computed: {},
}).mount("#profile-form");

const inn = new autoComplete({
    selector: "#inn",
    placeHolder: "ИНН",
    data: {
        src: async (query) => {
            try {
                // Fetch External Data Source
                const data = await axios.post(route("suggestions.company"), {
                    inn: query,
                });
                // Returns Fetched data
                let result = [];
                data.data.suggestions.forEach((elem) => {
                    result.push({
                        inn: elem.data.inn,
                        title: elem.value.replaceAll('"', ''),
                        kpp: elem.data.kpp ?? '',
                        ogrn: elem.data.ogrn,
                        director: elem.data.management?.name ?? '',
                        directorsPost: elem.data.management?.post ?? '',
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
	document.querySelector("#kpp").value = feedback.selection.value.kpp;
	document.querySelector("#title").value = feedback.selection.value.title;
	document.querySelector("#ogrn").value = feedback.selection.value.ogrn;
	document.querySelector("#director").value = feedback.selection.value.director;
	document.querySelector("#directors_post").value = feedback.selection.value.directorsPost;
	document.querySelector("#address").value = feedback.selection.value.address;
});

const bik = new autoComplete({
    selector: "#bik",
    placeHolder: "БИК",
    data: {
        src: async (query) => {
            try {
                // Fetch External Data Source
                const data = await axios.post(route("suggestions.bank"), {
                    bik: query,
                });
                // Returns Fetched data
                let result = [];
                data.data.suggestions.forEach((elem) => {
                    result.push({
                        bik: elem.data.bic,
                        title: elem.value,
                        correspondent_account: elem.data.correspondent_account,
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
    document.querySelector("#correspondent_account").value =
        feedback.selection.value.correspondent_account;
    document.querySelector("#bank").value = feedback.selection.value.title;
});

let selects = document.querySelectorAll(".form-select");
selects.forEach((select) => {
    new Choices(select, {
        searchEnabled: false,
        itemSelectText: "",
        shouldSort: false,
        allowHTML: true,
    });
});
