<?php

namespace App\Actions\Reports\ProfitReport;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ProfitReport
{
	public function __construct(
		private readonly Collection $data,
		private readonly float $income,
		private readonly CarbonPeriod $period,
	)
	{	  
	}

	public function getData(): Collection
	{
		return $this->data;
	}

	public function getIncome(): float
	{
		return $this->income;
	}

	public function toHtml(): string
	{
		return view(
			'reports.profit', 
			[
				'data' => $this->data, 
				'income' => $this->income,
				'period' => $this->period,
				'periodInDays' => $this->periodInDays(),
				'bodySum' => $this->bodySum(),
				'outcome' => $this->outcome(),
			],
		)->render();
	}

	public function toExcel(): string
	{
		$writer = SimpleExcelWriter::create(
			storage_path('app/public/') . $this->fileName(), 
			configureWriter: function($writer) {
				$options = $writer->getOptions();
				$options->DEFAULT_COLUMN_WIDTH=18;
				$options->setColumnWidth(30, 2,);
				$options->setColumnWidth(6, 1, 5);
			}
		)
			->addHeader([
				'Номер договора',
				'Контрагент',
				'Старт периода',
				'Конец периода',
				'Дней',
				'Сумма договора',
				'Срок тарифа',
				'Часть суммы',
				'Выплаты',
			]);

		foreach ($this->data as $item) {
			$days = $item->get('period_start')->diffInDays($item->get('period_end')) + 1;
			$writer->addRow([
				$item->get('contract_number'),
				$item->get('organization'),
				$item->get('period_start')->translatedFormat('d F Y'),
				$item->get('period_end')->translatedFormat('d F Y'),
				$days,
				number_format($item->get('contract_amount'), 2, ',', ' ') . ' р',
				$item->get('contract_duration') . ' мес',
				number_format($item->get('contract_amount') / $item->get('contract_duration') / $this->periodInDays() * $days, 2, ',', ' '),
				number_format($item->get('payments_sum', 2, ',', ' '))
			]);
		}

		$writer->addRows([
			[''],
			[''],
			['','','','','','', 'Поступления', 'Сумма частей тел договоров', 'Сумма выплат'],
			['','','','','','', $this->income, number_format($this->bodySum(), 2, ',', ' '), $this->outcome()],
			[''],
			['','','','','','Агентское вознаграждение', number_format($this->income - $this->bodySum() - $this->outcome(), 2, ',', ' ')],
		]);

		return $this->fileName();
	}

	private function bodySum(): float
	{
		$periodDays = $this->period->getStartDate()->diffInDays($this->period->getEndDate()) + 1;
		
		return $this->data->reduce(function($carry, $item) use ($periodDays) {
			$days = $item->get('period_start')->diffInDays($item->get('period_end')) + 1;

			return $carry + $item->get('contract_amount') / $item->get('contract_duration') / $periodDays * $days;
		}, 0);
	}

	private function outcome(): float
	{
		return $this->data->reduce(function($carry, $item) {
			return $carry + $item->get('payments_sum');
		}, 0);
	}

	private function periodInDays(): int
	{
		return $this->period->getStartDate()->diffInDays($this->period->getEndDate()) + 1;
	}

	private function fileName(): string
	{
		$start = $this->period->getStartDate();
		$end = $this->period->getEndDate();

		if (
			$start->eq($start->startOfMonth()) && 
			$end->eq($end->endOfMonth()) &&
			$start->format('m Y') === $end->format('m Y')
		) {
			return "Отчет о прибыли за {$start->translatedFormat('F Y')}.xlsx";
		}

		return "Отчет о прибыли за период с {$start->translatedFormat('d F Y')} по {$end->translatedFormat('d F Y')}.xlsx";
	}
}
