@extends('layouts.app')

@section('title', 'График платежей')

@section('content')

	<x-common.h1 class="mb-4">График платежей</x-common.h1>

	@foreach ($operations as $date => $monthOperations)
	<div class="card mb-4">
		<div class="card-header">
			{{ now()->parse($date)->translatedFormat("F, Y год") }}
		</div>
		<div class="card-body">
			<x-common.tables.yellow class="mb-3">
				<x-slot:header>
					<div class="col">Дата</div>
					<div class="col">Начислено</div>
					<div class="col">Тело закупа</div>
					<div class="col">Договор</div>
					<div class="col">К выплате</div>
				</x-slot:header>

				@php
					$monthOperations = $monthOperations->sortBy('planned_at');
				@endphp

				@foreach ($monthOperations as $operation)
					@if ($operation instanceof App\Models\Profitability)
						<x-common.tables.yellow.row>
							<div class="col">{{ $operation->planned_at->translatedFormat('d F Y') }}</div>
							<div class="col">+{{ $operation->amount }}</div>
							<div class="col"></div>
							<div class="col">
								<a href="{{ route('users.contract_show', $operation->contract->id) }}" class="btn-link">
									№{{ $operation->contract->id }}
								</a>	
							</div>
							<div class="col d-flex justify-content-center flex-nowrap gap-2">
								@if ($operation->payment->planned_at->equalTo($operation->planned_at))
									@if ($operation->payment->status === $operation->payment::STATUS_PROCESSED)
										<svg width="17" height="12" viewBox="0 0 17 12" fill="none"
											xmlns="http://www.w3.org/2000/svg">
											<path
												d="M6.15251 11.3346L0.703125 6.2785L2.06547 5.01447L6.15251 8.80657L14.9241 0.667969L16.2865 1.932L6.15251 11.3346Z"
												fill="#60FC01" />
										</svg>
									@endif
									{{ $operation->payment->amount }}
								@else
									{{ $operation->payment->planned_at->translatedFormat('d F Y') }}
								@endif							
							</div>
						</x-common.tables.yellow.row>
					@elseif ($operation instanceof App\Models\Payment)
						@unless ($operation->status === $operation::STATUS_PROCESSED)
							<x-common.tables.yellow.row>
								<div class="col">{{ $operation->planned_at->translatedFormat('d F Y') }}</div>
								<div class="col"></div>
								<div class="col"></div>
								<div class="col">
									<a href="{{ route('users.contract_show', $operation->contract->id) }}" class="btn-link">
										# {{ $operation->contract->id }}
									</a>
								</div>
								<div class="col d-flex justify-content-center flex-nowrap gap-2">
									{{ $operation->amount }}
								</div>
							</x-common.tables.yellow.row>
						@endunless
					@endif
				@endforeach
			</x-common.tables.yellow>

			@php
				$paymentsSum = $monthOperations->where(function ($operation) {
					return $operation instanceof App\Models\Payment;
				})
				->where('status', App\Models\Payment::STATUS_PROCESSED)
				->reduce(function ($carry, $item) {
					return $carry + $item->amount->raw();
				}, 0);
				$paymentsSum = new App\ValueObjects\Amount($paymentsSum);

				$profitabilitiesSum = $monthOperations->where(function ($operation) {
					return $operation instanceof App\Models\Profitability;
				})
				->reduce(function ($carry, $item) {
					return $carry + $item->amount->raw();
				}, 0);
				$profitabilitiesSum = new App\ValueObjects\Amount($profitabilitiesSum);
			@endphp
			@if ($profitabilitiesSum->raw() > 0 || $paymentsSum->raw() > 0)
				<x-common.tables.total header="Итого за месяц">
					<x-common.tables.total.row label="Начислено" :value="$profitabilitiesSum" />
					<x-common.tables.total.row label="Выплачено" :value="$paymentsSum" />
				</x-common.tables.total>
			@endif
		</div>
	</div>
	@endforeach

	@if ($operations->isEmpty())
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
