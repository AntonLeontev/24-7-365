@extends('layouts.app')

@section('title', 'Добавить договор')

@section('scripts')
    @vite(['resources/js/newContract.js'])
@endsection

@section('content')

    <x-common.h1 class="mb-13">Заключение нового договора</x-common.h1>
    <div id="app">
        <add-contract
			:amount-saved="{{ request()->amount ?? 0 }}"
			:tariff-id-saved="{{ request()->tariff_id ?? 0 }}"
			:tariffs="{{ json_encode(tariffs()) }}"
			:user="{{ json_encode(auth()->user()->load(['organization', 'account'])) }}"
		></add-contract>
    </div>
@endsection
