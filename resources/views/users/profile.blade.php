@extends('layouts.app')


@section('title', $user->first_name)

{{-- @section('scripts')
	@vite(['resources/sass/profile.scss'])
@endsection --}}

@section('content')
    @if ($message = Session::get('success'))
        <div class="container">
            <div class="row">


                <div class="col align-self-center">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ $message }}.
                        <button class="btn-close" data-bs-dismiss="alert" type="button" aria-label="Close"></button>
                    </div>
                </div>

            </div>
        </div>
    @endif

    @if ($errors->any())

        <div class="container-fluid">
            <div class="row">
                <div class="col align-self-center">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Ошибка!</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button class="btn-close" data-bs-dismiss="alert" type="button" aria-label="Close"></button>
                    </div>
                </div>

            </div>
        </div>

    @endif

	<x-common.h1 class="mb-13">Данные профиля</x-common.h1>
	<form action="#">
		<fieldset>
			<div class="profile">		
				<div class="card profile__data">
					<div class="card-header">Данные профиля</div>
					<div class="card-body pb-0">
						<x-common.form.input name="first_name" placeholder="ФИО или nickname" label="Не обязательно" value="{{ $user->first_name ?? '' }}" />
						<x-common.form.input name="phone" placeholder="Номер телефона" label="Не обязательно" value="{{ $user->phone ?? '' }}" />
						<x-common.form.input name="email" placeholder="e-mail" label="e-mail" value="{{ $user->email }}" />
					</div>
				</div>
				<div class="card profile__requecites">
					<div class="card-header d-flex justify-content-between align-items-center">
						Реквизиты
						<button class="btn btn-link fs-8 fs-md-7" data-bs-toggle="modal" data-bs-target="#callBack">У меня нет ИП/ООО</button>
					</div>
					<div class="card-body pb-0">
						<x-common.form.input name="inn" placeholder="ИНН" label="Не обязательно" value="{{ $user->organization->inn ?? '' }}" />
						<x-common.form.input name="kpp" placeholder="КПП" label="Не обязательно" value="{{ $user->organization->kpp ?? '' }}" />
						<x-common.form.input name="title" placeholder="Наименование" label="Не обязательно" value="{{ $user->organization?->title ?? '' }}" />
						<x-common.form.input name="bik" placeholder="БИК" label="" value="{{ $user->account?->bik ?? '' }}" />
						<x-common.form.input name="bank" placeholder="Наименование банка" label="Не обязательно" value="{{ $user->account?->bank ?? '' }}" />
						<x-common.form.input name="correspondent_account" placeholder="Корреспондентский счет" label="Не обязательно" value="{{ $user->account->correspondent_account ?? '' }}" />
						<x-common.form.input name="payment_account" placeholder="Номер счета" label="Не обязательно" value="{{ $user->account->payment_account ?? '' }}" />
					</div>
				</div>
				<div class="card profile__password">
					<div class="card-header">Сменить пароль</div>
					<div class="card-body">
						<x-common.form.input name="password" type="password" placeholder="Новый пароль" label="Придумайте новый пароль" />
						<x-common.form.input name="password_confirmation" type="password" placeholder="Повторите пароль" label="" />
					</div>
				</div>
			</div>
			<button class="profile__button btn btn-primary d-lg-block w-100 w-lg-33 mx-auto my-13" type="submit">Сохранить</button>
		</fieldset>
	</form>

	<x-common.modal modalTitle="У вас нет ИП/ООО?" id="callBack">
		<p class="fs-8 fs-md-7 mb-13">Введите номер ваш телефона, с вами свяжется работник банка и вам бесплатно помогут в оформлении ИП или ООО</p>
		<form action="" method="POST">
			@csrf
			<x-common.form.input class="mb-13" name='phone' placeholder="+7" label="Ваш номер телефона"  />
			<button class="btn btn-primary mb-2 w-100">Отправить</button>
			<x-common.form.checkbox class="fs-9 fs-md-8" label='' name="check" >
				Я согласен на <a class="text-reset" href="#">обработку персональных данных</a> и ознакомлен с <a class="text-reset" href="#">политикой конфиденциальности</a>.
			</x-common.form.checkbox>
		</form>
	</x-common.modal>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"
        integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- @vite(['resources/js/userProfile.js']) --}}
    <!--
    <blade ___scripts_2___/>
    -->
@endsection
