@extends('layouts.app')

@section('title')
	@empty($user->first_name)
		Профиль
	@else
		{{ $user->first_name }}
	@endif
@endsection

@section('scripts')
	@vite(['resources/js/userProfile.js'])
@endsection


@section('content')

    <x-common.h1 class="mb-13">Данные профиля</x-common.h1>
    @if ($user->is_blocked)
        <div class="border border-primary border-2 p-13 d-flex flex-column justify-content-center align-items-center mb-13">
			<span class="fs-4 text-uppercase">Пользователь был заблокирован {{ $user->updated_at->format('d.m.Y') }}</span> 
			<button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#blockUser" type="button">
				Разблокировать пользователя
			</button>
		</div>
    @endif
    <div id="profile-form" class="mb-13">
        <form data-phone="{{ $user->phone ?? '' }}"
            action="{{ route('users.profile.save', auth()->id()) }}" method="POST"
            ref="profileForm">
            <div class="profile">
                
                    <div class="card profile__data">
                        <div class="card-header">Данные профиля</div>
						<fieldset @disabled(auth()->id() !== $user->id)>
							<div class="card-body pb-0">
								<input-component name="first_name" value="{{ $user->first_name ?? '' }}"
									placeholder="ФИО или nickname" label="Не обязательно" :error="errors.first_name" 
									@clear-error="(name) => (errors[name] = null)">
								</input-component>
								<input-component name="phone" value="{{ $user->phone ? '+' . $user->phone : '' }}"
									placeholder="Номер телефона" label="Не обязательно" :error="errors.phone" 
									@clear-error="(name) => (errors[name] = null)">
								</input-component>
								<input-component name="email" value="{{ $user->email }}" placeholder="e-mail"
									label="e-mail" :error="errors.email" 
									@clear-error="(name) => (errors[name] = null)">
								</input-component>
							</div>
						</fieldset>
                    </div>
                    <div class="card profile__requecites">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Реквизиты
                            @if (auth()->id() === $user->id)
                                <button class="btn btn-link fs-8 fs-md-7 pb-0" data-bs-toggle="modal"
                                    data-bs-target="#callBack" type="button" tabindex="-1">
                                    У меня нет ИП / ООО
                                </button>
                            @endif
                        </div>
						<fieldset @disabled(auth()->id() !== $user->id)>
							<div class="card-body">
								<input-component id="inn" name=inn 	value="{{ $user->organization->inn ?? '' }}"
									placeholder="ИНН" label="Не обязательно" :error="errors.inn">
								</input-component>
								<input-component id="kpp" name="kpp" value="{{ $user->organization->kpp ?? '' }}"
									tabindex="-1" placeholder="КПП" label="Не обязательно" :error="errors.kpp" readonly >
								</input-component>
								<input-component id="title" name="title"
									value="{{ $user->organization?->title ?? '' }}" tabindex="-1"
									placeholder="Название организации" label="Не обязательно" :error="errors.title" readonly>
								</input-component>
								<input-component id="bik" name="bik" value="{{ $user->account?->bik ?? '' }}"
									placeholder="БИК" :error="errors.bik">
								</input-component>
								<input-component id="bank" name="bank" value="{{ $user->account?->bank ?? '' }}"
									tabindex="-1" placeholder="Наименование банка" label="Не обязательно" readonly  :error="errors.bank">
								</input-component>
								<input-component id="correspondent_account" name="correspondent_account"
									value="{{ $user->account->correspondent_account ?? '' }}" tabindex="-1"
									placeholder="Корреспондентский счет" label="Не обязательно" :error="errors.correspondent_account" readonly>
								</input-component>
								<input-component name="payment_account" value="{{ $user->account->payment_account ?? '' }}"
									placeholder="Расчетный счет" label="Не обязательно" :error="errors.payment_account" 
									@clear-error="(name) => (errors[name] = null)">
								</input-component>
								<input id="ogrn" name="ogrn" type="hidden"
									value="{{ $user->organization->ogrn ?? '' }}">
								<input id="address" name="legal_address" type="hidden"
									value="{{ $user->organization->legal_address ?? '' }}">
								<input id="director" name="director" type="hidden"
									value="{{ $user->organization->director ?? '' }}">
								<input id="directors_post" name="directors_post" type="hidden"
									value="{{ $user->organization->directors_post ?? '' }}">
							</div>
						</fieldset>
                    </div>
                @if (auth()->id() === $user->id)
                    <div class="card profile__password">
                        <div class="card-header">Сменить пароль</div>
                        <div class="card-body">
							<input-component name="password" type="password" placeholder="Новый пароль"
                                label="Придумайте новый пароль" :error="errors.password" 
								@clear-error="(name) => (errors[name] = null)">
							</input-component>
							<input-component name="password_confirmation" type="password" placeholder="Повторите пароль"
                                label="" :error="errors.password_confirmation" 
								@clear-error="(name) => (errors[name] = null)">
							</input-component>
                        </div>
                    </div>
                @else
                    <div class="card profile__role">
                        <div class="card-body">
                            <div class="role-content">
                                <div class="fs-4">
                                    Роль на платформе:
                                    <span class="text-primary">{{ $user->getRoleNames()->first() }}</span>
                                </div>
                                <div>
									@can('assign roles')
										<button class="btn btn-link ps-0" data-bs-toggle="modal" data-bs-target="#changeRole" type="button">
											Сменить роль
										</button>
									@endcan
									@can('block users')
										<button class="btn btn-link ps-0" data-bs-toggle="modal" data-bs-target="#blockUser" type="button">
											@if ($user->is_blocked)
												Разблокировать пользователя
											@else
												Заблокировать пользователя
											@endif
										</button>
									@endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            @if (auth()->id() === $user->id)
                <button class="btn btn-primary d-lg-block w-100 w-lg-33 my-13 position-relative mx-auto"
                    id="profile-save-button" type="submit" @click.prevent="handleForm">
                    Сохранить
                    <div class="position-absolute top-50 translate-middle-y" v-cloak v-show="spinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </button>
            @endif

        </form>
		<Transition>
			<div class="notice" v-cloak v-show="notice">
				<span v-text="message"></span>
				<button class="btn" type="button" aria-label="Закрыть" @click="hideNotice">
					<svg width="14" height="14" viewBox="0 0 14 14" fill="none"
						xmlns="http://www.w3.org/2000/svg">
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
				</button>
			</div>
		</Transition>
		<smscode
			:phone="this.newPhone ?? ''"
			:errors="errors"
			@interface="(smscodeInterface) => ($options.smscodeInterface = smscodeInterface)"
			@errors="(response) => handleErrors(response)"
			@notify="(message, delay) => notify(message, delay)"
			@phone-is-confirmed="(phone) => phoneIsConfirmed(phone)"
		></smscode>
    </div>

	@can('see other profiles')
		<div class="card mb-13">
			<div class="card-header">Общая статистика</div>
			<div class="card-body">
				<ul class="statistics">
					<li class="statistics__item">
						<span class="item__label">Количество договоров</span>
						<span class="item__value">{{ $user->contracts->count() }}</span>
					</li>
					<li class="statistics__item">
						<span class="item__label">Общая сумма закупа</span>
						<span class="item__value">{{ $user->contractsAmount() }}</span>
					</li>
				</ul>
			</div>
		</div>

		<div class="card mb-13">
			<div class="card-header">Список договоров пользователя</div>
			<div class="card-body">
				<x-common.tables.dark>
					<x-slot:header>
						<div class="col">Номер договора</div>
						<div class="col">Тариф</div>
						<div class="col">Сумма</div>
						<div class="col">Статус</div>
					</x-slot:header>

					@foreach ($user->contracts->load('tariff') as $contract)
						<x-common.tables.dark.row>
							<div class="col">
								<a class="btn-link"
									href="{{ route('users.contract_show', $contract->id) }}">№{{ $contract->id }}</a>
							</div>
							<div class="col">{{ $contract->tariff->title }}</div>
							<div class="col">{{ $contract->amount }}</div>
							<div class="col">{{ $contract->status->getName() }}</div>
						</x-common.tables.dark.row>
					@endforeach
				</x-common.tables.dark>
			</div>
		</div>
	@endcan

	{{-- <x-common.modal id="callBack" modalTitle="У вас нет ИП / ООО?">
		<p class="fs-8 fs-md-7 mb-13">Введите номер телефона, с вами свяжется работник банка и вам бесплатно помогут в
			оформлении ИП или ООО</p>
		<form action="" method="POST">
			@csrf
			<x-common.form.input class="mb-13" name='phone' placeholder="+7" label="Ваш номер телефона" />
			<button class="btn btn-primary w-100 mb-2">Отправить</button>
			<x-common.form.checkbox class="fs-9 fs-md-8" name="check" label=''>
				Я согласен на <a class="text-reset" href="#">обработку персональных данных</a> и ознакомлен с <a
					class="text-reset" href="#">политикой конфиденциальности</a>.
			</x-common.form.checkbox>
		</form>
	</x-common.modal> --}}
	<x-common.openCompany id="callBack" />

	@can('block users')
	@php
		$title = $user->is_blocked ? 
			"Восстановление пользователя" :
			"Блокировка пользователя"; 
		$text = $user->is_blocked ? 
			"восстановить" :
			"заблокировать"; 
		$action = $user->is_blocked ? 
			route('users.unblock', $user->id) :
			route('users.block', $user->id); 
	@endphp
		<x-common.modal id="blockUser" :modalTitle="$title">
			<form action="{{ $action }}" method="post">
				@csrf
				<p>Вы уверены, что хотите {{ $text }} пользователя {{ $user->first_name }}?</p>
				<button class="btn btn-primary w-100" type="submit">
					<span class="text-capitalize">{{ $text }}</span> пользователя
				</button>
			</form>
		</x-common.modal>
	@endcan
	@can('assign roles')
		<x-common.modal id="changeRole" modalTitle="Сменить роль пользвателя" overflow="overflow-visible">
			<form action="{{ route('users.update-role', $user->id) }}" method="post">
				@csrf
				<p>Выберете какая роль должна быть у пользователя {{ $user->first_name }} на платформе</p>
				<x-common.form.select name="roles[]" class="mb-13">
					<option disabled selected>Роль</option>
					@foreach (roles() as $role)
						<option value="{{ $role }}" @if ($user->getRoleNames()->first() === $role) selected @endif>
							{{ $role }}
						</option>
					@endforeach
				</x-common.form.select>
				<button class="btn btn-primary w-100" type="submit">
					Сохранить
				</button>
			</form>
		</x-common.modal>
	@endcan
@endsection
