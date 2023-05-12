<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/sass/landing.scss', 'resources/js/landing.js'])
	@yield('scripts')

	<link rel="icon" href="/favicon.ico" type="image/x-icon">

	<link rel="prefetch" href="{{ Vite::asset('resources/sass/app.scss') }}" as="style">
	<link rel="prefetch" href="{{ Vite::asset('resources/js/app.js') }}" as="script">
</head>
<body>
	@yield('content')
	<script src="/js/jquery.min.js"></script>
	<script src="/js/slick.min.js"></script>
</body>
</html>
