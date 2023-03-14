@extends('layouts.app')

@section('title', 'Активные договоры')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <x-common.h1 class="mb-4">Активные договоры</x-common.h1>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        Список договоров
                        <div class="d-none d-lg-flex gap-2">
                            <a class="btn btn-outline-primary" href="{{ route('payments.for_user') }}">График платежей</a>
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
					@empty($contracts)
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
										<div class="col">{{ $contract->tariff->title }}</div>
										<div class="col">{{ $contract->amount }}</div>
										<div class="col">
											@if ($contract->status === $contract::ACTIVE)
												Активный
											@elseif($contract->status === $contract::PENDING)
												Ожидает оплаты
											@elseif($contract->status === $contract::CANCELED)
												На расторжении
											@elseif($contract->status === $contract::TERMINATED)
												Отменен пользователем
											@elseif($contract->status === $contract::FINISHED)
												Завершен
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
            </div>
        </div>
    </div>

@endsection
