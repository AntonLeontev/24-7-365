<template>
  <div class="d-flex justify-between mb-12">
	
		<div v-for="statement in statements" class="btn btn-outline-primary">{{ statement.date }}</div>
	</div>

	<div class="card">
		<div class="card-header">
			Выписка
		</div>
		<div class="card-body"></div>
	</div>
</template>

<script>
export default {
  created() {
	this.getStatements();
  },
  data() {
    return {
		statements: []
	};
  },
  props: {},
  methods: {
	getStatements() {
		axios
			.get('/admin/statements')
			.then(response => {
				this.statements = response.data.data;
				console.log(response);
			})
	},
  },
};
</script>

<style lang="scss" scoped></style>
