@extends('layouts.auth')

@section('title', 'Сбросить пароль')

@section('content')
<x-auth.box>
	<form method="POST" action="{{ route('password.update') }}">
		@csrf

		<input type="hidden" name="token" value="{{ $token }}">

		<div class="row mb-3">
			<div class="col">
				@error('email')
					<span class="fs-8 text-primary" role="alert">
						{{ $message }}
					</span>
				@enderror
				
				<input id="email" type="email" placeholder="e-mail" class="form-control" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
			</div>
		</div>

		<div class="row mb-3">
			<div class="col">
				@error('password')
					<span class="fs-8 text-primary" role="alert">
						{{ $message }}
					</span>
				@enderror
				
				<input id="password" type="password" placeholder="Новый пароль" class="form-control" name="password" required autocomplete="new-password">
			</div>
		</div>

		<div class="row mb-3">
			<div class="col">
				<input id="password-confirm" type="password" placeholder="Повторите пароль" class="form-control" name="password_confirmation" required autocomplete="new-password">
			</div>
		</div>

		<div class="row mb-0">
			<div class="col">
				<button type="submit" class="btn btn-primary w-100">
					{{ __('Reset Password') }}
				</button>
			</div>
		</div>
	</form>
</x-auth.box>
@endsection
