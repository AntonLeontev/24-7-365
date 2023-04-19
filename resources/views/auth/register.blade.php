@extends('layouts.auth')

@section('title', 'Регистрация')

@section('scripts')
	@vite(['resources/js/auth.js'])
@endsection

@section('content')
	@include('layouts.part.login-register')
@endsection
