<template>
  <div class="card">
    <div class="card-body pb-0">
      <div
        class="d-flex flex-column gap-111 px-111 py-121 py-md-13 border-top border-primary border-2 bg-dark"
      >
        <p class="text-center mb-0 fs-8 fs-md-7 fs-lg-6">
          Вы можете увеличить увеличить сумму действущего договора или перейти на тариф с
          большей доходностью.
        </p>
        <p class="text-center mb-0 fs-8 fs-md-7 fs-lg-6">
          Уменьшить срок действия текущего тарифа или перейти на младший нельзя.
        </p>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">Увеличить сумму закупа</div>
    <div class="card-body pt-0 d-flex flex-column flex-md-row gap-13">
      <p class="w-md-50 d-flex align-items-center text-light fs-8 fs-md-6 mb-0">
        Введите на сколько хотите увеличить сумму закупа
      </p>
      <div class="form-input w-md-50">
        <input
          type="text"
          class="form-control"
          name="addedAmount"
          placeholder="Введите сумму"
          autocorrect="off"
          autocomplete="off"
          autocapitalize="off"
          @input="input"
          :class="{ 'border-primary': errors.addedAmount }"
        />
        <label class="form-label" :class="{ 'text-primary': errors.addedAmount }">
          {{ errors.addedAmount ?? "Увеличение суммы закупа" }}
        </label>
      </div>
    </div>
  </div>

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
                :amount="newAmount"
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
    <div class="card-body d-flex">
      <button class="btn btn-primary mx-auto w-100 w-lg-50" @click="updateContract">
        Изменить договор
      </button>
    </div>
  </div>

  <Transition>
    <div class="notice" v-cloak v-show="notice">
      <span v-text="message"></span>
      <button type="button" class="btn" aria-label="Закрыть" @click="hideNotice">
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
</template>

<script>
import TariffGroup from "../calculator/TariffGroup.vue";

export default {
  name: "EditContract",
  created() {},
  data() {
    return {
      addAmount: null,
      notice: false,
      errors: {},
      message: "",
      tariffId: this.contract.tariff.id,
    };
  },
  props: {
    tariffs: Object,
    contract: Object,
  },
  methods: {
    input(event) {
      this.errors.addedAmount = null;
      this.formatNumber(event);
    },
    formatNumber(event) {
      this.addAmount = +event.target.value.replace(/\D/g, "");

      event.target.value = new Intl.NumberFormat("ru").format(this.addAmount);
    },
    updateContract() {
      axios
        .request({
          url: route("contracts.update", this.contract.id),
          method: "POST",
          data: { addedAmount: this.addAmount },
        })
        .then((response) => {
          if (response.data.ok) {
            window.location.replace(route("users.contract_show", this.contract.id));
          }
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
    handleTariffSelection(tariffId) {
      this.tariffId = tariffId;
    },
    isEnabled(tariff) {
      if (tariff.max_amount.raw === 0) {
        return this.newAmount >= tariff.min_amount.amount;
      }

      return (
        this.newAmount >= tariff.min_amount.amount &&
        this.newAmount < tariff.max_amount.amount
      );
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
  computed: {
    newAmount() {
      return this.contract.amount.amount + this.addAmount;
    },
  },
  components: { TariffGroup },
};
</script>

<style lang="scss" scoped>
.form-input::after {
  content: "₽";
  position: absolute;
  right: 0;
  bottom: 10px;
  color: #737373;
}
</style>
