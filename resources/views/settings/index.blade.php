@extends('layouts.app')

@section('scripts')
	@vite(['resources/js/settings.js'])
@endsection

@section('title', 'Настройки приложения')

@section('content')
    <div id="app" class="container">
		<div class="card mb-13">
			<div class="card-header">
				Месяц начала выплат
			</div>
			<div class="card-body">
				<div class="col-6">
					<payments-start 
						action="{{ route('settings.update') }}" 
						:settings="{{ json_encode(config('allowed-settings.payments_start')) }}"
						actual="{{ settings()->payments_start }}" 
						@toast="toast"
					></payments-start>
				</div> 
			</div>
		</div>
		<div class="card mb-13">
			<div class="card-header">Данные организации для документов</div>
			<div class="card-body">
				<form id="profile-organization-form" method="post" action="{{ route('settings.update') }}" ref="form">
					@csrf
					<x-common.form.input 
						class="mb-4 col-12"
						name="organization_title" 
						placeholder="Название организации" 
						label="пример: ООО Компания" 
						value="{!! settings()->organization_title !!}"
					/>
					<div class="row">
						<x-common.form.input 
							class="mb-4 col-12 col-lg-4"
							name="inn" 
							placeholder="ИНН" 
							label="пример: 1026605606620" 
							value="{!! settings()->inn !!}"
						/>
						<x-common.form.input 
							class="mb-4 col-12 col-lg-4"
							name="ogrn" 
							placeholder="ОГРН" 
							label="пример: 1026605606620" 
							value="{!! settings()->ogrn !!}"
						/>
						<x-common.form.input 
							class="mb-4 col-12 col-lg-4"
							name="kpp" 
							placeholder="КПП" 
							label="пример: 1026605606620" 
							value="{!! settings()->kpp !!}"
						/>
					</div>
					<div class="row">
						<x-common.form.input 
							class="mb-4 col-12 col-lg-4"
							name="director" 
							placeholder="Руководитель организации" 
							label="пример: Шмидт Ф.П." 
							value="{!! settings()->director !!}"
						/>
						<x-common.form.input 
							class="mb-4 col-12 col-lg-4"
							name="director_genitive" 
							placeholder="Руководитель в родительном падеже" 
							label="пример: Шмидтa Ф.П." 
							value="{!! settings()->director_genitive !!}"
						/>
						<x-common.form.input 
							class="mb-4 col-12 col-lg-4"
							name="accountant" 
							placeholder="Главный бухгалтер" 
							label="пример: Шмидт Ф.П." 
							value="{!! settings()->accountant !!}"
						/>
					</div>
					<div class="row">
						<x-common.form.input 
							class="mb-4 col-12 col-lg-6"
							name="legal_address" 
							placeholder="Юридический адрес" 
							label="ул. Труда  8 - 88" 
							value="{!! settings()->legal_address !!}"
						/>
						<x-common.form.input 
							class="mb-4 col-12 col-lg-6"
							name="actual_address" 
							placeholder="Фактический адрес" 
							label="ул. Труда  8 - 88" 
							value="{!! settings()->actual_address !!}"
						/>
					</div>
					<div class="row">
						<x-common.form.input 
							class="mb-4 col-12 col-lg-6"
							name="payment_account" 
							placeholder="Расчетный счёт" 
							label="40817810099910004312" 
							value="{!! settings()->payment_account !!}"
						/>
						<x-common.form.input 
							class="mb-4 col-12 col-lg-6"
							name="correspondent_account" 
							placeholder="Корреспондентский счёт" 
							label="40817810099910004312" 
							value="{!! settings()->correspondent_account !!}"
						/>
					</div>
					<div class="row">
						<x-common.form.input 
							class="mb-4 col-12 col-lg-6"
							name="bik" 
							placeholder="БИК" 
							label="044525974" 
							value="{!! settings()->bik !!}"
						/>
						<x-common.form.input 
							class="mb-4 col-12 col-lg-6"
							name="bank" 
							placeholder="Российский Банк" 
							label="044525974" 
							value="{!! settings()->bank !!}"
						/>
					</div>
					<div class="row">
						<x-common.form.input 
							class="mb-4 col-12 col-lg-6"
							name="phone" 
							placeholder="Телефон" 
							label="+7" 
							value="{!! settings()->phone !!}"
						/>
						<x-common.form.input 
							class="mb-4 col-12 col-lg-6"
							name="email" 
							placeholder="email" 
							label="email" 
							value="{!! settings()->email !!}"
						/>
					</div>

		
		
					<div class="col-12">
						<button 
							class="btn btn-primary w-100" 
							type="button"
							@click="saveSettings"
						>
							Сохранить
						</button>
					</div>
		
				</form>
			</div>
		</div>
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
    </div>
@endsection
