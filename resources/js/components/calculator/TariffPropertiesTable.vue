<template>
  <div
    class="d-flex flex-column gap-2 py-121 pe-11"
    :class="{ 'bg-secondary': style == 'contract', 'bg-body': style == 'calculator' }"
  >
    <div class="property fs-8 fs-md-7">
      Объем вклада от
      <span class="text-nowrap"
        >{{ tariffs[0].min_amount.amount.toLocaleString() }} ₽</span
      >
      <span v-if="tariffs[0].max_amount.raw !== 0">
        до
        <span class="text-nowrap"
          >{{ tariffs[0].max_amount.amount.toLocaleString() }} ₽</span
        ></span
      >
    </div>
    <div class="property fs-8 fs-md-7">
      Доходность
      <span v-if="tariffs[0].getting_profit === 1">ежемесячно</span>
      <span v-else-if="tariffs[0].getting_profit === 2">в конце срока</span>
    </div>
    <div class="property fs-8 fs-md-7">
      Тело вклада
      <span v-if="tariffs[0].getting_deposit === 1">ежемесячно</span>
      <span v-else-if="tariffs[0].getting_deposit === 2">в конце срока</span>
    </div>
  </div>
</template>

<script>
export default {
  name: "TariffPropertiesTable",
  created() {},
  data() {
    return {};
  },
  props: {
    tariffs: Object,
    style: {
      type: String,
      default: () => "calculator",
    },
  },
  methods: {},
};
</script>

<style lang="scss" scoped>
.property {
  position: relative;
  padding-left: 46px;

  &::before {
    content: "";
    position: absolute;
    left: 24px;
    top: 9px;
    width: 16px;
    height: 2px;
    background-color: #fce301;
  }
}
</style>
