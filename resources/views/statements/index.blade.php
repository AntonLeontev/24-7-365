@extends('layouts.app')

@section('title', 'Выписки')

@section('scripts')
	@vite(['resources/js/statements.js'])
@endsection

@section('content')
<div id="app">
	<Statements></Statements>
</div>
@endsection
