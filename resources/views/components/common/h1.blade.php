<div {{ $attributes->class(['heading'])->merge() }}>
    <h1 class="heading-h1">{{ $slot }}</h1>
	<picture>
		<source srcset="{{ Vite::asset('resources/images/h1-bg.webp') }}">
		<img class="heading-image" src="{{ Vite::asset('resources/images/h1-bg.png') }}" alt="Фоновое изображение">
	</picture>
</div>
