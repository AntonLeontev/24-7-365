<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<style type="text/css">
	@font-face { 
		font-family: "Roboto";
		src: url({{ '/storage/fonts/Roboto-Medium.ttf' }}) format("truetype");
		font-weight: 400;
	}
	@font-face { 
		font-family: "Roboto";
		src: url({{ '/storage/fonts/Roboto-Bold.ttf' }}) format("truetype");
		font-weight: bold;
	}

	* {font-family: Roboto;font-size: 14px;line-height: 14px;}
	table {margin: 0 0 15px 0;width: 100%;border-collapse: collapse;border-spacing: 0;}		
	table th {padding: 5px;font-weight: bold;}        
	table td {padding: 5px;}	
	h1 {margin: 0 0 10px 0;padding: 10px 0;border-bottom: 2px solid #000;font-weight: bold;font-size: 20px;}
		
	/* Реквизиты банка */
	.requesites td {padding: 3px 2px;border: 1px solid #000000;font-size: 12px;line-height: 12px;vertical-align: top;}
 
	/* Поставщик/Покупатель */
	.contract th {padding: 3px 0;vertical-align: top;text-align: left;font-size: 13px;line-height: 15px;}	
	.contract td {padding: 3px 0;}		
 
	/* Наименование товара, работ, услуг */
	.list thead, .list tbody  {border: 2px solid #000;}
	.list thead th {padding: 4px 0;border: 1px solid #000;vertical-align: middle;text-align: center;}	
	.list tbody td {padding: 0 2px;border: 1px solid #000;vertical-align: middle;font-size: 11px;line-height: 13px;}	
	.list tfoot th {padding: 3px 2px;border: none;text-align: right;}	
 
	/* Сумма */
	.total {margin: 0 0 20px 0;padding: 0 0 10px 0;border-bottom: 2px solid #000;}	
	.total p {margin: 0;padding: 0;}
		
	/* Руководитель, бухгалтер */
	.sign {position: relative;}
	.sign table {width: 60%;}
	.sign th {padding: 40px 0 0 0;text-align: left;}
	.sign td {padding: 40px 0 0 0;border-bottom: 1px solid #000;text-align: right;font-size: 12px;}
	.sign-1 {position: absolute;left: 149px;top: -44px;}	
	.sign-2 {position: absolute;left: 149px;top: 0;}	
	.printing {position: absolute;left: 271px;top: -15px;}
	</style>
</head>
<body>
	<table class="requesites">
		<tbody>
			<tr>
				<td colspan="2" style="border-bottom: none;">{{ settings()->bank }}<br><br></td>
				<td>БИК</td>
				<td style="border-bottom: none;">{{ settings()->bik }}</td>
			</tr>
			<tr>
				<td colspan="2" style="border-top: none; font-size: 10px;">Банк получателя</td>
				<td>Сч. №</td>
				<td style="border-top: none;">{{ settings()->payment_account }}</td>
			</tr>
			<tr>
				<td width="25%">ИНН {{ settings()->inn }}</td>
				<td width="30%">КПП {{ settings()->kpp }}</td>
				<td width="10%" rowspan="3">Сч. №</td>
				<td width="35%" rowspan="3">{{ settings()->correspondent_account }}</td>
			</tr>
			<tr>
				<td colspan="2" style="border-bottom: none;">{{ settings()->organization_title }}<br><br></td>
			</tr>
			<tr>
				<td colspan="2" style="border-top: none; font-size: 10px;">Получатель</td>
			</tr>
		</tbody>
	</table>
	
	<h1>Счет на оплату № {{ $payment->id }} от {{ $payment->created_at->translatedFormat('d F Y') }}</h1>
 
	<table class="contract">
		<tbody>
			<tr>
				<td width="15%">Поставщик:<br>(Исполнитель)</td>
				<th width="85%">{{ settings()->organization_title }}, ИНН {{ settings()->inn }}, КПП {{ settings()->kpp }}, {{ settings()->legal_address }}</th>
			</tr>
			<tr>
				<td>Покупатель:<br>(Заказчик)</td>
				<th> 
					{{ $payment->account->organization->title }}, ИНН {{ $payment->account->organization->inn }}, КПП {{ $payment->account->organization->kpp }},
				</th>
			</tr>
			<tr>
				<td>Основание:</td>
				<th>Оплата по договору {{ $payment->contract_id }}
				</th>
			</tr>
		</tbody>
	</table>
 
	<table class="list">
		<thead>
			<tr>
				<th width="5%">№</th>
				<th width="54%">Наименование товара, работ, услуг</th>
				<th width="8%">Коли-<br>чество</th>
				<th width="5%">Ед.<br>изм.</th>
				<th width="14%">Цена</th>
				<th width="14%">Сумма</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="center">1</td>
				<td align="left">Наименование услуги</td>
				<td align="right">1</td>
				<td align="left">шт</td>
				<td align="right">{{ $payment->amount }}</td>
				<td align="right">{{ $payment->amount }}</td>
			</tr>
		</tbody>
	</table>
	<table>
		<tbody>
			<tr>
				<td style="width: 80%; text-align:right"><strong>Итого:</strong></td>
				<td style="text-align:right"><strong>{{ $payment->amount }}</strong></td>
			</tr>
			<tr>
				<td style="width: 80%; text-align:right"><strong>Без налога (НДС)</strong></td>
				<td style="text-align:right"><strong>-</strong></td>
			</tr>
			<tr>
				<td style="width: 80%; text-align:right"><strong>Всего к оплате:</strong></td>
				<td style="text-align:right"><strong>{{ $payment->amount }}</strong></td>
			</tr>
		</tbody>
	</table>
	
	<div class="total">
		<p>Всего наименований 1, на сумму {{ $payment->amount }}</p>
		<p><strong>{{ amount_to_string($payment->amount->amount()) }}</strong></p>
	</div>
	
	<div class="sign">
		<table>
			<tbody>
				<tr>
					<th width="30%">Руководитель</th>
					<td width="70%">{{ settings()->director }}</td>
				</tr>
				<tr>
					<th>Бухгалтер</th>
					<td>{{ settings()->accountant }}</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div>
		Обращаем ваше внимание, что назначение платежа вашего платежного поручения должно ТОЧНО СООТВЕТСТВОВАТЬ назначению платежа выставленного Вам счета. В противном случае, вынуждены будем вам вернуть ваш платеж. Расходы на возврат средств на ваш р/с (банковская комиссия / от 1 до 3%), в таком случае, будет произведен за ваш счет
	</div>
</body>
</html>
