<template>
  <div
    class="modal fade"
    aria-labelledby="Введите код из сообщения"
    aria-hidden="true"
    tabindex="-1"
    id="smscode"
  >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title pe-3">Введите код из сообщения</h5>
          <button
            class="btn-close"
            data-bs-dismiss="modal"
            type="button"
            aria-label="Закрыть"
          ></button>
        </div>
        <div class="modal-body">
          <form ref="checkCodeForm">
            <div class="mb-13">
              <input-component
                :value="input"
                name="code"
                placeholder="Код из сообщения"
                :error="this.errors.code"
                @clearError="(name) => (errors[name] = null)"
              ></input-component>
            </div>
            <button class="btn btn-primary w-100 mb-2" @click.prevent="handle">
              Отправить
            </button>
          </form>

          <button
            class="btn btn-link w-100"
            @click.prevent="askSmsCode"
            :disabled="timerCount > 0"
          >
            Прислать еще раз
            <span class="text-decoration-none" v-show="timerCount > 0"
              >({{ timerCount }} сек.)</span
            >
          </button>

          <!--  //TODO Удалить -->
          <span v-show="smscode" v-text="smscode"></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import InputComponent from "./InputComponent.vue";
import { nextTick } from "vue";

export default {
  name: "Smscode",
  created() {},
  data() {
    return {
      smscode: null,
      type: null,
      input: "",
      timerCount: 0,
    };
  },
  props: {
    phone: String,
    errors: Object,
  },
  methods: {
    async handle() {
      await this.checkCode()
        .then(() => {
          if (this.type === "phone_confirmation") {
            this.$emit("phoneIsConfirmed", this.phone);
          }

          if (this.type === "contract_creating") {
            this.$emit("contractIsConfirmed", this.phone);
          }

          this.type = null;
        })
        .catch((error) => {
          this.$emit("errors", error);
        });
    },
    async confirmPhone() {
      this.type = "phone_confirmation";
      this.input = "";
      this.modal.show();
      await this.askSmsCode();
    },
    async confirmContractCreation() {
      this.type = "contract_creating";
      this.input = "";
      this.modal.show();
      await this.askSmsCode();
    },
    async askSmsCode() {
      if (this.timerCount > 0) return;

      let formData = new FormData();

      await nextTick();
      formData.append("phone", this.phone);

      axios
        .post(route("smscode.create", this.type), formData)
        .then((response) => {
          this.smscode = response.data.code;
          this.timerCount = 35;
        })
        .catch((response) => {
          this.$emit("errors", response);
        });
    },
    async checkCode() {
      const form = this.$refs.checkCodeForm;

      let formData = new FormData(form);

      await axios
        .post(route("smscode.check", this.type), formData)
        .then((response) => {
          if (!response.data.ok) {
            this.errors.code = response.data.message;
            throw response;
          }

          this.modal.hide();
        })
        .catch((response) => {
          this.$emit("errors", response);
          throw response;
        });
    },
    emitInterface() {
      this.$emit("interface", {
        confirmContractCreation: () => this.confirmContractCreation(),
        confirmPhone: () => this.confirmPhone(),
      });
    },
  },
  watch: {
    timerCount: {
      handler(value) {
        if (value > 0) {
          setTimeout(() => {
            this.timerCount--;
          }, 1000);
        }
      },
      immediate: true,
    },
  },
  mounted() {
    this.modal = new bootstrap.Modal("#smscode", { keyboard: false });

    this.emitInterface();
  },
  components: { InputComponent },
};
</script>

<style lang="scss" scoped></style>
