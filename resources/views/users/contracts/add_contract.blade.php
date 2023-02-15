@extends('layouts.app')

@section('title', 'Добавить договор')

@section('content')
    <div class="container">
        <div class="row">
        
  
        
        <h1>Добавление договора</h1>
        
        
        <div class="container text-center">
          <div class="row">
           
            <div class="col-8 align-self-center">
              {{$termsOfContract}}
            </div>
           
          </div>
        </div>
        
        
      
        
        
<div id="tariffs">
   @foreach ($tariffs as $tariffGroupTitle=>$grouped_tariffs)
  <nav class="navbar bg-dark text-white mt-5">
          <div class="container-fluid justify-content-start">
         {{$tariffGroupTitle}}
         </div>
        </nav>
         
        <div class="container-fluid text-center">
          <div class="row">
		@foreach($grouped_tariffs as $tariff)
		
	<div class="col-4 mt-5 ">
		<div class="card" style="width: 18rem;">
        
          <div class="card-body">
            <h5 class="card-title">Количество месяцев {{$tariff->duration }}</h5>
            <p class="card-text">
            Объем вклада: {{ ($tariff->max_amount->raw() == 0) ? 'без ограничений' : 'до '.$tariff->max_amount }}
            <br>
            Cрок размещения:: до {{$grouped_tariffs->max('duration')}} мес.
            </p>
         
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">доходность в год {{$tariff->annual_rate}}</li>
            <li class="list-group-item">доходность {{ ($tariff->getting_profit == 1) ? 'ежемесячно' : 'по истечению срока' }}</li>
            <li class="list-group-item">тело вклада {{ ($tariff->getting_deposit == 1) ? 'ежемесячно' : 'по истечению срока' }}</li>
          </ul>
          <div class="card-body">
            <a href="#calculator" class="card-link" @click="setCalcData({{$tariff->annual_rate}},{{$tariff->duration }},'{{$tariff->title}}')">Выбрать</a>
          
          </div>
        </div>
	</div>	
		
		@endforeach 
		   </div>
        </div>

 @endforeach
  
   
        
        <div id="calculator">
        <nav class="navbar bg-dark text-white mt-5">
                  <div class="container-fluid justify-content-start">
                 Калькулятор
                 </div>
                 <div class="container-fluid justify-content-end">
                 тариф: @{{tariffTitle}}
                 </div>
                </nav>
          <div class="container text-center">
                  <div class="row">
        <table class="table text-center">
          <thead>
            <tr>
              <th scope="col">Вклад руб.</th>
              <th scope="col">доходность ип за месяц. </th>
              <th scope="col">  "+" (к сумме инвестиций) за 1 год. </th>
        	  <th scope="col">доходность ИП(% ставка в мес.)</th>
        	  <th scope="col">total ИП за 1 год</th>
        	  <th scope="col">total ИП за период</th>
        	  <th scope="col">доходность ИП (ROI / х) за период</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row"><input class="form-control" type="number" v-model="amount" placeholder="Вклад" aria-label="default input example"></th>
              <td>@{{profitabilityPerMounth}}</td>
              <td>@{{plusToAmountPerYear}}</td>
              <td>@{{profitabilityPerMounthRateProc}}</td>
              <td>@{{totalPerYear}}</td>
              <td>@{{totalPerPeriod}}</td>
              <td>@{{profitabilityPerPeriod}}</td>
            </tr>
         
          </tbody>
        </table>
        
              </div>
           </div>
        
        </div>

  </div>

        
        </div>
     </div>   
     
@vite(['resources/js/vueTariffCalc.js'])

@endsection




