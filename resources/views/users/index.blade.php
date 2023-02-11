@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUser" type="button">
                    Create user
                </button>
            </div>
        </div>
        @foreach ($users as $user)
            <div class="row" href="{{ route('users.show', $user->id) }}">
                <div class="col">
                    <a href="{{ route('users.show', $user->id) }}">
                        {{ $user->first_name }} {{ $user->last_name }}
                    </a>
                </div>
                <div class="col">{{ $user->email }}</div>
                <div class="col">
                    @if ($user->roles->count() === 0)
                        Роль не назначена
                    @else
                        @foreach ($user->roles as $role)
                            {{ $role->name }}
                        @endforeach
                    @endif

                </div>
            </div>
        @endforeach
        {{ $users->links() }}
    </div>


    <!-- Modal -->
    <div class="modal fade" id="createUser" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
        <form 
			class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable" 
			action="{{ route('users.create') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create User</h1>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column gap-3 px-4">
					@csrf
                    <input type="email" name="email" placeholder="email" autocomplete="off">
					<input type="text" name="last_name" placeholder="Фамилия">
					<input type="text" name="first_name" placeholder="Имя*">
					<input type="text" name="patronymic" placeholder="Отчество">
					<select name="roles[]">
						<option disabled selected>Роль</option>
						@foreach (roles() as $role)
							<option value="{{ $role }}">{{ $role }}</option>
						@endforeach
					</select>
					<input type="password" name="password" placeholder="Пароль" autocomplete="off">
					<input type="password" name="password_confirmation" placeholder="Подтверждение" autocomplete="off">
					@if(! empty($errors->messages()))
						@foreach ($errors->messages() as $message)
							<div>{{ $message[0] }}</div>
						@endforeach
					@endif
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                    <button class="btn btn-primary" type="submit">Create</button>
                </div>
            </div>
        </form>
    </div>

@endsection
