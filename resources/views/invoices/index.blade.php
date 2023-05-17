@extends('layouts.app')

@section('scripts')
	@vite(['resources/js/invoices.js'])
@endsection

@section('title', 'Счета')

@section('content')

	<div id="app">
		{{-- <x-common.h1 class="mb-121">Выставленные счета</x-common.h1> --}}
		<invoices></invoices>
	</div>

    

@endsection
