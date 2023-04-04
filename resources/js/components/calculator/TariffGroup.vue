<template>
  <div
    class="tariff-group h-100 px-11 px-md-13 py-13 d-flex flex-column justify-content-between border border-2"
    :class="{
      'bg-secondary': style === 'calculator',
      'border-secondary': style === 'calculator' && !isSelected,
      'bg-body': style === 'contract',
      'border-dark': style === 'contract' && !isSelected,
      'border-primary': isSelected,
    }"
  >
    <div>
      <div
        class="mb-121 pb-121 border-bottom border-2 border-primary fs-6 fs-md-5 text-primary fw-bold"
      >
        Тариф <span class="text-uppercase">{{ title }}</span>
      </div>

      <div class="mb-121 mb-13">
        <tariff-properties-table :tariffs="tariffs" :style="style" />
      </div>
      <div class="d-flex flex-column gap-3 mb-121 mb-md-13">
        <tariff-calculation
          label="Срок:"
          :value="selectedTariff.duration"
          append=" мес."
        />
        <tariff-calculation label="Сумма:" :value="amountLocal" append=" ₽" />
        <tariff-calculation
          label="Доходность в год:"
          :value="selectedTariff.annual_rate"
          append="%"
        />
        <tariff-calculation
          label="Доходность в месяц:"
          :value="profitPerMonthPersent"
          append="%"
        />
        <tariff-calculation
          label="Доходность в месяц:"
          :value="profitPerMonth"
          append=" ₽"
        />
        <tariff-calculation
          :label="'Доход за ' + selectedTariff.duration + ' мес.'"
          :value="profitPerDuration"
          append=" ₽"
        />
        <tariff-calculation
          :label="'ROI за ' + selectedTariff.duration + ' мес.'"
          :value="roiPerDuration"
          append=""
        />
      </div>
    </div>
    <div>
      <div class="mb-121 mb-md-13">
        <p class="mb-121 fs-7">Срок вклада:</p>
        <tariff-slider
          :tariffs="tariffs"
          :selectedTariffId="selectedTariffId"
          @tariffChange="(tariffDuration) => tariffChange(tariffDuration)"
        />
      </div>
      <button
        class="btn w-100 d-flex justify-content-center align-items-center gap-2"
        :class="{ 'btn-primary': isSelected, 'btn-outline-primary': !isSelected }"
        :disabled="isDisabled"
        @click="selectTariff"
      >
        {{ isSelected ? "Тариф выбран" : "Выбрать" }}
        <svg
          width="16"
          height="12"
          viewBox="0 0 16 12"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
          v-show="isSelected"
        >
          <path
            d="M5.59509 12L0 6.31185L1.39877 4.88981L5.59509 9.15592L14.6012 0L16 1.42204L5.59509 12Z"
            fill="#19191A"
          />
        </svg>
      </button>
    </div>
  </div>
</template>

<script>
import TariffSlider from "./TariffSlider.vue";
import TariffCalculation from "./TariffCalculation.vue";
import TariffPropertiesTable from "./TariffPropertiesTable.vue";

export default {
  name: "TariffGroup",
  created() {},
  data() {
    return {
      duration: this.tariffs[0].duration,
    };
  },
  props: {
    title: {},
    tariffs: {},
    amount: {},
    style: { default: () => "calculator" },
    selectedTariffId: {},
  },
  methods: {
    selectTariff() {
      this.$emit("tariffSelected", this.selectedTariff.id);
    },
    tariffChange(tariffDuration) {
      this.duration = tariffDuration;

      if (this.isSelected) this.selectTariff();
    },
  },
  components: { TariffSlider, TariffCalculation, TariffPropertiesTable },
  computed: {
    profitPerMonthPersent() {
      let numberFormat = new Intl.NumberFormat("ru-RU", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
      return numberFormat.format(this.selectedTariff.annual_rate / 12);
    },
    profitPerMonth() {
      let numberFormat = new Intl.NumberFormat("ru-RU", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
      return numberFormat.format(
        (this.amount * this.selectedTariff.annual_rate) / 100 / 12
      );
    },
    profitPerDuration() {
      let numberFormat = new Intl.NumberFormat("ru-RU", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
      });
      return numberFormat.format(
        ((this.amount * this.selectedTariff.annual_rate) / 100 / 12) *
          this.selectedTariff.duration
      );
    },
    roiPerDuration() {
      let numberFormat = new Intl.NumberFormat("ru-RU", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
      });
      return numberFormat.format(
        (((this.amount * this.selectedTariff.annual_rate) / 100 / 12) *
          this.selectedTariff.duration +
          this.amount) /
          this.amount
      );
    },
    selectedTariff() {
      return _.find(this.tariffs, (tariff) => {
        return tariff.duration === this.duration;
      });
    },
    amountLocal() {
      let numberFormat = new Intl.NumberFormat("ru-RU", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      });
      return numberFormat.format(this.amount);
    },
    isDisabled() {
      if (this.selectedTariff.max_amount.raw === 0) {
        return this.amount < this.selectedTariff.min_amount.amount;
      }

      return (
        this.amount < this.selectedTariff.min_amount.amount ||
        this.amount >= this.selectedTariff.max_amount.amount
      );
    },
    isSelected() {
      let tariff = _.find(this.tariffs, (tariff) => {
        return tariff.id === this.selectedTariffId;
      });

      return !!tariff;
    },
  },
};
</script>

<style lang="scss" scoped>
.tariff-group {
  transition: border-color 0.8s ease;
}
</style>
