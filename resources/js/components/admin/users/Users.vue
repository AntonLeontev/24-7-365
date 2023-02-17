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

    <table-header
      @change-sort="changeSort"
      :sort="this.sort"
      :order="this.order"
    ></table-header>

    <div v-for="user in users.data">
      <div class="row" :href="route('users.show', user.id)">
        <div class="col">
          <a :href="route('users.show', user.id)">
            {{ user.first_name }} {{ user.last_name }}
          </a>
        </div>
        <div class="col">
          <span>{{ user.role ?? "Роль не незначена" }}</span>
        </div>
        <div class="col text-break">
          {{ user.organization ?? "Нет" }}
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
  computed: {},
  data() {
    return {
      users: {},
      page: 1,
      search: "",
      sort: "",
      order: "ASC",
    };
  },
  props: {},
  methods: {
    route(...vars) {
      return route(...vars);
    },
    async update(url = this.url()) {
      await axios
        .get(url, {})
        .then((response) => {
          this.users = response.data;
        })
        .catch((data) => {
          if (data.response.status === 401) {
            location.reload();
            return;
          }

          this.$emit("toast", { type: "error", message: data.message, autohide: false });
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
    changePage(data) {
      this.page = data.page;
      this.update(data.href);
    },
    url() {
      let url = new URL(route("users.index"));

      if (this.page != 1) url.searchParams.append("page", this.page);

      if (this.search !== "") url.searchParams.append("search", this.search);

      if (this.sort !== "") {
        url.searchParams.append("sort", this.sort);
        url.searchParams.append("order", this.order);
      }

      return url.href;
    },
    changeSort(data) {
      if (this.sort === data.title) {
        this.changeOrder();
      } else {
        this.sort = data.title;
        this.order = "ASC";
      }

      this.update();
    },
    changeOrder() {
      if (this.order === "ASC") {
        this.order = "DESC";
        return;
      }

      this.order = "ASC";
    },
  },
};
</script>

<style lang="scss" scoped></style>
