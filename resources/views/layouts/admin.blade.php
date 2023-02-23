<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
	@routes
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
	@yield('scripts')
</head>
<body>
	<div id="app">
		<x-header />
		
		<main class="py-4">
			@yield('content')
		</main>
		
		<toasts ref="toasts"></toasts>
	</div>
</body>
</html>