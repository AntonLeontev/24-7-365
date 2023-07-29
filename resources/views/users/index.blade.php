@extends('layouts.app')

@section('scripts')
	@vite(['resources/js/users.js'])
@endsection

@section('title', 'Пользователи')

@section('content')

	<div id="app">
		<users @toast="toast"></users>
	</div>

    <!-- Modal -->
	@can('create users')
		<div class="modal fade" id="createUser" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
			<form 
				class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" 
				action="{{ route('users.create') }}" method="POST">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Создание пользователя</h1>
						<button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
					</div>
					<div class="modal-body d-flex flex-column gap-3 px-4">
						@csrf
						<div class="create-user-form">
							<x-common.form.input class="name" label='ФИО или Nickname' name="first_name" required />
							<x-common.form.input class="email" label='email' name="email" type="email" required />
							<x-common.form.input class="phone" label='Телефон' name="phone" type="phone" />
							<x-common.form.input class="pass" label='Пароль' name="password" type="password" required />
							<x-common.form.input class="conf" label='Подтверждение' name="password_confirmation" type="password" required />
							<x-common.form.select class="role" name="roles[]">
								<option disabled selected>Роль</option>
								@foreach (roles() as $role)
								<option value="{{ $role }}">{{ $role }}</option>
								@endforeach
							</x-common.form.select>
						</div>
						@if(! empty($errors->messages()))
							@foreach ($errors->messages() as $message)
								<div>{{ $message[0] }}</div>
							@endforeach
						@endif
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary w-100" type="submit">Создать</button>
					</div>
				</div>
			</form>
		</div>
	@endcan

@endsection
