<template>
  <div class="position-relative">
    <div class="position-relative d-flex align-items-center gap-3" @click="toggle">
      <div class="position-relative top-notifications cursor-pointer">
        <svg
          width="20"
          height="22"
          viewBox="0 0 20 22"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M19.2099 15.4713C18.6243 14.4324 17.9196 12.4565 17.9196 8.96296V8.23981C17.9196 3.72778 14.3962 0.0305556 10.0589 0H9.99937C8.95796 0.0013367 7.92701 0.213155 6.96538 0.623362C6.00374 1.03357 5.13025 1.63413 4.39479 2.39075C3.65933 3.14738 3.07629 4.04525 2.67896 5.03311C2.28164 6.02096 2.07781 7.07946 2.07911 8.14815V8.96296C2.07911 12.4565 1.37443 14.4324 0.788845 15.4713C0.647225 15.7189 0.571845 16.0004 0.570336 16.2875C0.568826 16.5746 0.64124 16.857 0.78025 17.1061C0.919259 17.3552 1.11993 17.5622 1.36195 17.7062C1.60398 17.8502 1.87877 17.926 2.15851 17.9259H6.02932C6.02932 19.0064 6.44759 20.0427 7.19212 20.8067C7.93665 21.5708 8.94645 22 9.99937 22C11.0523 22 12.0621 21.5708 12.8066 20.8067C13.5512 20.0427 13.9694 19.0064 13.9694 17.9259H17.8402C18.1199 17.9278 18.3951 17.8533 18.6375 17.7101C18.8799 17.5669 19.0809 17.3602 19.2198 17.1111C19.3576 16.8606 19.4292 16.5775 19.4274 16.2898C19.4257 16.0022 19.3507 15.72 19.2099 15.4713ZM9.99937 20.3704C9.36842 20.3677 8.76406 20.1093 8.31791 19.6514C7.87175 19.1936 7.61995 18.5734 7.61734 17.9259H12.3814C12.3788 18.5734 12.127 19.1936 11.6808 19.6514C11.2347 20.1093 10.6303 20.3677 9.99937 20.3704ZM2.15851 16.2963C2.84335 15.0741 3.66713 12.8231 3.66713 8.96296V8.14815C3.66452 7.29309 3.82638 6.4459 4.14344 5.65516C4.46049 4.86441 4.92651 4.14566 5.51477 3.54009C6.10303 2.93452 6.80198 2.45405 7.57154 2.12621C8.34109 1.79838 9.16614 1.62963 9.99937 1.62963H10.049C13.5129 1.65 16.3316 4.62407 16.3316 8.23981V8.96296C16.3316 12.8231 17.1554 15.0741 17.8402 16.2963H2.15851Z"
            fill="#FCE301"
          />
        </svg>
        <div
          class="notifications__number"
          v-text="notifications.length"
          v-show="notifications.length > 0"
        ></div>
      </div>
    </div>

    <div class="notifications__modal" v-if="notifications.length > 0" v-show="show">
      <div class="modal__header">
        <span class="fs-6">Уведомления</span>
        <button class="btn btn-link fs-8" @click="readAll">
          Пометить всё прочитанным
        </button>
      </div>
      <div class="modal__body">
        <div class="notification" v-for="notification in notifications">
          <div class="d-flex justify-content-between gap-3">
            <div class="notification__heading">
              <span v-if="notification.data.title">{{ notification.data.title }}</span>
            </div>
            <div class="fs-8 text-light text-nowrap">
              {{ datetime(notification.created_at) }}
            </div>
          </div>
          <div class="notification__text" v-if="notification.data.text">
            {{ notification.data.text }}
          </div>
          <div class="d-flex">
            <a
              class="btn btn-link ps-0 fs-7"
              :href="notification.data.button.href"
              v-if="notification.data.button"
            >
              {{ notification.data.button.text }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Notifications",
  created() {
    this.getUnread();
    document.addEventListener("click", this.outside);
  },
  data() {
    return {
      notifications: {},
      show: false,
    };
  },
  methods: {
    getUnread() {
      axios
        .post(route("notifications.unread"))
        .then((response) => (this.notifications = response.data))
        .catch((response) => console.log(response));
    },
    readAll() {
      axios.get(route("notifications.read_all")).then(() => {
        this.show = false;
        this.getUnread();
      });
    },
    toggle() {
      if (this.notifications.length <= 0) return;

      this.show = !this.show;
    },
    hide() {
      if (this.notifications.length <= 0) return;

      this.show = false;
    },
    datetime(date) {
      const options = {
        month: "short",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
      };
      return new Date(date).toLocaleString("ru", options).replace(/[,\.]/g, "");
    },
    outside(event) {
      let notifications = event.target.closest("#notifications");

      if (notifications) return;

      this.hide();
    },
  },
  directives: {},
};
</script>

<style lang="scss" scoped></style>
