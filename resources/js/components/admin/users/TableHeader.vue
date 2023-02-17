<template>
  <div class="row">
    <column-title
      title="name"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >ФИО</column-title
    >
    <column-title
      title="role"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Роль</column-title
    >
    <column-title
      title="organization"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Организация</column-title
    >
    <column-title
      title="contract_sum"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Сумма договоров</column-title
    >
    <column-title
      title="payment_sum"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Сумма выплат</column-title
    >
    <column-title
      title="status"
      :sort="this.sort"
      :order="this.order"
      @change-sort="changeSort"
      >Статус</column-title
    >
    <column-title title="block">Блокировка</column-title>
  </div>
</template>

<script>
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
};
</script>

<style lang="scss" scoped></style>
