@extends('layouts.landing')

@section('title', 'Главная')

@section('content')
	<div class="container">
		<div class="position-absolute top-50 start-50 translate-middle">
			@auth ()
				<a href="{{ route('income_calculator') }}" class="btn btn-primary me-13">Калькулятор</a>
			@endif

			@guest
				<a href="{{ route('login') }}" class="btn btn-primary me-13">Войти</a>
				<a href="{{ route('register') }}" class="btn btn-primary">Регистрация</a>
			@endguest
		</div>
	</div>
@endsection
