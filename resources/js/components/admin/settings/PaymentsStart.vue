<template>
  <form class="w-100" :action="action" method="post" @change="saveSetting">
    <div class="form-selection">
      <select class="form-select" name="payments_start">
        <option
          v-for="setting in settings"
          :value="setting"
          :selected="setting == actual"
        >
          {{ setting }} месяц
        </option>
      </select>
      <svg
        class="selection-svg"
        width="16"
        height="10"
        viewBox="0 0 16 10"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          class="arrow"
          d="M16 1.79687L8 9.79687L-3.49691e-07 1.79687L1.42 0.376875L8 6.95687L14.58 0.376875L16 1.79687Z"
        />
      </svg>
    </div>
  </form>
</template>

<script>
import Choices from "choices.js";

export default {
  name: "PaymentsStart",
  created() {},
  data() {
    return {};
  },
  mounted() {
    let selects = document.querySelectorAll(".form-select");
    selects.forEach((select) => {
      new Choices(select, {
        searchEnabled: false,
        itemSelectText: "",
        shouldSort: false,
        allowHTML: true,
      });
    });
  },
  props: {
    actual: String,
    settings: Array,
    action: String,
  },
  methods: {
    async saveSetting(event) {
      let form = event.target.closest("form");

      let formData = new FormData(form);

      await axios
        .request({
          url: form.action,
          method: form.method,
          data: formData,
        })
        .then((response) => {
          this.success();
        })
        .catch((response) => {
          this.error(response.response?.message ?? "Ошибка сохранения. Попробуйте позже");
        });
    },
    success(data) {
      this.$emit("toast", {
        message: "Сохранено",
      });
    },
    error(message) {
      this.$emit("toast", {
        message: message,
      });
    },
  },
  computed: {},
};
</script>

<style lang="scss" scoped></style>
