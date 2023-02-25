@extends('layouts.app')

@section('title', 'Активные договоры')

@section('content')
    <div class="container">
        <div class="row">



            <h1>Договор {{ $contract->id }}</h1>



            <nav class="navbar bg-dark mt-5 text-white">
                <div class="container-fluid justify-content-start">
                    Договор # {{ $contract->id }}; Тариф {{ $contract->tariff->title }} со сроком
                    {{ $contract->tariff->duration }}; Сумма {{ $contract->amount }}
                </div>

            </nav>




            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Дата</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($payments as $payment)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $payment->amount }}</td>
                            <td>{{ $payment->status === $payment::STATUS_PENDING ? 'Ожидается' : 'Выполнен' }}</td>
                            <td>
								@if ($payment->status === $payment::STATUS_PENDING)
									{{ $payment->planned_at->translatedFormat('d M Y') }}
								@else
									{{ $payment->paid_at->translatedFormat('d M Y') }}
								@endif
							</td>
							<td>
								@if ($payment->type === $payment::TYPE_CREDIT)
									Исходящий
								@else
									Входящий
								@endif
							</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $payments->links() }}



            <button class="btn btn-primary btn-lg" type="button">скачать договор pdf</button>





            <nav class="navbar bg-body-tertiary mt-5">
                <form class="container-fluid justify-content-end">
                    <a href="{{ route('users.add_contract') }}"> <button class="btn btn-outline-primary"
                            type="button">Добавить договор</button> </a>
                </form>
            </nav>


        </div>
    </div>

@endsection
