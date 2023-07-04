<template>
  <div class="table_dark_admin__header row">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: "TableHeader",
  created() {},
  data() {
    return {};
  },
  props: {},
  methods: {
    changeSort(data) {
      this.$emit("change-sort", data);
    },
  },
  components: {},
};
</script>

<style lang="scss" scoped></style>
