<?php

namespace App\Actions\Reports\ProfitReport;

use App\Enums\ContractStatus;
use App\Enums\PaymentType;
use App\Models\Contract;
use App\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class ProfitReportMaker
{
    private CarbonPeriod $period;

    public function make(CarbonPeriod $period): ProfitReport
    {
        $this->period = $period;

        $contracts = $this->getContracts();

        $data = $this->prepare($contracts);

        $income = $this->getIncome();

        return new ProfitReport($data, $income, $period);
    }

    private function getContracts(): Collection
    {
        // TODO canceled and terminated contracts
        $contracts = Contract::query()
            ->whereIn('status', [ContractStatus::active, ContractStatus::pending])
            ->where('paid_at', '<=', $this->period->getEndDate())
            ->get();

        $finishedContracts = Contract::query()
            ->whereIn('status', [ContractStatus::finished])
            ->where('paid_at', '<=', $this->period->getEndDate())
            ->where('updated_at', '>=', $this->period->getStartDate())
            ->get();

        $contracts = $contracts->concat($finishedContracts)->load(['tariff', 'organization', 'realChanges']);

        return $contracts;
    }

    private function prepare(Collection $contracts): SupportCollection
    {
        $result = [];

        foreach ($contracts as $contract) {

            if ($contract->status === ContractStatus::active && $contract->contractChanges->count() === 1) {
                $result[] = collect([
                    'contract_number' => $contract->id,
                    'organization' => $contract->organization->title,
                    'period_start' => $this->latest($contract->paid_at, $this->period->getStartDate()),
                    'period_end' => $this->period->getEndDate(),
                    'contract_amount' => $contract->amount->amount(),
                    'contract_duration' => $contract->tariff->duration,
                    'tariff_rate' => $contract->tariff->annual_rate,
                ]);

                continue;
            }

            if ($contract->status === ContractStatus::finished && $contract->contractChanges->count() === 1) {
                $result[] = collect([
                    'contract_number' => $contract->id,
                    'organization' => $contract->organization->title,
                    'period_start' => $this->latest($contract->paid_at, $this->period->getStartDate()),
                    'period_end' => $this->earliest($contract->end(), $this->period->getEndDate()),
                    'contract_amount' => $contract->amount->amount(),
                    'contract_duration' => $contract->tariff->duration,
                    'tariff_rate' => $contract->tariff->annual_rate,
                ]);

                continue;
            }

            if ($contract->realChanges->count() > 1) {
                $changes = $contract->realChanges->load('tariff');

                if (
                    $changes->last()->starts_at >= $this->period->getStartDate()
                    && $changes->last()->starts_at <= $this->period->getEndDate()
                ) {
                    // Отчет о периоде до изменений
                    $result[] = collect([
                        'contract_number' => $contract->id,
                        'organization' => $contract->organization->title,
                        'period_start' => $this->period->getStartDate(),
                        'period_end' => $changes->last()->starts_at->subDay(),
                        'contract_amount' => $changes->slice(-2, 1)->first()->amount->amount(),
                        'contract_duration' => $changes->slice(-2, 1)->first()->tariff->duration,
                        'tariff_rate' => $contract->tariff->annual_rate,
                    ]);

                    // Отчет о периоде после изменений
                    $result[] = collect([
                        'contract_number' => $contract->id,
                        'organization' => $contract->organization->title,
                        'period_start' => $changes->last()->starts_at,
                        'period_end' => $this->period->getEndDate(),
                        'contract_amount' => $contract->amount->amount(),
                        'contract_duration' => $contract->tariff->duration,
                        'tariff_rate' => $contract->tariff->annual_rate,
                    ]);

                    continue;
                } else {
                    $result[] = collect([
                        'contract_number' => $contract->id,
                        'organization' => $contract->organization->title,
                        'period_start' => $this->latest($contract->paid_at, $this->period->getStartDate()),
                        'period_end' => $this->period->getEndDate(),
                        'contract_amount' => $contract->amount->amount(),
                        'contract_duration' => $contract->tariff->duration,
                        'tariff_rate' => $contract->tariff->annual_rate,
                    ]);

                    continue;
                }
            }

        }

        return collect($result);
    }

    private function latest(Carbon ...$dates): Carbon
    {
        $result = null;

        foreach ($dates as $date) {
            if (is_null($result)) {
                $result = $date;

                continue;
            }

            $result = $result > $date ? $result : $date;
        }

        return $result;
    }

    private function earliest(Carbon ...$dates): Carbon
    {
        $result = null;

        foreach ($dates as $date) {
            if (is_null($result)) {
                $result = $date;

                continue;
            }

            $result = $result < $date ? $result : $date;
        }

        return $result;
    }

    private function paymentsPerPeriod(Contract $contract): int
    {
        return DB::table('payments')
            ->where('contract_id', $contract->id)
            ->where('type', PaymentType::credit)
            ->where('paid_at', '>=', $this->period->getStartDate())
            ->where('paid_at', '<=', $this->period->getEndDate())
            ->sum('amount');
    }

    private function getIncome(): float
    {
        $transactions = Transaction::query()
            ->where('direction', 'credit')
            ->where('created_at', '>=', $this->period->getStartDate())
            ->where('created_at', '<=', $this->period->getEndDate())
            ->get();

        // Выбираем только поступления от озона
        $sum = $transactions
            ->filter(function (Transaction $transaction) {
                // TODO refactor to separate class
                if ($transaction->payer_inn === '7704217370') {
                    return true;
                }

                if (preg_match('/ИНН 7704217370 по реестру/i', $transaction->purpose)) {
                    return true;
                }

                if (preg_match('/ИР-200853\/23 от 14\.06\.2023/i', $transaction->purpose)) {
                    return true;
                }

                return false;
            })
            ->sum('amount');

        return $sum / 100;
    }
}
