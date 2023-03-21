@extends('layouts.app')

@section('title', 'Изменить договор')

@section('scripts')
	@vite(['resources/js/editContract.js'])
@endsection

@section('content')
	<x-common.h1 class="mb-13">Смена тарифа по договору {{ $contract->id }}</x-common.h1>
	<div id="app">
		<edit-contract
			:contract="{{ json_encode($contract) }}"
			:tariffs="{{ json_encode(more_profitable_tariffs($contract->tariff->annual_rate)) }}"
		></edit-contract>
	</div>
@endsection
