<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	{{-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> --}}
	
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
				<td colspan="2" style="border-bottom: none;">ЗАО "БАНК", г.Москва</td>
				<td>БИК</td>
				<td style="border-bottom: none;">000000000</td>
			</tr>
			<tr>
				<td colspan="2" style="border-top: none; font-size: 10px;">Банк получателя</td>
				<td>Сч. №</td>
				<td style="border-top: none;">00000000000000000000</td>
			</tr>
			<tr>
				<td width="25%">ИНН 0000000000</td>
				<td width="30%">КПП 000000000</td>
				<td width="10%" rowspan="3">Сч. №</td>
				<td width="35%" rowspan="3">00000000000000000000</td>
			</tr>
			<tr>
				<td colspan="2" style="border-bottom: none;">ООО "Компания"</td>
			</tr>
			<tr>
				<td colspan="2" style="border-top: none; font-size: 10px;">Получатель</td>
			</tr>
		</tbody>
	</table>
 
	<h1>Счет на оплату № 10 от {{ now()->translatedFormat('d F Y') }}</h1>
 
	<table class="contract">
		<tbody>
			<tr>
				<td width="15%">Поставщик:</td>
				<th width="85%">ООО "Компания", ИНН 0000000000, КПП 000000000, 125009, Москва г, Тверская, дом № 9</th>
			</tr>
			<tr>
				<td>Покупатель:</td>
				<th>ООО "Покупатель", ИНН 0000000000, КПП 000000000, 119019, Москва г, Новый Арбат, дом № 10
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
				<td align="right"></td>
				<td align="right"></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="5">Итого:</th>
				<th>р</th>
			</tr>
			<tr>
				<th colspan="5">Без налога (НДС)</th>
				<th>-</th>
			</tr>
			<tr>
				<th colspan="5">Всего к оплате:</th>
				<th>{{ $sum }} р</th>
			</tr>
		</tfoot>
	</table>
	
	<div class="total">
		<p>Всего наименований 1, на сумму {{ $sum }} руб.</p>
		<p><strong>{{ amount_to_string($sum) }}</strong></p>
	</div>
	
	<div class="sign">
		<table>
			<tbody>
				<tr>
					<th width="30%">Руководитель</th>
					<td width="70%">Путин В.В.</td>
				</tr>
				<tr>
					<th>Бухгалтер</th>
					<td>Шольц О.Б.</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>
