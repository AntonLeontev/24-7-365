@extends('layouts.auth')

@section('title', 'Вход')

@section('scripts')
	@vite(['resources/js/auth.js'])
@endsection

@section('content')
	@include('layouts.part.login-register')
@endsection
