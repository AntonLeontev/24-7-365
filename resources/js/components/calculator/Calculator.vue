<template>
  <div class="row">
    <div class="col">
      <div class="card border-top border-primary border-2 mb-13">
        <div class="card-header pb-0">Калькулятор</div>
        <div class="card-body pt-12">
          <div class="row">
            <p class="text-light col-12 col-lg-6 fs-8 fs-md-6">
              Введите желаемую сумму закупа и мы покажем ожидаемую прибыль по всем тарифам
            </p>
            <div class="form-input col-12 col-lg-6">
              <input
                type="text"
                class="form-control"
                placeholder="Сумма закупа"
                :value="amountFormatted"
                autocorrect="off"
                autocomplete="off"
                autocapitalize="off"
                @input="handleAmount"
                ref="amount"
              />
              <label class="form-label" v-show="!amountError">Введите сумму закупа</label>
              <label class="form-label text-primary" v-show="amountError">{{
                amountError
              }}</label>
              <svg
                width="13"
                height="14"
                viewBox="0 0 13 14"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="position-absolute bottom-5 end-0 translate-middle"
              >
                <path
                  d="M7.50764 9.07173H0.64897V7.12216H7.4565C8.00621 7.12216 8.45792 7.0348 8.81161 6.86009C9.16957 6.68537 9.43377 6.44247 9.60423 6.13139C9.77894 5.82031 9.86417 5.45597 9.85991 5.03835C9.86417 4.62926 9.77894 4.26278 9.60423 3.93892C9.43377 3.6108 9.17383 3.35298 8.8244 3.16548C8.47923 2.97372 8.04031 2.87784 7.50764 2.87784H4.93803V14H2.57298V0.909091H7.50764C8.52184 0.909091 9.3805 1.09233 10.0836 1.45881C10.7868 1.82102 11.3194 2.31321 11.6816 2.93537C12.0481 3.55327 12.2314 4.24787 12.2314 5.01918C12.2314 5.82031 12.046 6.52557 11.6752 7.13494C11.3045 7.74006 10.7676 8.2152 10.0645 8.56037C9.36133 8.90128 8.50906 9.07173 7.50764 9.07173ZM7.80806 10.0433V11.9929H0.64897V10.0433H7.80806Z"
                  fill="#737373"
                />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="tariffs-wrap">
    <template v-for="(group, groupName) in tariffs">
      <Transition :duration="{ enter: 800, leave: 300 }">
        <div class="tariff" v-show="isEnabled(group[0])">
          <tariff-group
            :title="groupName"
            :tariffs="group"
            :amount="amount"
            @tariffSelected="
              (selectedTariffId) => handleTariffSelection(selectedTariffId)
            "
          />
        </div>
      </Transition>
    </template>
  </div>
</template>

<script>
import TariffGroup from "./TariffGroup.vue";

export default {
  name: "Calculator",
  async mounted() {},
  data() {
    return {
      tariffId: null,
      amount: 1000000,
      amountError: null,
      tariffBlocks: null,
    };
  },
  props: {
    tariffs: Object,
  },
  methods: {
    handleAmount() {
      document.querySelectorAll("[data-min-amount]").forEach((el) => {
        el.style.display = "none";
      });

      this.amount = +this.$refs.amount.value.replace(/\D/g, "");

      this.validateAmount();
    },
    validateAmount() {
      this.amountError = null;

      if (+this.amount < 500000) {
        this.amountError = "Минимальная сумма 500 000 рублей";
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
      let url = new URL(route("contracts.agree"));
      url.searchParams.set("amount", this.amount);
      url.searchParams.set("tariff_id", tariffId);

      window.location.replace(url);
    },
  },
  computed: {
    amountFormatted() {
      let numberFormat = new Intl.NumberFormat("ru-RU");

      return numberFormat.format(this.amount);
    },
  },
  components: { TariffGroup },
};
</script>

<style lang="scss" scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity transform 0.3s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
