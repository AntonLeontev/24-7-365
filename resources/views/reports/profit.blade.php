<style>
	 table, th, td {
		border: 1px solid black;
	}
</style>

@php
	$periodDays = $period->getStartDate()->diffInDays($period->getEndDate()) + 1;
@endphp

<table>
	<thead>
		<tr>
			<th>Номер договора</th>
			<th>Наименование</th>
			<th>Старт периода</th>
			<th>Конец периода</th>
			<th>Дней</th>
			<th>Сумма договора</th>
			<th>Срок договора</th>
			<th>Часть суммы</th>
			<th>Выплаты доходности</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data as $item)
			<tr>
				@php
					$days = $item->get('period_start')->diffInDays($item->get('period_end')) + 1;
				@endphp
				<td>{{ $item->get('contract_number') }}</td>
				<td>{{ $item->get('organization') }}</td>
				<td>{{ $item->get('period_start')->translatedFormat('d F Y') }}</td>
				<td>{{ $item->get('period_end')->translatedFormat('d F Y') }}</td>
				<td>{{ $days }}</td>
				<td>{{ $item->get('contract_amount') }}</td>
				<td>{{ $item->get('contract_duration') }}</td>
				<td>{{ $item->get('contract_amount') / $item->get('contract_duration') / $periodDays * $days }}</td>
				<td>{{ $item->get('payments_sum') }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
