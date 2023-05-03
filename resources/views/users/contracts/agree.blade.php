@extends('layouts.app')

@section('title', 'Текст договора')

@section('scripts')
	@vite(['resources/js/agree.js'])
@endsection

@section('content')
    <x-common.h1>Заключение нового договора</x-common.h1>

    <div class="card my-13">
        <div class="card-header d-flex justify-content-between align-items-center">
            Заключение договора
            <div class="d-flex gap-3">
                <a class="btn btn-outline-primary d-none d-lg-block" href="{{ route('users.contract.pdf') }}" download>
                    Скачать договор в PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="contract-text text-bg-dark vh-70 overflow-auto px-3 py-3 mb-12">
                @include('pdf.contract.text')
            </div>
			<form id="agree-form" class="d-flex flex-column gap-3" action="{{ route('users.add_contract') }}" method="get">
				<input type="hidden" name="tariff_id" value="{{ request()->tariff_id }}">
				<input type="hidden" name="amount" value="{{ request()->amount }}">
				<x-common.form.checkbox name="read" class="fs-7 fs-sm-6">Я прочёл договор и приложение к договору</x-common.form.checkbox>
				<x-common.form.checkbox name="understood" class="fs-7 fs-sm-6">Я понял условия описанные в договоре и приложеии к договору</x-common.form.checkbox>
				<x-common.form.checkbox name="agreed" class="fs-7 fs-sm-6">Я согласен с условиями договора и приложением к договору</x-common.form.checkbox>
				<button class="btn btn-primary w-100 fs-7 fs-sm-6" disabled>Продолжить заключение договора</button>
			</form>
            <a class="btn btn-outline-primary w-100 d-lg-none mt-4 fs-7 fs-sm-6" href="{{ route('users.contract.pdf') }}" download>
                Скачать договор в PDF
            </a>
        </div>
    </div>

@endsection
