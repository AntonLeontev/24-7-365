@extends('layouts.app')

@section('title', 'Активные договоры')

@section('content')
    <div class="container">
        <div class="row">
        
        <h1>Активные договоры</h1>
        
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
    </tr>
  </thead>
  <tbody>
  
  
  @foreach ($contracts as $contract)
			<tr>
              <th scope="row"></th>
              <td>{{ $contract->status }}</td>
              <td>{{ $contract->id }}</td>
              <td>{{ $contract->created_at }}</td>
              <td>{{ $contract->created_at }}</td>
              <td>{{ $contract->tariff->title }}</td> 
                       <td><a href="{{ route("users.contract_show", $contract->id) }}"><button type="button" class="btn btn-success">подробнее</button></a></td>    
            </tr>

 @endforeach
    
   
  </tbody>
</table>
 {{ $contracts->links() }}
 <p class="fst-italic">*Все операции по договорам происходят на внутренней странице договора. (при клике по строке с договором)</p>       
        
        
        </div>
     </div>   

@endsection
