<template>
  <div class="card">
    <div class="card-header">Укажите сумму закупа</div>
    <div class="card-body pt-0 d-flex flex-column flex-md-row gap-13">
      <p class="w-md-50 d-flex align-items-center text-light fs-8 fs-md-6 mb-0">
        Введите сумму закупа, а после выберите подходящий тариф
      </p>
      <div class="form-input w-md-50">
        <input
          type="text"
          class="form-control"
          name="amount"
          placeholder="Введите сумму"
          autocorrect="off"
          autocomplete="off"
          autocapitalize="off"
          autofocus
          @keyup="input"
          :class="{ 'border-primary': errors.amount }"
          ref="amount"
          id="amount"
        />
        <label class="form-label" :class="{ 'text-primary': errors.amount }">
          {{ errors.amount ?? "Сумма закупа" }}
        </label>
        <svg
          width="13"
          height="14"
          viewBox="0 0 13 14"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
          class="position-absolute bottom-5 end-0 translate-middle-y"
        >
          <path
            d="M7.50764 9.07173H0.64897V7.12216H7.4565C8.00621 7.12216 8.45792 7.0348 8.81161 6.86009C9.16957 6.68537 9.43377 6.44247 9.60423 6.13139C9.77894 5.82031 9.86417 5.45597 9.85991 5.03835C9.86417 4.62926 9.77894 4.26278 9.60423 3.93892C9.43377 3.6108 9.17383 3.35298 8.8244 3.16548C8.47923 2.97372 8.04031 2.87784 7.50764 2.87784H4.93803V14H2.57298V0.909091H7.50764C8.52184 0.909091 9.3805 1.09233 10.0836 1.45881C10.7868 1.82102 11.3194 2.31321 11.6816 2.93537C12.0481 3.55327 12.2314 4.24787 12.2314 5.01918C12.2314 5.82031 12.046 6.52557 11.6752 7.13494C11.3045 7.74006 10.7676 8.2152 10.0645 8.56037C9.36133 8.90128 8.50906 9.07173 7.50764 9.07173ZM7.80806 10.0433V11.9929H0.64897V10.0433H7.80806Z"
            fill="#737373"
          />
        </svg>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ContractAmount",
  created() {},
  data() {
    return {
      amount: this.amountSaved,
      errors: {},
    };
  },
  props: {
    amountSaved: Number,
  },
  mounted() {
    this.$refs.amount.value = this.amountSaved === 0 ? "" : this.amountSaved;
    if (this.amountSaved > 0) {
      this.formatNumber();
    }
  },
  methods: {
    input() {
      this.errors.amount = null;

      this.formatNumber();

      if (this.amount < 500000) {
        this.errors.amount = "Минимальная сумма 500 000 р";
      }

      this.$emit("amountChanged", this.amount);
    },
    formatNumber() {
      let numberFormat = new Intl.NumberFormat("ru-RU", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      });
      this.amount = +this.$refs.amount.value.replace(/\D/g, "");
      document.querySelector("#amount").value = numberFormat.format(this.amount);
    },
  },
};
</script>

<style lang="scss" scoped></style>
