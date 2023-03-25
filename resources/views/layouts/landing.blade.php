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
    	
    	    <main class="col mt-4 px-xxl-5">
    		@yield('content')
    		</main>
    		
    		
    	</div>
	</div>	
		
	
</body>
</html>
