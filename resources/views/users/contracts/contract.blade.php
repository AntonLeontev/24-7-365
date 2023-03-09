@extends('layouts.app')

@section('title', 'Активные договоры')

@section('content')
    <div class="container">
        <div class="row">



            <h1>Договор {{ $contract->id }}</h1>


			<div class="row">
				<div class="col">
					@if ($contract->status === $contract::ACTIVE)
						<button href="{{ route('contracts.increase_amount', $contract->id) }}" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#increaseAmount">Increase amount</button>
						<a href="#" class="btn btn-outline-primary">Change tariff</a>
					@endif
					<a href="{{ route('contracts.cancel', $contract->id) }}" class="btn btn-outline-primary">Cancel</a>
					<a href="{{ url()->previous() }}" class="btn btn-outline-primary">Back</a>
					<a href="{{ route('pay-in', $contract->id) }}" class="btn btn-outline-danger">Pay-in</a>
					<a href="{{ route('period', $contract->id) }}" class="btn btn-outline-danger">Period</a>
				</div>
			</div>
			<div class="row">
				@error('amount')
					{{ $message }}
				@enderror
			</div>
			<div class="row">
				<div class="col">Тип</div>
				<div class="col">Тариф</div>
				<div class="col">Сумма</div>
				<div class="col">Статус</div>
				<div class="col">Начало</div>
				<div class="col">Длился</div>
			</div>

			@foreach ($contract->changes->load('tariff') as $change)
				<div class="row">
					<div class="col">
						@if ($change->type === $change::TYPE_INITIAL)
							Инициация
						@elseif ($change->type === $change::TYPE_INCREASE_AMOUNT)
							Увеличение суммы
						@elseif ($change->type === $change::TYPE_CHANGE_TARIFF)
							Смена тарифа
						@elseif ($change->type === $change::TYPE_PROLONGATION)
							Автопродление
						@endif
					</div>
					<div class="col">{{ $change->tariff->title }} - {{ $change->tariff->duration }}</div>
					<div class="col">{{ $change->amount }}</div>
					<div class="col">
						@if ($change->status === $change::STATUS_ACTUAL)
							Актуальный
						@elseif ($change->status === $change::STATUS_PENDING)
							Ждет подтверждения
						@elseif ($change->status === $change::STATUS_WAITING_FOR_PERIOD_END)
							Ждет конца периода
						@elseif ($change->status === $change::STATUS_PAST)
							Прошедший
						@endif
					</div>
					<div class="col">{{ $change->starts_at?->translatedFormat('d M Y h:i') }}</div>
					<div class="col">{{ $change->duration }}</div>
				</div>
			@endforeach

			@php($change = $contract->changes->last())
			<div class="row">
				@if ($change->status === $change::STATUS_PENDING)
					@unless ($change->type === $change::TYPE_INITIAL)
						Изменения ожидают подтверждения: {{ $change->name() }}
						<div>
							<a href="{{ route('contracts.cancel_change', $contract->id) }}" class="btn btn-outline-danger">Отменить изменение</a>
						</div>
					@endunless
				@elseif ($change->status === $change::STATUS_WAITING_FOR_PERIOD_END)
					Изменения вступят в силу {{ $contract->periodEnd()->translatedFormat('d M Y h:i') }}
				@endif
			</div>

            <nav class="navbar bg-dark mt-5 text-white">
                <div class="container-fluid justify-content-start">
                    Договор # {{ $contract->id }}; Тариф {{ $contract->tariff->title }} со сроком
                    {{ $contract->tariff->duration }}; Сумма {{ $contract->amount }}
                </div>

            </nav>




            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Дата</th>
                        <th scope="col">Начислено</th>
                        <th scope="col">Тело закупа</th>
                        <th scope="col">К выплате</th>
                    </tr>
                </thead>
                <tbody>


					@foreach ($operations as $operation)
						@if ($operation instanceof App\Models\Profitability)
							<tr>
								<td>{{ $operation->planned_at->translatedFormat('d F Y') }}</td>
								<td>+{{ $operation->amount }}</td>
								<td></td>
								<td>
									@if ($operation->payment->planned_at->equalTo($operation->planned_at))
										{{ $operation->payment->amount }}
									@else
										{{ $operation->payment->planned_at->translatedFormat('d F Y') }}
									@endif
								</td>
							</tr>
						@elseif ($operation instanceof App\Models\Payment)
							<tr>
								<td>{{ $operation->planned_at->translatedFormat('d F Y') }}</td>
								<td></td>
								<td></td>
								<td>
									{{ $operation->amount }}
								</td>
							</tr>
						@endif
					@endforeach

                    
                </tbody>
            </table>
            {{-- {{ $payments->links() }} --}}



            <button class="btn btn-primary btn-lg" type="button">скачать договор pdf</button>





            <nav class="navbar bg-body-tertiary mt-5">
                <form class="container-fluid justify-content-end">
                    <a href="{{ route('users.add_contract') }}"> <button class="btn btn-outline-primary"
                            type="button">Добавить договор</button> </a>
                </form>
            </nav>


        </div>
    </div>
	<div class="modal fade" id="increaseAmount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-body">
        <form action="{{ route('contracts.increase_amount', $contract->id) }}" method="post">
			@csrf
			<input type="number" name="amount" step="1000" value="{{ $contract->amount->amount() }}"/>
			@error('amount')
				{{ $message }}
			@enderror
			<button class="btn btn-outline-success" type="submit">Увеличить</button>
		</form>
      </div>
    </div>
  </div>
</div>


@endsection
