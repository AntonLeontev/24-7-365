@extends('layouts.admin')

@section('title', 'Настройки приложения')

@section('content')
    <div class="container">
		<div class="col d-flex gap-2">
			Месяц начала выплат
			<payments-start 
				action="{{ route('settings.update') }}" 
				:settings="{{ json_encode(config('allowed-settings.payments_start')) }}"
				actual="{{ settings()->payments_start }}" 
				@toast="toast"
			></payments-start>
		</div>
        <form id="profile-organization-form" method="post" action="{{ route('settings.update') }}">
            @csrf
			
            <div class="col mt-5">
                <h2>Данные организации</h2>
            </div>


            <div class="mb-3">
                <label class="form-label">Название организации</label>
                <input class="form-control" name="organization_title" type="text" placeholder="пример: ООО Компания"
                    @isset(settings()->organization_title) value="{{ settings()->organization_title }}" @endisset>
            </div>
            <div class="mb-3">
                <label class="form-label">ИНН</label>
                <input class="form-control" name="inn" type="text" placeholder="пример: NNNNXXXXXXCC"
                    @isset(settings()->inn) value="{{ settings()->inn }}" @endisset>
            </div>
            <div class="mb-3">
                <label class="form-label">ОГРН</label>
                <input class="form-control" name="ogrn" type="text" placeholder="пример: 1026605606620"
                    @isset(settings()->ogrn) value="{{ settings()->ogrn }}" @endisset>
            </div>
            <div class="mb-3">
                <label class="form-label">КПП</label>
                <input class="form-control" name="kpp" type="text" placeholder="пример: 771401001"
                    @isset(settings()->kpp) value="{{ settings()->kpp }}" @endisset>
            </div>

            <div class="mb-3">
                <label class="form-label">Руководитель организации</label>
                <input class="form-control" name="director" type="text" placeholder="пример: Федор Павлович Шмидт"
                    @isset(settings()->director) value="{{ settings()->director }}" @endisset>
            </div>
            <div class="mb-3">
                <label class="form-label">Главный бухгалтер</label>
                <input class="form-control" name="accountant" type="text" placeholder="пример: Валерий Васильевич Учетов"
                    @isset(settings()->accountant) value="{{ settings()->accountant }}" @endisset>
            </div>


            <div class="mb-3">
                <label class="form-label">Юридический адрес</label>
                <input class="form-control" id="form-legal-address" name="legal_address" type="text"
                    placeholder="пример: ул. Труда  8 - 88 "
                    @isset(settings()->legal_address) value="{{ settings()->legal_address }}" @endisset>
                <div class="form-check">
                    <input class="form-check-input" id="identical_addresses" type="checkbox" value="">
                    <label class="form-check-label" for="flexCheckChecked">
                        Совпадает с фактическим
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Фактический адрес</label>
                <input class="form-control" id="form-actual-address" name="actual_address" type="text"
                    placeholder="пример: ул. Труда  7 - 77"
                    @isset(settings()->actual_address) value="{{ settings()->actual_address }}" @endisset>
            </div>

            <div class="mb-3">
                <label class="form-label">Расчетный счёт</label>
                <input class="form-control" name="payment_account" type="text" placeholder="40817810099910004312"
                    @isset(settings()->payment_account) value="{{ settings()->payment_account }}" @endisset>
            </div>
            <div class="mb-3">
                <label class="form-label">Корреспондентский счёт</label>
                <input class="form-control" name="correspondent_account" type="text" placeholder="40817810077710003542"
                    @isset(settings()->correspondent_account) value="{{ settings()->correspondent_account }}" @endisset>
            </div>
            <div class="mb-3">
                <label class="form-label">БИК</label>
                <input class="form-control" name="bik" type="text" placeholder="044525974"
                    @isset(settings()->bik) value="{{ settings()->bik }}" @endisset>
            </div>
            <div class="mb-3">
                <label class="form-label">Банк</label>
                <input class="form-control" name="bank" type="text" placeholder="Российский Банк"
                    @isset(settings()->bank) value="{{ settings()->bank }}" @endisset>
            </div>



            <div class="col-12">
                <button class="btn btn-primary" type="submit">сохранить</button>
            </div>

        </form>
    </div>
@endsection
