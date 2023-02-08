@extends('layouts.app')

@section('title', $user->first_name)

@section('content')
	Profile {{ $user->first_name }}
@endsection
