<style>
	 table, th, td {
		border: 1px solid black;
	}
</style>

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
				<td>{{ $item->get('contract_amount') }} р</td>
				<td>{{ $item->get('contract_duration') }} мес</td>
				<td>{{ number_format($item->get('contract_amount') / $item->get('contract_duration') / $periodInDays * $days, 2, ',', ' ') }}</td>
				<td>{{ number_format($item->get('payments_sum', 2, ',', ' ')) }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
<div>
	<div><span>Income:</span> <span>{{ $income }}</span></div>
	<div><span>Body sum:</span> <span>{{ $bodySum }}</span></div>
	<div><span>Outcome:</span> <span>{{ $outcome }}</span></div>
	<div><span>Profit:</span> <span>{{ $income - $bodySum - $outcome }}</span></div>
</div>
