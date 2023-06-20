<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="none"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/sass/landing.scss'])
	@yield('scripts')

	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="favicon180.png">
	<link rel="manifest" href="manifest.webmanifest">

	@include('layouts.part.openGraph')

	<link rel="prefetch" href="{{ Vite::asset('resources/sass/app.scss') }}" as="style">
	<link rel="prefetch" href="{{ Vite::asset('resources/js/app.js') }}" as="script">
</head>
<body>
	@yield('content')
	<div class="container mb-3">
		<a href="{{ url()->previous() }}" class="btn btn-outline-primary">Назад</a>
	</div>
</body>
</html>
