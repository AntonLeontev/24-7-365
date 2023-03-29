<template>
  <div class="form-input">
    <input
      type="text"
      class="form-control"
      :name="name"
      :placeholder="placeholder"
      :value="value"
      autocorrect="off"
      autocomplete="off"
      autocapitalize="off"
      v-on:input="$emit('clearError', this.name)"
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
    return {};
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
  },
};
</script>

<style lang="scss" scoped></style>
