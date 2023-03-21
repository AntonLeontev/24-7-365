
<div class="continer-fluid">
<div class="row">

@foreach (tariffs() as $tariffGroupTitle => $grouped_tariffs)
                    
                    <div class="card tariff-card">
  <div class="card-body ">
    
   
      <div class="card-title"><h5>Тариф «{{ $tariffGroupTitle }}»</h5></div>
       <div class="tab-content" id="nav-tariff-tabContent">  
       @foreach ($grouped_tariffs as $key=>$tariff) 
      
     <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="nav-{{ md5($tariffGroupTitle) }}-{{ $tariff->duration }}" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
      <table class="table">  
        <tr>
          <td>Количество месяцев</td>
          <td>{{ $tariff->duration }}</td>
        </tr>
        <tr>
    
          <td>Сумма</td>
          <td>{{ $tariff->max_amount->raw() == 0 ? 'без ограничений' : 'до ' . $tariff->max_amount }}</td>
        </tr>
        <tr>
          <td>Доходность ИП<br>
          (% ставка в год)
          </td>
          <td>{{ $tariff->annual_rate }}%</td>
        </tr>
        
        <tr>
          <td>Доходность ИП<br>
          (% ставка в мес)
          </td>
          <td>{{ number_format((float)($tariff->annual_rate/12), 2, '.', ''); }}%</td>
        </tr>
        
      </tbody>
    </table>
    
    <ul>
    <li>Объем вклада:{{ $tariff->max_amount->raw() == 0 ? 'без ограничений' : 'до ' . $tariff->max_amount }}</li>
    <li>Cрок размещения: до {{ $grouped_tariffs->max('duration') }} мес.</li>
    <li>Тело вклада{{ $tariff->getting_deposit == 1 ? 'ежемесячно' : 'по истечению срока' }}</li>
    <li>Доходность {{ $tariff->getting_profit == 1 ? 'ежемесячно' : 'по истечению срока' }}</li>
    </ul>
   </div>
    
      @endforeach
      </div>
      
      
    <div>
    <p>Сроки вкладов:</p>
    <div class="scroller">
    <div class="spot"></div>
       <div id="nav-tab-{{ md5($tariffGroupTitle) }}" role="tablist">
        <table>
        <tr>
            <td><svg width="2" height="7" viewBox="0 0 2 7" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="2" height="7" fill="#3A3A3A"/></svg></td>
            <td><svg width="2" height="7" viewBox="0 0 2 7" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="2" height="7" fill="#3A3A3A"/></svg></td>
            <td><svg width="2" height="7" viewBox="0 0 2 7" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="2" height="7" fill="#3A3A3A"/></svg></td>
        </tr>

        <tr>
            <td><a class="nav-link active" id="nav-{{ md5($tariffGroupTitle) }}-{{ $grouped_tariffs[0]['duration'] }}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{ md5($tariffGroupTitle) }}-{{ $grouped_tariffs[0]['duration'] }}" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ $grouped_tariffs[0]['duration'] }} мес.</a></td>
            <td><a class="nav-link " id="nav-{{ md5($tariffGroupTitle) }}-{{ $grouped_tariffs[1]['duration'] }}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{ md5($tariffGroupTitle) }}-{{ $grouped_tariffs[1]['duration'] }}" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ $grouped_tariffs[1]['duration'] }} мес.</a></td>
            <td><a class="nav-link " id="nav-{{ md5($tariffGroupTitle) }}-{{ $grouped_tariffs[2]['duration'] }}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{ md5($tariffGroupTitle) }}-{{ $grouped_tariffs[2]['duration'] }}" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ $grouped_tariffs[2]['duration'] }} мес.</a></td>
            
           
        </tr>
        </table>
        </div>
        
    </div>
        
    </div>
    
    <a class="btn btn-outline-primary" href="#">Выбрать</a>
    
 
    
  
  </div>
</div>
@endforeach                    
 
 
 </div>
 </div>                   
           
                    
     