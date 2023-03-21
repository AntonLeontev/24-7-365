@extends('layouts.app')

@section('title', 'Изменить договор')

@section('content')
	<x-common.h1>Смена тарифа по договору {{ $contract->id }}</x-common.h1>
@endsection
