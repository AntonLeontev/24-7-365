@extends('layouts.app')

@section('title', 'Активные договоры')

@section('content')
    <div class="container">
        <div class="row">



            <h1>Активные договоры</h1>


            <nav class="navbar bg-body-tertiary">
                <form class="container-fluid justify-content-end">
                    <a href="{{ route('users.add_contract') }}"> <button class="btn btn-outline-primary"
                            type="button">Добавить договор</button> </a>
                </form>
            </nav>


            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Номер</th>
                        <th scope="col">Дата заключения</th>
                        <th scope="col">Дата окончания</th>
                        <th scope="col">Тариф</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>


                    @foreach ($contracts as $contract)
                        <tr>
                            <th scope="row"></th>
                            <td>{{ $contract->status }}</td>
                            <td>{{ $contract->id }}</td>
                            <td>
								@if ($contract->status === $contract::ACTIVE)
									{{ $contract->paid_at }}
								@elseif ($contract->status === $contract::PENDING)
									Ожидает оплаты
								@elseif ($contract->status === $contract::CANCELED)
									На расторжении
								@endif
							</td>
                            <td>
								@if ($contract->status === $contract::ACTIVE)
									{{ $contract->paid_at->addMonths($contract->tariff->duration) }}
								@endif
                            <td>{{ $contract->tariff->title }}</td>
                            <td>
								<a href="{{ route('users.contract_show', $contract->id) }}">
									<button class="btn btn-success" type="button">подробнее</button>
								</a>
                            </td>
							<td><a href="{{ route('contracts.cancel', $contract->id) }}" class="btn btn-danger">Del</a></td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
            {{ $contracts->links() }}
            <p class="fst-italic">*Все операции по договорам происходят на внутренней странице договора. (при клике по
                кнопке "подробнее" в строке договора)</p>


        </div>
    </div>

@endsection