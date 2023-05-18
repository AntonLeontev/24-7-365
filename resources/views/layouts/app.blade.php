<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
	@routes
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
	@yield('scripts')

	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="favicon180.png">
	<link rel="manifest" href="manifest.webmanifest">

	<meta property="og:title" content="@yield('title') | {{ config('app.name', 'Laravel') }}"/>
	<meta property="og:type" content="website" />
	<meta property="og:url" content="{{ request()->url() }}" />
	<meta property="og:image" content="{{ env('APP_URL') }}/ogimage.jpg"/>
</head>
<body>
	<div class="application">
		@include('layouts.part.sidebar_menu')
		<div class="container-fluid">
			<div class="row">
				@include('layouts.part.header')
			
				<main class="mt-4 px-xxl-110 px-xxxl-120">
					@yield('content')
				</main>
			</div>
		</div>	
		@include('layouts.part.footer')
	</div>
</body>
</html>
