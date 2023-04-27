@extends('layouts.app')

@section('title', 'График платежей')

@section('content')

	<x-common.h1 class="mb-4">График платежей</x-common.h1>

	@foreach ($profitabilities as $date => $monthOperations)
	<div class="card mb-4">
		<div class="card-header">
			{{ now()->parse($date)->translatedFormat("F, Y год") }}
		</div>
		<div class="card-body">
			<x-common.tables.yellow class="mb-3">
				<x-slot:header>
					<div class="col">Дата</div>
					<div class="col">Тело закупа</div>
					<div class="col">Договор</div>
					<div class="col">К выплате</div>
					<div class="col">Выплачено</div>
				</x-slot:header>

				@php
					$monthOperations = $monthOperations->sortBy('planned_at');
				@endphp

				@foreach ($monthOperations as $operation)
					<x-common.tables.yellow.row>
						<div class="col">{{ $operation->accrued_at->translatedFormat('d F Y') }}</div>
						<div class="col">
							{{ $operation->contract->amountOnDate($operation->accrued_at->subDay())->format(0) }}
						</div>
						<div class="col">
							<a href="{{ route('users.contract_show', $operation->contract->id) }}" class="btn-link">
								№{{ $operation->contract->id }}
							</a>	
						</div>
						<div class="col">+{{ $operation->amount }}</div>
						<div class="col d-flex justify-content-center flex-nowrap gap-2">
							@if ($operation->payment->planned_at->equalTo($operation->accrued_at))
								@if ($operation->payment->status->value === 'processed')
									{{ $operation->payment->amount }}
								@endif
							@else
								{{ $operation->payment->planned_at->translatedFormat('d F Y') }}
							@endif							
						</div>
					</x-common.tables.yellow.row>
				@endforeach
			</x-common.tables.yellow>

			@php
				$paymentsSum = 0;
				$profitabilitiesSum = 0;

				foreach ($monthOperations as $profitability) {
					$profitabilitiesSum += $profitability->amount->raw();

					if ($profitability->payment->status->value === 'processed') {
						$paymentsSum += $profitability->payment->amount->raw();
					}
				}

				$paymentsSum = new App\ValueObjects\Amount($paymentsSum);
				$profitabilitiesSum = new App\ValueObjects\Amount($profitabilitiesSum);
			@endphp
			<x-common.tables.total header="Итого за месяц">
				<x-common.tables.total.row label="Начислено" :value="$profitabilitiesSum" />
				<x-common.tables.total.row label="Выплачено" :value="$paymentsSum" />
			</x-common.tables.total>
		</div>
	</div>
	@endforeach

	@if ($profitabilities->isEmpty())
		<div class="p-4 pb-5">
			<div class="bg-body text-light p-5 text-center">
				<p>
					Пока нет начислений или выплат. Они появятся после оплаты любого договора
				</p>
			</div>
		</div>
	@endif

	{{-- {{ $payments->links() }} --}}



@endsection
