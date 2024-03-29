<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
	@yield('scripts')

	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="favicon180.png">
	<link rel="manifest" href="manifest.webmanifest">
	
	@include('layouts.part.openGraph')
</head>
<body>
	<div class="auth__wrap" id="app">
		@if (Session::has('email_is_null'))
			<div class="text-bg-primary p-3 text-center">В профиле не указан email. Добавьте email в профиль или зарегистрируйтесь через форму</div>
		@endif
		<div class="container-fluid min-vh-100">
			<div class="d-flex justify-content-center align-items-center min-vh-100">
				@yield('content')
			</div>
		</div>
		<picture>
			<source srcset="{{ Vite::asset('resources/images/firstscreen.webp') }}">
			<img class="auth__image" src="{{ Vite::asset('resources/images/firstscreen.jpg') }}" alt="Фон">
		</picture>
	</div>
</body>
</html>
