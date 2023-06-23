<template>
  <div v-if="tariffs.length > 1" class="position-relative">
    <Slider
      :min="min"
      :max="max"
      :step="-1"
      :tooltips="false"
      :lazy="true"
      v-model="duration"
      @update="change"
      ref="slider"
    />
    <div class="control-bar" @click="setSlider"></div>
    <div class="duration-bar d-flex justify-content-between text-light">
      <div
        class="duration fs-7"
        :class="{ 'text-white': duration == tariff.duration }"
        @click="duration = tariff.duration"
        v-for="tariff in tariffs"
      >
        {{ tariff.duration }} мес.
      </div>
    </div>

    <div class="position-absolute w-100 top-0 d-flex justify-content-between">
      <div
        v-for="tariff in tariffs"
        class="scale"
        :class="{ scale_passed: duration > tariff.duration }"
      ></div>
    </div>
  </div>
  <div v-else>
    <Slider
      :min="0"
      :max="max"
      :step="-1"
      :tooltips="false"
      :lazy="true"
      disabled
      v-model="duration"
      @update="change"
      ref="slider"
    />
    <div class="d-flex justify-content-end text-light">
      <div
        class="duration fs-7"
        :class="{ 'text-white': duration == tariff.duration }"
        @click="duration = tariff.duration"
        v-for="tariff in tariffs"
      >
        {{ tariff.duration }} мес.
      </div>
    </div>
  </div>
</template>

<script>
import Slider from "@vueform/slider";

export default {
  name: "TariffSlider",
  mounted() {
    this.duration = this.value ?? this.min;
  },
  data() {
    return {
      duration: this.tariffs[0].duration,
    };
  },
  props: {
    tariffs: Object,
    selectedTariffId: {},
  },
  methods: {
    change(val = null) {
      let duration = val ?? this.duration;

      let value = _.reduce(
        this.tariffs,
        function (closest, tariff) {
          if (Math.abs(closest - duration) <= Math.abs(tariff.duration - duration))
            return closest;

          return tariff.duration;
        },
        this.tariffs[0].duration
      );

      this.$emit("tariffChange", value);
      setTimeout(() => (this.duration = value), 100);
    },
    setSlider(event) {
      const rect = event.target.getBoundingClientRect();

      let left = (100 * event.offsetX) / rect.width;

      let value = this.min + ((this.max - this.min) / 100) * left;

      //   this.duration = value;

      this.change(value);
    },
  },
  components: { Slider },
  computed: {
    min() {
      return _.reduce(
        this.tariffs,
        function (min, tariff) {
          if (min <= tariff.duration) return min;

          return tariff.duration;
        },
        this.tariffs[0].duration
      );
    },
    max() {
      return _.reduce(
        this.tariffs,
        function (max, tariff) {
          if (max >= tariff.duration) return max;

          return tariff.duration;
        },
        this.tariffs[0].duration
      );
    },
    step() {
      return this.max / this.tariffs.length;
    },
    value() {
      let tariff = _.find(this.tariffs, (tariff) => {
        return tariff.id === this.selectedTariffId;
      });

      return tariff?.duration;
    },
  },
};
</script>

<style src="@vueform/slider/themes/default.css"></style>
<style lang="scss" scoped>
.scale {
  width: 2px;
  height: 7px;
  background-color: #737373;
  transform: translateY(-2px);
  transition: background-color 0.3s ease;

  &:first-child {
    background-color: #fff;
  }

  &:last-child {
    background-color: #737373;
  }

  &_passed {
    background-color: #fff;
  }
}

.control-bar {
  position: relative;
  top: -21px;
  height: 35px;
  cursor: pointer;
  z-index: 100;
}

.duration-bar {
  margin-top: -21px;
  position: relative;
  z-index: 2;
}
.duration {
  display: flex;
  height: 30px;
  cursor: pointer;
  align-items: flex-end;
  transition: color 0.3s ease;
}
</style>
