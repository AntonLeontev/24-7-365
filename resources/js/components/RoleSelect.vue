<template>
  <form :action="action" method="post" @change="saveRole">
    <select name="roles[]">
      <template v-if="userRoles.includes('Superuser')">
        <option selected>Superuser</option>
      </template>
      <template v-else>
        <option v-if="userWithoutRoles" disabled selected>Роль</option>
        <option v-for="role in roles" :value="role" :selected="userRoles.includes(role)">
          {{ role }}
        </option>
      </template>
    </select>
  </form>
</template>

<script>
export default {
  name: "RoleSelect",
  created() {},
  data() {
    return {};
  },
  props: {
    userRoles: Array,
    action: String,
    roles: Array,
  },
  methods: {
    async saveRole(event) {
      await submitForm(event, this.success, this.error);
    },
    success(data) {
      this.$emit("toast", {
        message: "Сохранено",
      });
    },
    error(message) {
      this.$emit("toast", {
        message: message,
        type: "error",
        autohide: false,
      });
    },
  },
  computed: {
    userWithoutRoles() {
      return this.userRoles.length === 0 ? true : false;
    },
  },
};
</script>

<style lang="scss" scoped></style>
