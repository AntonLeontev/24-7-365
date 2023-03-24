<template>
  <div class="col title cursor-pointer" :data-title="title" @click="changeSort">
    <slot></slot>
    <svg
      v-show="title === sort"
      :class="{ 'arrow-rotated': order === 'DESC' }"
      xmlns="http://www.w3.org/2000/svg"
      width="16"
      height="16"
      fill="currentColor"
      class="arrow"
      viewBox="0 0 16 16"
    >
      <path
        d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"
      />
    </svg>
  </div>
</template>

<script>
export default {
  name: "ColumnTitle",
  created() {},
  data() {
    return {};
  },
  props: {
    title: String,
    sort: String,
    order: String,
  },
  methods: {
    changeSort(event) {
      this.$emit("change-sort", { title: this.title });
    },
  },
};
</script>

<style lang="scss" scoped>
.arrow {
  transition: transform 0.3s ease;
}
.arrow-rotated {
  transform: rotateZ(180deg);
}
</style>
