<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Example Component</div>

          <div class="card-body">I'm an example component.</div>
          <div class="card">
            <div
              class="card-body"
              :class="(counter > 2 || counter < -2) && 'text-danger'"
            >
              Counter: <span>{{ this.counter }}</span>
              <div class="d-flex gap-1">
                <button class="btn btn-outline-danger" @click="decrement">-</button>
                <button class="btn btn-outline-success" @click="increment">+</button>
              </div>
              <p>{{ message }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      counter: 0,
      wasOver: false,
    };
  },
  computed: {
    showText() {
      return this.counter >= -2 && this.counter <= 2 && this.wasOver;
    },
    message() {
      if (this.counter > 2) return "Чето много!";
      if (this.counter < -2) return "Чето мало!";
      if (this.showText) return "Вот теперь норм!";
      return "";
    },
  },
  methods: {
    increment() {
      this.counter++;
      this.checkWasOver();
    },
    decrement() {
      this.counter--;
      this.checkWasOver();
    },
    checkWasOver() {
      if (this.wasOver) return;

      if (this.counter > 2 || this.counter < -2) {
        this.wasOver = true;
      }
    },
  },
  mounted() {},
};
</script>
