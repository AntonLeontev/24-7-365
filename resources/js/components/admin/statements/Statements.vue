<template>
  <div class="d-flex gap-2 mb-12">
		<div 
			class="btn btn-outline-primary" 
			v-for="statement in statements"
			:data-id="statement.id"
			@click="activate"
		>
			{{ formatDate(statement.date) }}
		</div>
	</div>

	<div class="card">
		<div class="card-header">
			Выписка {{ formatDate(activeStatement?.date) }}
			<div v-show="activeStatement">
				Страниц {{ response?.Meta?.totalPages }}, начальный баланс {{ formatNumber(transactions?.startDateBalance) }}, конечный баланс {{ formatNumber(transactions?.endDateBalance) }}
			</div>
		</div>
		<div class="card-body" v-show="activeStatement">
			<div v-if="transactions?.Transaction?.length === 0">Транзакций нет</div>
			<div v-else>Всего транзакций: {{ transactions?.Transaction?.length }}</div>

			<div
				class="row py-3 border-bottom border-light"
				v-for="transaction in transactions?.Transaction"
			>
				<div class="col-2 d-flex align-items-center">
					<span :class="{'text-success': transaction.creditDebitIndicator == 'Credit', 'text-danger': transaction.creditDebitIndicator == 'Debet'}">
						<span v-if="transaction.creditDebitIndicator == 'Credit'">+</span>
						<span v-else>-</span>
						{{ formatNumber(transaction.Amount.amount) }} р
					</span>
				</div>
				<div class="col d-flex align-items-center">
					<span v-if="transaction.creditDebitIndicator == 'Credit'">от {{ transaction.DebtorParty.name }}</span>
					<span v-else>{{ transaction.CreditorParty.name }}</span>
				</div>
				<div class="col-7 d-flex align-items-center">{{ transaction.description }}</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
  created() {
	this.getStatements();
  },
  data() {
    return {
		statements: [],
		response: null,
		transactions: {},
		activeStatement: null,
	};
  },
  props: {},
  methods: {
	getStatements() {
		axios
			.get('/admin/statements')
			.then(response => {
				this.statements = response.data.data;
				this.activeStatement = this.statements[0];
			})
	},
	activate(event) {
		this.activeStatement = this.statements.find(el => {
			return el.id == event.target.dataset.id;
		})
	},
	formatDate(date) {
		if (date === undefined) return '';

		date = new Date(date);

		return date.toLocaleDateString('ru')
	},
	formatNumber(number) {
		if (number === undefined) return '';
		if (number === null) return '';

		return number.toLocaleString('ru')
	},
  },
  watch: {
	activeStatement(val, oldVal) {
		axios
			.get(`/admin/statements/${val.id}`)
			.then(response => {
				this.response = response.data
				this.transactions = response.data.Data.Statement[0]
			})
	}
  },
};
</script>

<style lang="scss" scoped></style>
