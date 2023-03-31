<template>
  <div
    class="h-100 px-11 px-md-13 py-13 d-flex flex-column justify-content-between bg-secondary"
  >
    <div>
      <div
        class="mb-121 pb-121 border-bottom border-2 border-primary fs-6 fs-md-5 text-primary fw-bold"
      >
        Тариф <span class="text-uppercase">{{ title }}</span>
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
          :value="(selectedTariff.annual_rate / 12).toFixed(2)"
          append="%"
        />
        <tariff-calculation
          label="Доходность в месяц:"
          :value="profitPerMonth"
          append=" ₽"
        />
        <tariff-calculation
          :label="'Доход за ' + selectedTariff.duration + ' мес.'"
          :value="
            (
              ((amount * selectedTariff.annual_rate) / 100 / 12) *
              selectedTariff.duration
            ).toLocaleString()
          "
          append=" ₽"
        />
      </div>
      <div class="mb-121 mb-13">
        <tariff-properties-table :tariffs="tariffs" />
      </div>
    </div>
    <div>
      <div class="mb-121 mb-md-13">
        <p class="mb-121 fs-7">Срок вклада:</p>
        <tariff-slider
          :tariffs="tariffs"
          @tariffChange="(tariffDuration) => (duration = tariffDuration)"
        />
      </div>
      <button
        class="btn btn-outline-primary w-100"
        :disabled="isDisabled"
        @click="$emit('tariffSelected', selectedTariff.id)"
      >
        Выбрать
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
  props: ["title", "tariffs", "amount"],
  methods: {},
  components: { TariffSlider, TariffCalculation, TariffPropertiesTable },
  computed: {
    profitPerMonth() {
      let numberFormat = new Intl.NumberFormat("ru-RU", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
      return numberFormat.format(
        (this.amount * this.selectedTariff.annual_rate) / 100 / 12
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
  },
};
</script>

<style lang="scss" scoped></style>
