@extends('layouts.app')

@section('scripts')
	@vite(['resources/js/test.js'])
@endsection

@section('content')
	<x-common.h1 class="mb-15">Test</x-common.h1>
	<div id="app">
		<test :tariffs="{{ json_encode(tariffs()) }}"></test>
	</div>
@endsection
