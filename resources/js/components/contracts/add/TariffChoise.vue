<template>
  <div class="card">
    <div class="card-header">Выберите тариф</div>
    <div class="card-body">
      <div class="bg-body" v-for="(group, groupName) in tariffs">
        <div>{{ groupName }}</div>
        <div>
          <button class="btn btn-outline-primary" v-for="tariff in group">
            {{ tariff.title + " - " + tariff.duration }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TariffChoise",
  created() {},
  data() {
    return {};
  },
  props: {
    tariffId: Number,
    tariffs: Object,
  },
  methods: {},
};
</script>

<style lang="scss" scoped></style>
