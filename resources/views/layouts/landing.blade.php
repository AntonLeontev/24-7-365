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
</head>
<body>
	@yield('content')
	<script src="/js/jquery.min.js"></script>
	<script src="/js/slick.min.js"></script>
</body>
</html>
