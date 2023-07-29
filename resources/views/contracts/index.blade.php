@extends('layouts.app')

@section('title', 'Договоры')

@section('scripts')
	@vite(['resources/js/adminContracts.js'])
@endsection

@section('content')
	<div id="app">
		<contracts></contracts>
	</div>
@endsection
