@extends('layouts.static')

@section('title', 'Условия бронирования счета')

@section('content')
    <div class="container">
		<x-common.h1>
			Условия бронирования счета
		</x-common.h1>
		<div class="px-lg-120 text-justify">
	
			<p class="mt-3">
				Услуга по резервированию номера расчётного счёта оказывается юридическим лицам и индивидуальным предпринимателям
				(далее – клиенты), заполнившим заявку на сайте или позвонившим в банк по номеру телефона 8 800 2000 024.
	
	
			</p>
			<p>
				Платежи по зарезервированному счёту банк не проводит. Деньги, перечисленные клиенту по реквизитам
				зарезервированного счёта, хранятся в банке в течение 5 рабочих дней. Если счёт не будет открыт, через 5 рабочих
				дней деньги вернутся их отправителю. Банк не несет ответственности за убытки, возникшие у клиента, если на дату
				возврата денег отправителю, счёт отправителя закрыт или у него поменялись реквизиты. Если в течение 5 рабочих
				дней с момента поступления денег на зарезервированный счёт он будет открыт, деньги переведутся на открытый счёт
				в сроки, установленные действующим законодательством РФ.
	
	
			</p>
			<p>
				Банк может отказать в резервировании номера счёта в одностороннем порядке без объяснения причины. Банк может
				отказать в открытии зарезервированного счёта в случаях, установленных внутренними документами банка в
				соответствии с действующим законодательством РФ.
			</p>
		</div>
    </div>
@endsection
