<template>
  <nav
    role="navigation"
    aria-label="Pagination Navigation"
    v-if="paginator.last_page > 1"
  >
    <ul class="pagination">
      <li class="page-item disabled" aria-disabled="true" v-if="paginator.prev_page_url">
        <span class="page-link">Назад</span>
      </li>
      <li class="page-item" v-else>
        <a
          class="page-link"
          :href="paginator.prev_page_url"
          rel="prev"
          @click.prevent="change"
          >Назад</a
        >
      </li>
      <li class="page-item" v-if="paginator.next_page_url">
        <a
          class="page-link"
          :href="paginator.next_page_url"
          rel="next"
          @click.prevent="change"
          >Вперед</a
        >
      </li>
      <li class="page-item disabled" aria-disabled="true" v-else>
        <span class="page-link">Вперед</span>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "SimplePagination",
  created() {
    console.log(this.paginator);
  },
  data() {
    return {};
  },
  props: {
    paginator: Object,
  },
  methods: {
    change(event) {
      let target = event.target;
      if (target.tagName != "A") return;

      this.$emit("change-page", target.getAttribute("href"));
    },
  },
};
</script>

<style lang="scss" scoped></style>
