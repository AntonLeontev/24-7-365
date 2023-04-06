<template>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <span class="col-12 col-xxl-4">Список пользователей</span>

        <div class="col-12 col-xxl-8">
          <div class="row gy-2">
            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-input">
                <input
                  class="form-control pe-3"
                  type="text"
                  placeholder="Поиск"
                  v-model="search"
                  @change="() => update()"
                />
                <svg
                  width="14"
                  height="14"
                  viewBox="0 0 14 14"
                  fill="none"
                  class="position-absolute top-50 end-0 translate-middle-y"
                >
                  <path
                    d="M12.6322 13.6693L7.96557 9.0026C7.5952 9.2989 7.16927 9.53347 6.68779 9.70631C6.20631 9.87915 5.69396 9.96557 5.15075 9.96557C3.80507 9.96557 2.66631 9.49939 1.73446 8.56705C0.802604 7.6347 0.336431 6.49594 0.335938 5.15075C0.335938 3.80507 0.80211 2.66631 1.73446 1.73446C2.6668 0.802604 3.80557 0.336431 5.15075 0.335938C6.49643 0.335938 7.6352 0.80211 8.56705 1.73446C9.4989 2.6668 9.96507 3.80557 9.96557 5.15075C9.96557 5.69396 9.87915 6.20631 9.70631 6.68779C9.53347 7.16927 9.2989 7.5952 9.0026 7.96557L13.6693 12.6322L12.6322 13.6693ZM5.15075 8.48409C6.07668 8.48409 6.86384 8.15989 7.51223 7.51149C8.16063 6.8631 8.48458 6.07618 8.48409 5.15075C8.48409 4.22483 8.15989 3.43767 7.51149 2.78927C6.8631 2.14088 6.07618 1.81693 5.15075 1.81742C4.22483 1.81742 3.43767 2.14162 2.78927 2.79001C2.14088 3.43841 1.81693 4.22532 1.81742 5.15075C1.81742 6.07668 2.14162 6.86384 2.79001 7.51223C3.43841 8.16063 4.22532 8.48458 5.15075 8.48409Z"
                    fill="#737373"
                  />
                </svg>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-selection">
                <select class="form-select" @change="changeFilter">
                  <option value="all">Все</option>
                  <option value="with_contracts">С договорами</option>
                </select>
                <svg
                  class="selection-svg"
                  width="16"
                  height="10"
                  viewBox="0 0 16 10"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    class="arrow"
                    d="M16 1.79687L8 9.79687L-3.49691e-07 1.79687L1.42 0.376875L8 6.95687L14.58 0.376875L16 1.79687Z"
                  />
                </svg>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <button
                class="btn btn-outline-primary d-flex w-100 justify-content-center align-items-center gap-2 text-nowrap"
                data-bs-toggle="modal"
                data-bs-target="#createUser"
                type="button"
              >
                Создать пользователя
                <svg
                  width="11"
                  height="12"
                  viewBox="0 0 11 12"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M10.75 6.75H6.25V11.25H4.75V6.75H0.25V5.25H4.75V0.75H6.25V5.25H10.75V6.75Z"
                    fill="#FCE301"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="table_dark">
        <table-header
          @change-sort="changeSort"
          :sort="this.sort"
          :order="this.order"
        ></table-header>
        <div class="table_dark__body">
          <template v-for="user in users.data">
            <div class="table_dark__row" :href="route('users.show', user.id)">
              <div class="col">
                <a :href="route('users.show', user.id)">
                  {{ user.first_name }} {{ user.last_name }}
                </a>
              </div>
              <div class="col text-wrap text-break">
                {{ user.organization ?? "Нет" }}
              </div>
              <div class="col">{{ user.contracts_sum?.formatted ?? 0 }}</div>
              <div class="col"></div>
              <div class="col">
                <span class="text-success" v-if="user.is_blocked">Заблокирован</span>
                <span class="text-danger" v-else>Активен</span>
              </div>
            </div>
          </template>
        </div>
      </div>

      <simple-pagination :paginator="users" @change-page="changePage"></simple-pagination>
    </div>
  </div>
</template>

<script>
import TableHeader from "./TableHeader.vue";
import Choices from "choices.js";

export default {
  name: "Users",
  created() {},
  mounted() {
    this.update();

    let selects = document.querySelectorAll(".form-select");
    selects.forEach((select) => {
      new Choices(select, {
        searchEnabled: false,
        itemSelectText: "",
        shouldSort: false,
        allowHTML: true,
      });
    });
  },
  computed: {},
  data() {
    return {
      users: {},
      page: 1,
      search: "",
      sort: "",
      order: "ASC",
      filter: "all",
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

      if (this.filter !== "all") url.searchParams.append("filter", this.filter);

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

      if (this.order === "DESC") {
        this.order = "";
        this.sort = "";
        return;
      }

      this.order = "ASC";
    },
    changeFilter(event) {
      this.filter = event.target.value;
      this.update();
    },
  },
  components: { TableHeader },
};
</script>

<style lang="scss" scoped></style>
