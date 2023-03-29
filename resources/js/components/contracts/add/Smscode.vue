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
          <form
            action="{{ route('smscode.check', 'phone_confirmation') }}"
            ref="checkCodeForm"
          >
            <div class="mb-13">
              <input-component
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

          <button class="btn btn-link w-100" @click.prevent="askSmsCode">
            Прислать еще раз
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
    };
  },
  props: {
    phone: String,
    errors: Object,
  },
  methods: {
    async handle() {
      try {
        await this.checkCode();
        await this.savePhoneNumber();
      } catch (error) {
        return;
      }

      this.$emit("numberConfirmed", this.phone);
    },
    async askSmsCode() {
      let formData = new FormData();

      await nextTick();
      formData.append("phone", this.phone);

      axios
        .post(route("smscode.create", "phone_confirmation"), formData)
        .then((response) => {
          this.smscode = response.data.code;
        })
        .catch((response) => {
          this.$emit("errors", response);
        });
    },
    async checkCode() {
      const form = this.$refs.checkCodeForm;

      let formData = new FormData(form);

      await axios
        .post(route("smscode.check", "phone_confirmation"), formData)
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
    async savePhoneNumber() {
      let formData = new FormData();
      formData.append("phone", this.phone);

      axios
        .post(route("users.updatePhone"), formData)
        .then((response) => {
          this.$emit("notify", "Номер сохранен", 1500);
        })
        .catch((response) => {
          this.$emit("errors", response);
          throw response;
        });
    },
    emitInterface() {
      this.$emit("interface", {
        askSmsCode: () => this.askSmsCode(),
        modal: this.modal,
      });
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
