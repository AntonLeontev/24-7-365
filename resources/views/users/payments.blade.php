@extends('layouts.app')

@section('title', 'График платежей')

@section('content')
    <div class="container">
        <div class="row">



            <h1>График платежей</h1>



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


                    @foreach ($operations as $date => $month)
						<tr>
							<td colspan="5" style="background: yellow">
								{{ now()->parse($date)->translatedFormat("F Y") }}
							</td>
						</tr>
						@foreach ($month as $operation)
							@if ($operation instanceof App\Models\Profitability)
								<tr>
									<td>{{ $operation->planned_at->translatedFormat('d F Y') }}</td>
									<td>+{{ $operation->amount }}</td>
									<td></td>
									<td>
										@if ($operation->payment->planned_at === $operation->planned_at)
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
    
@endsection
