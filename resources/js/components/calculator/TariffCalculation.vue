<template>
  <div class="d-flex justify-content-between gap-2 border-bottom border-light border-2">
    <span class="fs-8 fs-md-7">{{ label }}</span>
    <span class="fs-8 fs-md-7 text-nowrap fw-bold d-flex align-items-end"
      >{{ value }}{{ append }}</span
    >
  </div>
</template>

<script>
export default {
  name: "TariffCalculation",
  created() {},
  data() {
    return {};
  },
  props: ["label", "value", "append"],
  methods: {},
};
</script>

<style lang="scss" scoped></style>
