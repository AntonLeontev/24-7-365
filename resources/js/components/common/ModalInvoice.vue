<template>
  <div
    class="modal fade"
    aria-labelledby="Платежное поручение"
    aria-hidden="true"
    tabindex="-1"
    id="modalInvoice"
  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header position-relative d-flex flex-column">
          <img
            class="mb-13"
            src="../../../images/payment.svg"
            alt="Платежное поручение"
          />
          <h5 class="modal-title pe-3 align-self-start">
            Вы можете скачать платежное поручение
          </h5>
          <button
            class="btn-close position-absolute top-30 end-30"
            data-bs-dismiss="modal"
            type="button"
            aria-label="Закрыть"
          ></button>
        </div>
        <div class="modal-body">
          <p class="mb-13">или получить его на почту</p>
          <button class="btn btn-primary w-100 mb-121" @click.prevent="invoiceDownload">
            Скачать
          </button>
          <button
            class="btn btn-outline-primary w-100 mb-2"
            @click.prevent="invoiceToEmail"
          >
            Получить на почту
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ModalInvoice",
  created() {},
  data() {
    return {
      modal: null,
    };
  },
  props: { paymentId: {} },
  methods: {
    invoiceDownload() {
      window.location.replace(route("invoice.pdf", this.paymentId));
    },
    invoiceToEmail() {
      alert("To do");
    },
    show() {
      this.modal.show();
    },
    emitInterface() {
      this.$emit("interface", {
        show: () => this.show(),
      });
    },
  },
  mounted() {
    this.modal = new bootstrap.Modal("#modalInvoice", { keyboard: false });

    this.emitInterface();
  },
};
</script>

<style lang="scss" scoped></style>
