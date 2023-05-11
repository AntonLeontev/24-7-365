<template>
  <div class="form-input" :class="class">
    <input
      :type="type"
      class="form-control"
      :name="name"
      :placeholder="placeholder"
      v-model="newValue"
      autocorrect="off"
      autocomplete="off"
      autocapitalize="off"
      @input="input"
      :class="{ 'border-primary': error }"
      :readonly="readonly"
      :tabindex="tabindex"
      :id="id"
    />
    <label class="form-label mb-0" v-show="!error && isFilled">{{ placeholder }}</label>
    <label class="form-label mb-0" v-show="!error && !isFilled">{{ label }}</label>
    <label class="form-label text-primary mb-0" v-cloak v-show="error" v-text="error"></label>
  </div>
</template>

<script>
export default {
  name: "InputComponent",
  created() {},
  data() {
    return {
      newValue: this.value ?? "",
    };
  },
  props: {
    name: String,
    placeholder: String,
    label: String,
    value: String,
    readonly: Boolean,
    tabindex: {},
    class: String,
    id: String,
    error: String,
    type: { default: "text" },
  },
  methods: {
    input() {
      this.$emit("clear-error", this.name);
    },
  },
  computed: {
    isFilled() {
      return this.newValue.trim().length > 0;
    },
  },
};
</script>

<style lang="scss" scoped></style>
