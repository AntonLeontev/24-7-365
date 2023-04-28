<template>
  <contract-amount
    :amount-saved="amountSaved"
    @amountChanged="(amount) => amountChanged(amount)"
  ></contract-amount>

  <div class="card">
    <div class="card-header">Выберите тариф</div>
    <div class="card-body pe-0 pe-xl-13">
      <div class="tariffs-wrap pb-2 pe-11 pe-sm-121 pe-md-13">
        <template v-for="(group, groupName) in tariffs">
          <Transition :duration="{ enter: 800, leave: 0 }">
            <div class="tariff" v-show="isEnabled(group[0])">
              <tariff-group
                :title="groupName"
                :tariffs="group"
                :amount="amount"
                :style="'contract'"
                :selectedTariffId="tariffId"
                @tariffSelected="
                  (selectedTariffId) => handleTariffSelection(selectedTariffId)
                "
              />
            </div>
          </Transition>
        </template>
      </div>
    </div>
  </div>

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
            :value="user.organization?.inn"
            id="inn"
            :error="this.errors.inn"
            @clear-error="(name) => (errors[name] = null)"
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
            @clear-error="(name) => (errors[name] = null)"
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
            @clear-error="(name) => (errors[name] = null)"
            readonly
          ></input-component>
        </div>
        <div class="col-12 col-sm-6 mb-121">
          <input-component
            name="payment_account"
            placeholder="Расчетный счет"
            :value="user.account?.payment_account"
            :error="this.errors.payment_account"
            @clear-error="(name) => (errors[name] = null)"
          ></input-component>
        </div>
        <div class="col-12 col-sm-6 mb-121">
          <input-component
            name="bik"
            placeholder="БИК"
            :value="user.account?.bik"
            id="bik"
            :error="this.errors.bik"
            @clear-error="(name) => (errors[name] = null)"
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
            @clear-error="(name) => (errors[name] = null)"
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
            @clear-error="(name) => (errors[name] = null)"
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
    :phone="this.phone ?? ''"
    :errors="errors"
    @interface="(smscodeInterface) => ($options.smscodeInterface = smscodeInterface)"
    @errors="(response) => handleErrors(response)"
    @notify="(message, delay) => notify(message, delay)"
    @phone-is-confirmed="(phone) => phoneIsConfirmed(phone)"
    @contract-is-confirmed="resubmit"
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
              @clear-error="(name) => (errors[name] = null)"
            ></input-component>
          </div>
          <button class="btn btn-primary w-100 mb-2" @click.prevent="confirmPhone">
            Отправить
          </button>
        </div>
      </div>
    </div>
  </div>

  <modal-invoice
    :paymentId="paymentId"
    @interface="(modal) => (paymentModal = modal)"
    @toast="(message) => notify(message, 3000)"
  ></modal-invoice>
</template>

<script>
import ContractAmount from "./ContractAmount.vue";
import InputComponent from "../../common/InputComponent.vue";
import Smscode from "../../common/Smscode.vue";
import autoComplete from "@tarekraafat/autocomplete.js";
import TariffGroup from "../../calculator/TariffGroup.vue";
import ModalInvoice from "../../common/ModalInvoice.vue";

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
      paymentId: null,
      actionIsConfirmed: false,
    };
  },
  props: {
    amountSaved: Number,
    tariffIdSaved: { default: 0 },
    tariffs: Object,
    user: Object,
  },
  methods: {
    async submit() {
      if (this.amount < 500000) {
        this.notify("Минимальная сумма 500 000 руб");
        return;
      }

      if (this.tariffId === "" || _.isNil(this.tariffId)) {
        this.notify("Не выбран тариф");
        return;
      }

      let form = this.$refs.profileForm;
      let formData = new FormData(form);

      axios
        .post(route("organization.save"), formData)
        .then(async () => {
          if (!this.phone) {
            this.phoneModal.show();
            return;
          }

          if (!this.actionIsConfirmed) {
            this.$options.smscodeInterface.confirmContractCreation();
            return;
          }

          await this.createContract();

          this.actionIsConfirmed = false;

          if (this.paymentId !== null) {
            this.paymentModal.show();
          }
        })
        .catch((response) => {
          this.handleErrors(response);
        });
    },
    phoneIsConfirmed(phone) {
      this.phone = phone;
      this.savePhoneNumber();
      this.resubmit();
    },
    resubmit() {
      this.actionIsConfirmed = true;
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
        await this.preparePhone();
      } catch (e) {
        this.errors.phone = e;
        return;
      }

      this.phone = document.querySelector('[name="phone"]').value;

      this.phoneModal.hide();
      this.$options.smscodeInterface.confirmPhone();
    },
    async preparePhone() {
      let phone = document.querySelector('[name="phone"]');

      if (phone.value === "") throw "Обязательно";

      let value = "+7" + phone.value.replace(/\D/g, "").slice(1);

      if (value.length !== 12) {
        throw "Должно быть 11 цифр";
      }

      await axios
        .post(route("users.validatePhone"), { phone: value })
        .catch((response) => {
          throw response.response?.data?.message ?? "Ошибка номера телефона";
        });
    },
    async savePhoneNumber() {
      let formData = new FormData();
      formData.append("phone", this.phone);

      axios
        .post(route("users.updatePhone"), formData)
        .then((response) => {
          this.notify("Номер сохранен", 1500);
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
    },
    isEnabled(tariff) {
      if (tariff.max_amount.raw === 0) {
        return this.amount >= tariff.min_amount.amount;
      }

      return (
        this.amount >= tariff.min_amount.amount && this.amount < tariff.max_amount.amount
      );
    },
    handleTariffSelection(tariffId) {
      this.tariffId = tariffId;
    },
    amountChanged(amount) {
      this.amount = amount;
      this.tariffId = null;
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
  components: { ContractAmount, InputComponent, Smscode, TariffGroup, ModalInvoice },
  mounted() {
    this.phoneModal = new bootstrap.Modal("#phoneModal", { keyboard: false });

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
      let kpp = document.querySelector("#kpp");
      kpp.value = feedback.selection.value.kpp;
      kpp.dispatchEvent(new Event("input", { bubbles: true }));
      let title = document.querySelector("#title");
      title.value = feedback.selection.value.title;
      title.dispatchEvent(new Event("input", { bubbles: true }));
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
      let corAccount = document.querySelector("#correspondent_account");
      corAccount.value = feedback.selection.value.correspondent_account;
      corAccount.dispatchEvent(new Event("input", { bubbles: true }));

      let bank = document.querySelector("#bank");
      bank.value = feedback.selection.value.title;
      bank.dispatchEvent(new Event("input", { bubbles: true }));
    });
  },
};
</script>

<style lang="scss" scoped></style>
