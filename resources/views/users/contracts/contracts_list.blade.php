@extends('layouts.app')

@section('title', 'Активные договоры')

@section('content')
	<x-common.h1 class="">Активные договоры</x-common.h1>

	<div class="card my-13">
		<div class="card-header d-flex justify-content-between">
			Список договоров
			<div class="d-none d-lg-flex gap-2">
				@if($contracts->count() > 0)
					<a class="btn btn-outline-primary" href="{{ route('payments.for_user') }}">График платежей</a>
				@endif
				<a class="btn btn-primary d-flex align-items-center justify-content-center gap-2"
					href="{{ route('users.add_contract') }}">
					Заключить договор
					<svg width="11" height="12" viewBox="0 0 11 12" fill="none"
						xmlns="http://www.w3.org/2000/svg">
						<path d="M10.75 6.75H6.25V11.25H4.75V6.75H0.25V5.25H4.75V0.75H6.25V5.25H10.75V6.75Z"
							fill="#202022" />
					</svg>
				</a>
			</div>
		</div>
		@if($contracts->count() === 0)
		<div class="p-4 pb-5">
			<div class="p-5 bg-body text-center text-light">
				<p>
					На данный момент нет активных договоров. 
				</p>
				<p>
					Но вы можете <a class="btn-link" href="{{ route('users.add_contract') }}">заключить новый</a>
				</p> 
			</div>
		</div>
		@else
			<div class="card-body card-body_table">
				<x-common.tables.dark>
					<x-slot:header>
						<div class="col">Номер договора</div>
						<div class="col">Тариф</div>
						<div class="col">Сумма</div>
						<div class="col">Статус</div>
					</x-slot:header>

					@foreach ($contracts as $contract)
						<x-common.tables.dark.row>
							<div class="col">
								<a class="btn-link"
									href="{{ route('users.contract_show', $contract->id) }}">№{{ $contract->id }}</a>
							</div>
							<div class="col">{{ $contract->tariff->title }} - {{ $contract->tariff->duration }} мес.</div>
							<div class="col">{{ $contract->amount }}</div>
							<div class="col">
								@if (
									$contract->paid_at &&
									$contract->prolongate &&
									$contract->status !== contract_status('canceled') &&
									$contract->status !== contract_status('terminated') &&
									$contract->end()->subMonths(2)->lte($contract->paid_at->addMonths($contract->duration()))
								)
									<button 
										class="btn btn-link ps-0"
										data-bs-toggle="modal" 
										data-bs-target="#contract_prolongation{{ $contract->id }}" 
									>
										Автопродление
									</button> 
								@else
									{{ $contract->status->getName() }}
								@endif
							</div>
						</x-common.tables.dark.row>
					@endforeach
				</x-common.tables.dark>                        
			</div>
		@endempty
	</div>
	<div class="card d-lg-none">
		<div class="card-body pt-0">
			<a class="btn btn-primary w-100 d-flex align-items-center justify-content-center mb-4 gap-2"
				href="{{ route('users.add_contract') }}">
				Заключить договор
				<svg width="11" height="12" viewBox="0 0 11 12" fill="none"
					xmlns="http://www.w3.org/2000/svg">
					<path d="M10.75 6.75H6.25V11.25H4.75V6.75H0.25V5.25H4.75V0.75H6.25V5.25H10.75V6.75Z"
						fill="#202022" />
				</svg>
			</a>
			<a class="btn btn-outline-primary w-100" href="{{ route('payments.for_user') }}">График платежей</a>
		</div>
	</div>

	@foreach ($contracts as $contract)
		@if(
			$contract->paid_at &&
			$contract->prolongate && 
			$contract->end()->subMonths(2)->lte($contract->paid_at->addMonths($contract->duration()))
		)
		<x-common.modal modalTitle="Продлить договор" id="contract_prolongation{{ $contract->id }}">
			<p>
				{{ $contract->end()->translatedFormat('d F Y г.') }} договор будет автоматически продлен на тех же условиях. Будет выплачена только доходность.
			</p>
			<p>
				Можно отменить автоматическое продление
			</p>
			<div class="d-flex flex-nowrap gap-3">
				<button class="btn-primary btn w-50" data-bs-dismiss="modal" type="button">Продлить автоматически</button>
				<a class="btn-outline-primary btn w-50" href="{{ route('contracts.cancel.prolongation', $contract->id) }}">
					Отменить продление
				</a>
			</div>
		</x-common.modal>
		@endif
	@endforeach

@endsection
