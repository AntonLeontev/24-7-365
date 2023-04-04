@extends('layouts.app')

@section('title', 'Договор №' . $contract->id)

@section('content')

    <x-common.h1 class="mb-3">Договор {{ $contract->id }}</x-common.h1>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
            Информация о договоре
            <a class="btn btn-link d-none d-md-flex justify-content-center align-items-center gap-2 pt-0"
                href="{{ url()->previous() }}">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.85 11.25V5.07353H3.4275L5.93 7.28162L4.95 8.16176L0.75 4.45588L4.95 0.75L5.93 1.61471L3.4275 3.83824H11.25V11.25H9.85Z"
                        fill="#FCE301" />
                </svg>
                Вернуться назад
            </a>
        </div>
        <div class="card-body d-xl-flex flex-xl-row-reverse justify-content-between gap-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-md-4 w-xl-66 mb-4 gap-0">
                <div class="w-100">
                    <x-contract.property value="{{ $contract->amount }}" label="Тело закупа" />
                    <x-contract.property value="{{ $contract->tariff->annual_rate }}%" label="Ставка в год" />
                    <x-contract.property value="{{ $contract->tariff->title }}" label="Тариф" />
                </div>
                <div class="w-100">
                    <x-contract.property value="{{ $contract->tariff->duration }} мес." label="Срок" />
                    <x-contract.property value="{{ $contract->paid_at?->translatedFormat('d M Y') }}" label="Начало" />
                    <x-contract.property
                        value="{{ $contract->paid_at?->addMonths($contract->tariff->duration)->translatedFormat('d M Y') }}"
                        label="Завершение" />
                </div>
            </div>
            <ul class="contract-list w-xl-33">
				<li>
					<span>
						Объем вклада от <span class="text-nowrap">{{ $contract->tariff->min_amount }}</span>
						@if ($contract->tariff->max_amount->raw() > 0)
							до <span class="text-nowrap">{{ $contract->tariff->max_amount }}</span>
						@endif
					</span> 
				</li>
			
				<li>
					Тело вклада в конце срока
				</li>
				<li>
					@if ($contract->tariff->getting_profit === $contract->tariff::MONTHLY)
						Доходность ежемесячно
					@else
						Доходность в конце срока
					@endif
				</li>
            </ul>
            <a class="btn btn-link d-flex d-md-none justify-content-center align-items-center gap-2"
                href="{{ url()->previous() }}">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.85 11.25V5.07353H3.4275L5.93 7.28162L4.95 8.16176L0.75 4.45588L4.95 0.75L5.93 1.61471L3.4275 3.83824H11.25V11.25H9.85Z"
                        fill="#FCE301" />
                </svg>
                Вернуться назад
            </a>
        </div>
    </div>

	@if($contract->isChanging())
		<div class="card">
			<div class="p-4 pb-5">
                    <div class="bg-body text-light p-5 text-center">
						@if ($contract->contractChanges->last()->status === 2)
							<p>
								Запрошены изменения в договоре. Для подтверждения нужно <a class="btn-link" href="{{ route('invoice.pdf', $contract->payments->where('type', 1)->where('status', 0)->last()->id) }}">оплатить счет</a>
							</p>
							<p class="mb-0"><a class="btn-link" href="{{ route('contracts.cancel_change', $contract->id) }}">Отменить изменения</a></p>
						@elseif($contract->contractChanges->last()->status === 3)
							<p class="mb-0">Изменения будут применены {{ $contract->periodEnd()->format('d.m.Y') }}</p>
						@endif
                    </div>
                </div>
		</div>
	@endif

	@if (auth()->id() === $contract->user_id)
		<div class="card mb-4">
			<div class="card-body d-flex justify-content-between flex-xl-nowrap flex-wrap gap-3">
				@if ($contract->status !== $contract::ACTIVE || $contract->isChanging())
					<button class="btn btn-outline-primary d-flex justify-content-center align-items-center w-100 order-xl-1 gap-2" disabled>
						<svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M8.72741 0.875L8.10692 1.51746L9.99438 3.42831H0.712203V4.32123H9.99438L8.10736 6.23253L8.72741 6.87454L11.6875 3.87477L8.72741 0.875ZM3.27391 7.12546L0.3125 10.1252L3.27391 13.125L3.89352 12.483L2.00694 10.5717H11.2887V9.67877H2.0065L3.89352 7.76747L3.27391 7.12546Z"
								fill="#FCE301" stroke="#FCE301" stroke-width="0.3" />
						</svg>
						Изменение тарифа или суммы закупа
					</button>
				@else
					<a href="{{ route('contracts.edit', $contract->id) }}"
						class="btn btn-outline-primary d-flex justify-content-center align-items-center w-100 order-xl-1 gap-2"
					>
						<svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M8.72741 0.875L8.10692 1.51746L9.99438 3.42831H0.712203V4.32123H9.99438L8.10736 6.23253L8.72741 6.87454L11.6875 3.87477L8.72741 0.875ZM3.27391 7.12546L0.3125 10.1252L3.27391 13.125L3.89352 12.483L2.00694 10.5717H11.2887V9.67877H2.0065L3.89352 7.76747L3.27391 7.12546Z"
								fill="#FCE301" stroke="#FCE301" stroke-width="0.3" />
						</svg>
						Изменение тарифа или суммы закупа
					</a>
				@endif
				<button class="btn btn-outline-primary d-flex justify-content-center align-items-center w-100 w-md-48 gap-2"
					data-bs-toggle="modal" data-bs-target="#contractText">
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_223_3926)">
							<path
								d="M1.75 13.0343V0.970937C1.75 0.868829 1.79148 0.770903 1.86533 0.698701C1.93917 0.6265 2.03932 0.585938 2.14375 0.585938H9.79037C9.89477 0.586027 9.99485 0.626649 10.0686 0.698871L12.1345 2.71884C12.1712 2.75471 12.2003 2.79732 12.2202 2.84424C12.24 2.89115 12.2501 2.94143 12.25 2.99219V13.0343C12.25 13.0848 12.2398 13.1349 12.22 13.1816C12.2002 13.2283 12.1712 13.2708 12.1347 13.3065C12.0981 13.3423 12.0547 13.3706 12.0069 13.39C11.9592 13.4093 11.908 13.4193 11.8562 13.4193H2.14375C2.09204 13.4193 2.04084 13.4093 1.99307 13.39C1.9453 13.3706 1.90189 13.3423 1.86533 13.3065C1.82876 13.2708 1.79976 13.2283 1.77997 13.1816C1.76018 13.1349 1.75 13.0848 1.75 13.0343Z"
								stroke="#FCE301" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<path
								d="M8.75 0.875V3.85C8.75 3.98924 8.80531 4.12277 8.90377 4.22123C9.00223 4.31969 9.13576 4.375 9.275 4.375H12.25"
								stroke="#FCE301" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</g>
						<defs>
							<clipPath id="clip0_223_3926">
								<rect width="14" height="14" fill="white" />
							</clipPath>
						</defs>
					</svg>
					Посмотреть договор
				</button>
				<button
					class="btn btn-outline-primary d-flex justify-content-center align-items-center w-100 w-md-48 order-xl-2 gap-2"
					data-bs-toggle="modal" data-bs-target="#cancelContract">
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_223_3922)">
							<path
								d="M10.7503 12.0012L7 8.25091L3.24966 12.0012L1.99955 10.7511L5.74989 7.00079L1.99955 3.25045L3.24966 2.00034L7 5.75068L10.7503 2.00034L12.0005 3.25045L8.25011 7.00079L12.0005 10.7511L10.7503 12.0012Z"
								fill="#FCE301" />
						</g>
						<defs>
							<clipPath id="clip0_223_3922">
								<rect width="14" height="14" fill="white" />
							</clipPath>
						</defs>
					</svg>
					Расторгнуть договор
				</button>
			</div>
		</div>
	@endif
	
	@if (app()->isLocal())
		<div class="card mb-4">
			<div class="card-header">
				УДАЛИТЬ
			</div>
			<div class="card-body">

				<a class="btn btn-outline-danger" href="{{ route('pay-in', $contract->id) }}">Pay-in</a>
				<a class="btn btn-outline-danger" href="{{ route('period', $contract->id) }}">Period</a>
			</div>
		</div>
	@endif

    <div class="card mb-4">
        <div class="card-header">График выплат по договору</div>
        <div class="card-body">
            @if ($operations->count() > 0)
                <x-common.tables.yellow class="mb-4">
                    <x-slot:header>
                        <div class="col">Дата</div>
                        <div class="col">Тело закупа</div>
                        <div class="col">Начислено</div>
                        <div class="col">К выплате</div>
                    </x-slot:header>

                    @foreach ($operations as $operation)
                        @if ($operation instanceof App\Models\Profitability)
                            <x-common.tables.yellow.row>
                                <div class="col">{{ $operation->planned_at->translatedFormat('d F Y') }}</div>
                                <div class="col">{{-- Тело закупа на дату выплаты --}}</div>
                                <div class="col">+{{ $operation->amount }}</div>
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
                            <x-common.tables.yellow.row>
                                <div class="col">{{ $operation->planned_at->translatedFormat('d F Y') }}</div>
                                <div class="col">{{ $contract->amount }}</div>
                                <div class="col"></div>
                                <div class="col d-flex justify-content-center flex-nowrap gap-2">
                                    @if ($operation->status === $operation::STATUS_PROCESSED)
                                        <svg width="17" height="12" viewBox="0 0 17 12" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6.15251 11.3346L0.703125 6.2785L2.06547 5.01447L6.15251 8.80657L14.9241 0.667969L16.2865 1.932L6.15251 11.3346Z"
                                                fill="#60FC01" />
                                        </svg>
                                    @endif
                                    {{ $operation->amount }}
                                </div>
                            </x-common.tables.yellow.row>
                        @endif
                    @endforeach
                </x-common.tables.yellow>
                <x-common.tables.total header="Итого за период:">
                    <x-common.tables.total.row label="Всего начислено доходности" :value="$totalProfitabilities" />
                    <x-common.tables.total.row label="Всего выплат" :value="$totalPayments" />
                </x-common.tables.total>
            @else
                <div class="p-4 pb-5">
                    <div class="bg-body text-light p-5 text-center">
                        <p>
                            Пока нет начислений или выплат.
                        </p>
                        @if ($contract->status === $contract::PENDING)
                            <p>
                                Для активации нужно оплатить <a class="btn-link"
                                    href="{{ route('invoice.pdf', $contract->payments->first()->id) }}">счет</a>
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    <x-common.modal id="cancelContract" modalTitle="Закрыть договор №{{ $contract->id }}">
        <div class="mb-5">
            @if ($contract->status === $contract::PENDING)
                Неоплаченный договор будет удален
            @else
                <p class="text-light">
                    Срок действия договора истекает
                    {{ $contract->paid_at?->addMonths($contract->tariff->duration)->translatedFormat('d.m.Y') }}
                </p>
                <p class="text-light">Если вы досрочно расторгните договор ВСТАВИТЬ ТЕКСТ</p>
            @endif
        </div>
        <div class="d-flex flex-column flex-lg-row gap-3">
            <a class="btn btn-primary w-100" href="{{ route('contracts.cancel', $contract->id) }}">
                @if ($contract->status === $contract::PENDING)
                    Удалить
                @else
                    Расторгнуть
                @endif
            </a>
            <button class="btn btn-outline-primary w-100" data-bs-dismiss="modal" type="button">
                Отмена
            </button>
        </div>
    </x-common.modal>
    <div class="modal" id="contractText" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pe-3 fs-md-4">Текст договора</h5>
                    <div class="d-flex gap-3">
                        <a class="btn btn-outline-primary d-none d-lg-block" href="{{ route('users.contract.pdf') }}">
                            Скачать договор в PDF
                        </a>
                        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="contract-text text-bg-dark vh-70 overflow-auto px-3 py-3">
                        @include('pdf.contract.text')
                    </div>
                    <a class="btn btn-outline-primary w-100 d-lg-none mt-4" href="{{ route('users.contract.pdf') }}">
                        Скачать договор в PDF
                    </a>
                </div>
            </div>
        </div>
    </div>



@endsection
