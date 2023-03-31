<template>
  <div class="position-relative">
    <Slider
      :min="min"
      :max="max"
      :step="step"
      :tooltips="false"
      :lazy="false"
      v-model="duration"
      @update="$emit('tariffChange', duration)"
    />
    <div class="d-flex justify-content-between mt-11 text-light">
      <div v-for="tariff in tariffs">
        <span class="fs-7" :class="{ 'text-white': duration == tariff.duration }">
          {{ tariff.duration }} мес.
        </span>
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
  methods: {},
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

  &_passed {
    background-color: #fff;
  }
}
</style>
