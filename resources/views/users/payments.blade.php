@extends('layouts.app')

@section('title', 'График платежей')

@section('content')
    <div class="container">

            <x-common.h1 class="mb-4">График платежей</x-common.h1>

			@foreach ($operations as $date => $month)
			<div class="card mb-4">
				<div class="card-header">
					{{ now()->parse($date)->translatedFormat("F, Y год") }}
				</div>
				<div class="card-body">
					<x-common.tables.yellow>
						<x-slot:header>
							<div class="col">Дата</div>
							<div class="col">Начислено</div>
							<div class="col">Тело закупа</div>
							<div class="col">Договор</div>
							<div class="col">К выплате</div>
						</x-slot:header>

						@foreach ($month as $operation)
							@if ($operation instanceof App\Models\Profitability)
								<x-common.tables.yellow.row>
									<div class="col">{{ $operation->planned_at->translatedFormat('d F Y') }}</div>
									<div class="col">+{{ $operation->amount }}</div>
									<div class="col"></div>
									<div class="col">
										<a href="{{ route('users.contract_show', $operation->contract->id) }}" class="btn-link">
											# {{ $operation->contract->id }}
										</a>	
									</div>
									<div class="col">
										@if ($operation->payment->planned_at === $operation->planned_at)
											{{ $operation->payment->amount }}
										@else
											{{ $operation->payment->planned_at->translatedFormat('d F Y') }}
										@endif
									</div>
								</x-common.tables.yellow.row>
							@elseif ($operation instanceof App\Models\Payment)
								<x-common.tables.yellow.row>
									<div class="col">{{ $operation->planned_at->translatedFormat('d F Y') }}</div>
									<div class="col"></div>
									<div class="col"></div>
									<div class="col">
										<a href="{{ route('users.contract_show', $operation->contract->id) }}" class="btn-link">
											# {{ $operation->contract->id }}
										</a>
									</div>
									<div class="col">
										{{ $operation->amount }}
									</div>
								</x-common.tables.yellow.row>
							@endif
						@endforeach
					</x-common.tables.yellow>
				</div>
			</div>
			@endforeach

            {{-- {{ $payments->links() }} --}}



    </div>
    
@endsection
