@extends('layouts.auth')

@section('title', 'Сброс пароля')

@section('content')
<x-auth.box>
	@if (session('status'))
		<div class="alert alert-success" role="alert">
			{{ session('status') }}
		</div>
	@endif

	<form method="POST" action="{{ route('password.email') }}">
		@csrf

		<div class="row mb-3">
			@error('email')
				<span class="text-primary fs-8" role="alert">
					{{ $message }}
				</span>
			@enderror
			<div class="col">
				<input id="email" type="email" placeholder="e-mail" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

			</div>
		</div>

		<div class="row mb-0">
			<div class="col">
				<button type="submit" class="btn btn-primary w-100">
					{{ __('Send Password Reset Link') }}
				</button>
			</div>
		</div>
	</form>
</x-auth.box>
@endsection
