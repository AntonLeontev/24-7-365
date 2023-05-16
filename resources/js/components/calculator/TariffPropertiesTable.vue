<template>
  <div
    class="d-flex flex-column gap-2 py-121 pe-12"
    :class="{ 'bg-secondary': style == 'contract', 'bg-body': style == 'calculator' }"
  >
    <div class="property fs-8 fs-md-7">
      Объем вклада от
      <span class="text-nowrap"
        >{{ new Intl.NumberFormat("ru-RU").format(tariffs[0].min_amount.amount) }} ₽</span
      >
      <span v-if="tariffs[0].max_amount.raw !== 0">
        до
        <span class="text-nowrap"
          >{{
            new Intl.NumberFormat("ru-RU").format(tariffs[0].max_amount.amount)
          }}
          ₽</span
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
  padding-left: 30px;

  @media (min-width: 1700px) {
    padding-left: 48px;
  }

  &::before {
    content: url("data:image/svg+xml,%3Csvg width='10' height='7' viewBox='0 0 10 7' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 3.16103L1.17907 2.02009L3.95884 4.71569L8.82093 0L10 1.14337L3.95884 7L0 3.16103Z' fill='%23FCE301'/%3E%3C/svg%3E");
    position: absolute;
    left: 15px;
    top: -1px;

    @media (min-width: 1700px) {
      left: 26px;
    }
  }
}
</style>
