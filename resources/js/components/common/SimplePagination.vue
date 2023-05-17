<template>
  <nav
    role="navigation"
    aria-label="Pagination Navigation"
    v-if="Object.keys(paginator).length > 0"
  >
    <ul class="pagination">
      <li class="page-item disabled" aria-disabled="true" v-if="!paginator.links.prev">
        <span class="page-link">Назад</span>
      </li>
      <li class="page-item" v-else>
        <a
          class="page-link"
          :href="paginator.links.prev"
          :data-page="paginator.meta.current_page - 1"
          rel="prev"
          @click.prevent="change"
          >Назад</a
        >
      </li>
      <li class="page-item p-2">
        {{ paginator.meta.current_page }}
      </li>
      <li class="page-item" v-if="paginator.links.next">
        <a
          class="page-link"
          :href="paginator.links.next"
          :data-page="paginator.meta.current_page + 1"
          rel="next"
          @click.prevent="change"
          ref="next"
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
import _ from "lodash";

export default {
  name: "SimplePagination",
  created() {},
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

      this.$emit("change-page", {
        href: target.getAttribute("href"),
        page: target.dataset.page,
      });
    },
  },
  computed: {},
};
</script>

<style lang="scss" scoped></style>
