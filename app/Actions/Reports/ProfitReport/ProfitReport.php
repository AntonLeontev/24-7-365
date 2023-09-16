<?php

namespace App\Actions\Reports\ProfitReport;

use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

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
			],
		)->render();
	}
}
