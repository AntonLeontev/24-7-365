@extends('layouts.app')

@section('title', 'Активные договоры')

@section('content')
    <div class="container">
        <div class="row">
        
  
        
        <h1>Активные договоры</h1>
        
   
        
          <nav class="navbar bg-dark text-white mt-5">
                  <div class="container-fluid justify-content-start">
                 Договор # {{ $contract->id }}; Тариф {{ $contract->tariff->title }} со сроком {{ $contract->tariff->duration }}
                 </div>
              
                </nav>
        
        
        
        
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Сумма</th>
      <th scope="col">Статус</th>
      <th scope="col">Дата</th>
    </tr>
  </thead>
  <tbody>
  
   @foreach ($payments as $payment)
			<tr>
              <th scope="row">{{ $payment->id }}</th>
              <td>{{ $payment->amount }}</td>
              <td>{{ $payment->status }}</td>
              <td>{{ $contract->created_at }}</td>  
            </tr>

 @endforeach
  </tbody>
</table>
 {{ $payments->links() }}



<button type="button" class="btn btn-primary btn-lg">скачать договор pdf</button>




     
      <nav class="navbar bg-body-tertiary mt-5">
          <form class="container-fluid justify-content-end">
         <a href="{{ route("users.add_contract") }}"> <button type="button" class="btn btn-outline-primary">Добавить договор</button> </a>
          </form>
        </nav>


        </div>
     </div>   

@endsection
