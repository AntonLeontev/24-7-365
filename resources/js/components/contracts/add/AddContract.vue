<template>
  <contract-amount
    :amount-saved="amountSaved"
    @amountChanged="(amount) => (this.amount = amount)"
  ></contract-amount>
  <tariff-choise :tariffs="tariffs"> </tariff-choise>

  <div class="card">
    <div class="card-header">
      <span v-if="user.organization?.id">Подтвердите</span>
      <span v-else>Заполните</span>
      реквизиты юр. лица или ИП
    </div>
    <div class="card-body">
      <form class="row" action="" method="post" ref="profileForm">
        <input name="amount" type="hidden" :value="amount" />
        <input name="tariff_id" type="hidden" :value="tariffId" />
        <input name="ogrn" type="hidden" id="ogrn" />
        <input name="director" type="hidden" id="director" />
        <input name="directors_post" type="hidden" id="directors_post" />
        <input name="legal_address" type="hidden" id="address" />
        <div class="col-12 col-sm-6 col-md-4 mb-121">
          <input-component
            name="inn"
            placeholder="ИНН"
            id="inn"
            :error="this.errors.inn"
            @clearError="(name) => (errors[name] = null)"
          ></input-component>
        </div>
        <div class="col-12 col-sm-6 col-md-4 mb-121">
          <input-component
            name="kpp"
            placeholder="КПП"
            :value="user.organization?.kpp"
            id="kpp"
            tabindex="-1"
            :error="this.errors.kpp"
            @clearError="(name) => (errors[name] = null)"
            readonly
          ></input-component>
        </div>
        <div class="col-12 col-md-4 mb-121">
          <input-component
            name="title"
            placeholder="Наименование"
            :value="user.organization?.title"
            id="title"
            tabindex="-1"
            :error="this.errors.title"
            @clearError="(name) => (errors[name] = null)"
            readonly
          ></input-component>
        </div>
        <div class="col-12 col-sm-6 mb-121">
          <input-component
            name="payment_account"
            placeholder="Расчетный счет"
            :value="user.account?.payment_account"
            :error="this.errors.payment_account"
            @clearError="(name) => (errors[name] = null)"
          ></input-component>
        </div>
        <div class="col-12 col-sm-6 mb-121">
          <input-component
            name="bik"
            placeholder="БИК"
            :value="user.account?.bik"
            id="bik"
            :error="this.errors.bik"
            @clearError="(name) => (errors[name] = null)"
          ></input-component>
        </div>
        <div class="col-12 col-sm-6 mb-121">
          <input-component
            name="bank"
            placeholder="Банк"
            :value="user.account?.bank"
            tabindex="-1"
            id="bank"
            :error="this.errors.bank"
            @clearError="(name) => (errors[name] = null)"
            readonly
          ></input-component>
        </div>
        <div class="col-12 col-sm-6 mb-121">
          <input-component
            name="correspondent_account"
            placeholder="Корреспондентский счет"
            :value="user.account?.correspondent_account"
            id="correspondent_account"
            :error="this.errors.correspondent_account"
            @clearError="(name) => (errors[name] = null)"
            tabindex="-1"
            readonly
          ></input-component>
        </div>
        <div class="col-12">
          <button class="btn btn-primary w-100 mb-13" @click.prevent="submit">
            Сгенерировать счет
          </button>
          <p class="text-uppercase text-center">
            Убедитесь, что все поля заполнены правильно
          </p>
        </div>
      </form>
    </div>
  </div>
  <Transition>
    <div class="notice" v-cloak v-show="notice">
      <span v-text="message"></span>
      <button class="btn" type="button" aria-label="Закрыть" @click="hideNotice">
        <svg
          width="14"
          height="14"
          viewBox="0 0 14 14"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g clip-path="url(#clip0_223_3922)">
            <path
              d="M10.7503 12.0012L7 8.25091L3.24966 12.0012L1.99955 10.7511L5.74989 7.00079L1.99955 3.25045L3.24966 2.00034L7 5.75068L10.7503 2.00034L12.0005 3.25045L8.25011 7.00079L12.0005 10.7511L10.7503 12.0012Z"
              fill="#FCE301"
            />
          </g>
          <defs>
            <clipPath id="clip0_223_3922">
              <rect width="14" height="14" fill="white" />
            </clipPath>
          </defs>
        </svg>
      </button>
    </div>
  </Transition>

  <smscode
    :phone="this.newPhone ?? ''"
    :errors="errors"
    @interface="(smscodeInterface) => ($options.smscodeInterface = smscodeInterface)"
    @errors="(response) => handleErrors(response)"
    @notify="(message, delay) => notify(message, delay)"
    @numberConfirmed="(phoneNumber) => submitAfterPhoneSaved(phoneNumber)"
  ></smscode>

  <div
    class="modal fade"
    aria-labelledby="Введите код из сообщения"
    aria-hidden="true"
    tabindex="-1"
    id="phoneModal"
  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pe-3">Введите номер вашего телефона</h5>
          <button
            class="btn-close"
            data-bs-dismiss="modal"
            type="button"
            aria-label="Закрыть"
          ></button>
        </div>
        <div class="modal-body">
          <p>мы вышлем вам код для подтверждения договора и получения счета на оплату</p>
          <div class="mb-13">
            <input-component
              name="phone"
              placeholder="Введите номер телефона"
              :error="this.errors.phone"
              @clearError="(name) => (errors[name] = null)"
            ></input-component>
          </div>
          <button class="btn btn-primary w-100 mb-2" @click.prevent="confirmPhone">
            Отправить
          </button>
        </div>
      </div>
    </div>
  </div>

  <div
    class="modal fade"
    aria-labelledby="Введите код из сообщения"
    aria-hidden="true"
    tabindex="-1"
    id="paymentModal"
  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header position-relative d-flex flex-column">
          <img class="mb-13" src="../../../../images/payment.svg" alt="" />
          <h5 class="modal-title pe-3 align-self-start">
            Вы можете скачать платежное поручение
          </h5>
          <button
            class="btn-close position-absolute top-30 end-30"
            data-bs-dismiss="modal"
            type="button"
            aria-label="Закрыть"
          ></button>
        </div>
        <div class="modal-body">
          <p class="mb-13">или получить его на почту</p>
          <button class="btn btn-primary w-100 mb-121" @click.prevent="invoiceDownload">
            Скачать
          </button>
          <button
            class="btn btn-outline-primary w-100 mb-2"
            @click.prevent="invoiceToEmail"
          >
            Получить на почту
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TariffChoise from "./TariffChoise.vue";
import ContractAmount from "./ContractAmount.vue";
import InputComponent from "./InputComponent.vue";
import Smscode from "./Smscode.vue";
import autoComplete from "@tarekraafat/autocomplete.js";
import { nextTick } from "vue";

export default {
  name: "AddContract",
  created() {},
  data() {
    return {
      amount: this.amountSaved,
      tariffId: this.tariffIdSaved,
      errors: {},
      notice: false,
      message: "",
      smscode: null,
      phoneModal: null,
      paymentModal: null,
      phone: this.user.phone,
      newPhone: null,
      paymentId: null,
    };
  },
  props: {
    amountSaved: Number,
    tariffIdSaved: String,
    tariffs: Object,
    user: Object,
  },
  methods: {
    async submit() {
      let form = this.$refs.profileForm;
      let formData = new FormData(form);

      axios
        .post(route("organization.save"), formData)
        .then(async () => {
          if (!this.phone) {
            this.phoneModal.show();
            return;
          }

          await this.createContract();

          if (this.paymentId !== null) {
            this.paymentModal.show();
          }
        })
        .catch((response) => {
          this.handleErrors(response);
        });
    },
    submitAfterPhoneSaved(phone) {
      this.phone = phone;
      this.submit();
    },
    async createContract() {
      let formData = new FormData();

      formData.append("amount", this.amount);
      formData.append("tariff_id", this.tariffId);

      await axios
        .post(route("contracts.store"), formData)
        .then((response) => {
          this.paymentId = response.data.paymentId;
        })
        .catch((response) => this.handleErrors(response));
    },
    async confirmPhone() {
      try {
        this.preparePhone();
      } catch (e) {
        this.errors.phone = e;
        return;
      }

      this.newPhone = document.querySelector('[name="phone"]').value;

      this.phoneModal.hide();
      this.$options.smscodeInterface.modal.show();
      this.$options.smscodeInterface.askSmsCode();
    },
    invoiceDownload() {
      window.location.replace(route("invoice.pdf", this.paymentId));
    },
    invoiceToEmail() {
      alert("To do");
    },
    preparePhone() {
      let phone = document.querySelector('[name="phone"]');

      if (phone.value === "") throw "Обязательно";

      phone.value = "+7" + phone.value.replace(/\D/g, "").slice(1);

      if (phone.value.length !== 12) {
        throw "Должно быть 11 цифр";
      }
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
  },
  components: { ContractAmount, TariffChoise, InputComponent, Smscode },
  mounted() {
    this.phoneModal = new bootstrap.Modal("#phoneModal", { keyboard: false });
    this.paymentModal = new bootstrap.Modal("#paymentModal", { keyboard: false });

    document.querySelector("#address").value = this.user.organization?.legal_address;
    document.querySelector("#inn").value = this.user.organization?.inn ?? "";
    document.querySelector("#ogrn").value = this.user.organization?.ogrn;
    document.querySelector("#director").value = this.user.organization?.director;
    document.querySelector(
      "#directors_post"
    ).value = this.user.organization?.directors_post;

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
      document.querySelector("#kpp").value = feedback.selection.value.kpp;
      document.querySelector("#title").value = feedback.selection.value.title;
      document.querySelector("#ogrn").value = feedback.selection.value.ogrn;
      document.querySelector("#director").value = feedback.selection.value.director;
      document.querySelector("#directors_post").value =
        feedback.selection.value.directorsPost;
      document.querySelector("#address").value = feedback.selection.value.address;
    });

    const bik = new autoComplete({
      selector: "[name='bik']",
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
  },
};
</script>

<style lang="scss" scoped></style>
