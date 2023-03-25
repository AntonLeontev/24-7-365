@extends('layouts.landing')

@section('title', 'Главная')

@section('content')
<a href="{{ route('login') }}" class="btn btn-primary">Login</a>
	<div class="container">
		<div class="row">
			<div class="col">Годовой %</div>
			<div class="col">Срок</div>
			<div class="col">Минимальная сумма</div>
			<div class="col">Максимальная сумма</div>
		</div>
		@php
			$title = '';
		@endphp
		@foreach ($tariffs as $tariff)
			@if ($title !== $tariff->title)
				<div class="row"><div class="col offset-1">{{ $tariff->title }}</div></div>
				@php($title = $tariff->title)
			@endif
			<div class="row">
				<div class="col">{{ $tariff->annual_rate }}</div>
				<div class="col">{{ $tariff->duration }} мес</div>
				<div class="col">{{ $tariff->min_amount }}</div>
				<div class="col">
					@if ($tariff->max_amount->raw() === 0)
						Не ограничена
					@else
						{{ $tariff->max_amount }}
					@endif
				</div>
			</div>
			
		@endforeach
	</div>
@endsection
