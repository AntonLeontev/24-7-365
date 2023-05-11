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
	@routes()
</head>
<body>
	<div class="auth__wrap" id="app">
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
