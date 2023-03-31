@extends('layouts.app')


@section('title', 'Калькулятор доходов')

@section('scripts')
	@vite(['resources/js/calculator.js'])
@endsection


@section('content')

<x-common.h1 class="mb-13">Калькулятор доходов</x-common.h1>

<div id="app">
	<calculator :tariffs="{{ json_encode(tariffs()) }}"></calculator>
</div>

@endsection
