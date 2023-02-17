<template>
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <transition name="fade">
      <div
        class="toast show align-items-center"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        v-show="show"
        :class="classes"
      >
        <div class="d-flex">
          <div class="toast-body">{{ text }}</div>
          <button
            type="button"
            class="btn-close me-2 m-auto"
            aria-label="Close"
            @click="show = false"
          ></button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: "Toasts",
  computed: {
    classes() {
      return {
        "text-bg-success": this.type === "success",
        "text-bg-danger": this.type === "error",
      };
    },
  },
  mounted() {},
  data() {
    return {
      show: false,
      text: "",
      type: "success",
    };
  },
  props: {},
  methods: {
    fire: function (options) {
      this.text = options.message;
      this.type = options.type ?? "success";
      this.show = true;

      if (options.autohide ?? true) {
        setTimeout(this.hide, options.delay ?? 1000);
      }
    },
    hide() {
      this.show = false;
    },
  },
};
</script>

<style lang="scss" scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
