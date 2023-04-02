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
</head>
<body>
	<div class="container-fluid">
    	<div class="row">
    	    <main class="col vh-100 vw-100 d-flex flex-column justify-content-center align-items-center">
				<div class="fs-3">
					@yield('code') | @yield('message')
				</div>
				<br>
				<a href="/" class="btn btn-link">Перейти на главную</a>
    		</main>
    	</div>
	</div>	
</body>
</html>
