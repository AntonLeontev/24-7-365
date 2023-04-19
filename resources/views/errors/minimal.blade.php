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
    	    <main class="col min-vh-100 vw-100 d-flex flex-column justify-content-center align-items-center">
				<div class="bg-primary w-100 w-md-auto w-xl-75 p-md-121">
					<div class="bg-secondary d-md-flex justify-content-between">
						<div class="p-11 p-md-13">
							<div class="error-code text-primary">
								@yield('code')
							</div>
							<div class="error-message text-uppercase">
								@yield('message')
							</div>
							<p class="text-light">
								@yield('text')
							</p>
							<a href="{{ route('home') }}" class="btn btn-primary">Перейти на главную</a>
						</div>
						<div class="d-none d-md-flex align-items-end">
							<img src="{{ Vite::asset('resources/images/error-boxes.png') }}" alt="Коробки" class="img-fluid">
						</div>
					</div>
				</div>
    		</main>
    	</div>
	</div>	
</body>
</html>
