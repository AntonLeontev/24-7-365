<template>
  <div class="container">
    <div class="row">
      <div class="col">
        <button
          class="btn btn-success"
          data-bs-toggle="modal"
          data-bs-target="#createUser"
          type="button"
        >
          Create user
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col">ФИО</div>
      <div class="col">Роль</div>
      <div class="col">Организация</div>
      <div class="col">Сумма договоров</div>
      <div class="col">Сумма выплат</div>
      <div class="col">Статус</div>
      <div class="col">Блокировка</div>
    </div>
    <div v-for="user in users.data">
      <div class="row" :href="route('users.show', user.id)">
        <div class="col">
          <a :href="route('users.show', user.id)">
            {{ user.first_name }} {{ user.last_name }}
          </a>
        </div>
        <div class="col">
          <span v-if="user.roles.length === 0">Роль не назначена</span>
          <span v-else v-for="role in user.roles">{{ role.name }}</span>
        </div>
        <div class="col">
          {{ user.organization?.title ?? "Без организации" }}
        </div>
        <div class="col"></div>
        <div class="col"></div>
        <div class="col">
          <span class="text-success" v-if="user.status == 1">Активен</span>
          <span class="text-danger" v-else>Заблокирован</span>
        </div>
        <div class="col">
          <form
            :action="route('users.block', user.id)"
            method="post"
            v-if="user.status == 1"
            @submit.prevent="submit"
          >
            <button class="btn" type="submit">Заблокировать</button>
          </form>
          <form
            :action="route('users.unblock', user.id)"
            method="post"
            v-else
            @submit.prevent="submit"
          >
            <button class="btn" type="submit">Разблокировать</button>
          </form>
        </div>
      </div>
    </div>
    <simple-pagination :paginator="users" @change-page="changePage"></simple-pagination>
  </div>
</template>

<script>
export default {
  name: "Users",
  created() {},
  mounted() {
    this.update();
  },
  data() {
    return {
      users: {},
    };
  },
  props: {},
  methods: {
    route(...vars) {
      return route(...vars);
    },
    async update(url = route("users.index")) {
      await axios
        .get(url, {})
        .then((response) => {
          this.users = response.data;
        })
        .catch((data) => {
          this.$emit("toast", { type: "error", message: data.message });
        });
    },
    async submit(event) {
      let form = event.target.closest("form");

      await axios({
        url: form.getAttribute("action"),
        method: form.getAttribute("method"),
        data: new FormData(form),
      })
        .then((response) => {
          this.update();
          this.$emit("toast", { message: "Выполнено" });
        })
        .catch(function (data) {
          console.log(data);
        });
    },
    changePage(url) {
      this.update(url);
    },
  },
};
</script>

<style lang="scss" scoped></style>
