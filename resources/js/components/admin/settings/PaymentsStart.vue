<template>
  <form :action="action" method="post" @change="saveSetting">
    <select name="payments_start">
      <option v-for="setting in settings" :value="setting" :selected="setting == actual">
        {{ setting }}
      </option>
    </select>
  </form>
</template>

<script>
export default {
  name: "PaymentsStart",
  created() {},
  data() {
    return {};
  },
  props: {
    actual: String,
    settings: Array,
    action: String,
  },
  methods: {
    async saveSetting(event) {
      await submitForm(event, this.success, this.error);
    },
    success(data) {
      this.$emit("toast", {
        message: "Сохранено",
      });
    },
    error(message) {
      this.$emit("toast", {
        message: message,
        type: "error",
        autohide: false,
      });
    },
  },
  computed: {},
};
</script>

<style lang="scss" scoped></style>
