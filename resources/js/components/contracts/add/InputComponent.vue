<template>
  <div class="form-input">
    <input
      type="text"
      class="form-control"
      :name="name"
      :placeholder="placeholder"
      v-model="newValue"
      autocorrect="off"
      autocomplete="off"
      autocapitalize="off"
      v-on:input="input"
      :class="{ 'border-primary': error }"
      :readonly="readonly"
      :tabindex="tabindex"
      :id="id"
    />
    <label class="form-label" v-show="!error">{{ placeholder }}</label>
    <label class="form-label text-primary" v-cloak v-show="error" v-text="error"></label>
  </div>
</template>

<script>
export default {
  name: "InputComponent",
  created() {},
  data() {
    return {
      newValue: this.value,
    };
  },
  props: {
    name: String,
    placeholder: String,
    value: String,
    readonly: Boolean,
    tabindex: String,
    class: String,
    id: String,
    error: String,
  },
  methods: {
    input() {
      this.$emit("clearError", this.name);
    },
  },
};
</script>

<style lang="scss" scoped></style>
