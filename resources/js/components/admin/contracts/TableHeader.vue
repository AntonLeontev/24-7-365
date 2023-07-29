<template>
  <div class="table_dark__header">
    <column-title
      title="id"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Номер</column-title
    >

    <column-title
      title="organization"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Организация</column-title
    >
    <column-title
      title="amount"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Сумма</column-title
    >
    <column-title
      title="tariff_title"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Тариф</column-title
    >
    <column-title
      title="status"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Статус</column-title
    >
    <column-title
      title="paid_at"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Дата оплаты</column-title
    >
  </div>
</template>

<script>
import ColumnTitle from "./ColumnTitle.vue";

export default {
  name: "TableHeader",
  created() {},
  data() {
    return {};
  },
  props: {
    sort: String,
    order: String,
  },
  methods: {
    changeSort(data) {
      this.$emit("change-sort", data);
    },
  },
  components: { ColumnTitle },
};
</script>

<style lang="scss" scoped></style>
