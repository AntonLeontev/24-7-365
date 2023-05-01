<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use App\Enums\ContractStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\ValueObjects\Amount;
use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'user_id',
        'organization_id',
        'tariff_id',
        'amount',
        'status',
        'paid_at',
        'prolongate'
    ];

    protected $casts = [
        'amount' => AmountCast::class,
        'paid_at' => 'date:d m Y',
        'status' => ContractStatus::class,
        'prolongate' => 'boolean',
    ];

    protected $with = ['contractChanges'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function contractChanges(): HasMany
    {
        return $this->hasMany(ContractChange::class);
    }

    public function lastChange(): ContractChange
    {
        return $this->contractChanges->last();
    }

    public function profitabilities(): HasMany
    {
        return $this->hasMany(Profitability::class);
    }

    public function periodEnd(): Carbon
    {
        if (is_null($this->paid_at)) {
            throw new DomainException("Попытка определить конец периода у неоплаченного договора", 1);
        }

        return $this->paid_at->addMonths($this->duration() + 1);
    }

    public function end(): ?Carbon
    {
        if (is_null($this->paid_at)) {
            return null;
        }

        return $this->currentTariffStart()->addMonths($this->tariff->duration);
    }

    public function income(): int
    {
        return $this->paymentsSum(PaymentType::debet);
    }

    public function outgoing(): int
    {
        return $this->paymentsSum(PaymentType::credit);
    }

    public function outPaymentsSumFromStart(): int
    {
        // Выплаты считаются либо с init либо с последнего prolongate изменения
        $lastStart = $this->contractChanges->where('type', ContractChangeType::prolongation)->last();

        if (is_null($lastStart)) {
            $lastStart = $this->contractChanges->where('type', ContractChangeType::init)->last();
        }

        return $this->payments
            ->where('type', PaymentType::credit)
            ->filter(function ($payment) use ($lastStart) {
                return $payment->planned_at->gt($lastStart->starts_at) &&
                $payment->planned_at->lte($lastStart->starts_at->addMonths($this->durationFromLastStart()));
            })
            ->reduce(function ($carry, $payment) {
                return $carry + $payment->amount->raw();
            }, 0);
    }

    public function durationFromLastStart(): int
    {
        return $this->contractChanges->reduce(function ($carry, $change) {
            if ($change->type === ContractChangeType::init) {
                    return $change->duration;
            }

            if ($change->type === ContractChangeType::prolongation) {
                    return $change->duration;
            }

            return $change->duration + $carry;
        }, 0);
    }

    public function duration(): int
    {
        return $this->contractChanges->sum('duration');
    }

    public function currentTariffStart(): Carbon
    {
        $change = $this->currentTariffChange();

        return $change->starts_at;
    }

    public function currentTariffDuration(): int
    {
        $duration = 0;

        $this->contractChanges
            ->sortBy('starts_at')
            ->whereIn('status', [ContractChangeStatus::past, ContractChangeStatus::actual])
            ->reduce(function ($carry, ContractChange $change) use (&$duration) {
                if ($change->type === ContractChangeType::init) {
                    $duration = $change->duration;
                }

                if ($change->type === ContractChangeType::change) {
                    if ($carry?->tariff_id === $change->tariff_id) {
                        $duration += $change->duration;
                        return $change;
                    }

                    if ($change->tariff->getting_profit === Tariff::MONTHLY) {
                        $duration = $change->duration;
                        return $change;
                    }

                    if (
                        $carry->tariff->getting_profit === Tariff::MONTHLY &&
                        $change->tariff->getting_profit === Tariff::AT_THE_END
                    ) {
                        $duration = $change->duration;
                        return $change;
                    }

                    $duration += $change->duration;
                    return $carry;
                }

                if ($change->type === ContractChangeType::prolongation) {
                    $duration = $change->duration;
                }

                return $change;
            },
            null);

        return $duration;
    }

    public function lastEndTariffChange(): ContractChange
    {
        $endChange = $this->contractChanges
            ->load('tariff')
            ->sortBy('created_at')
            ->reduce(function ($carry, ContractChange $change) {
                if ($change->tariff->getting_profit === Tariff::MONTHLY) {
                    return $carry;
                }

                if ($change->type === ContractChangeType::init) {
                    return $change;
                }

                if ($change->type === ContractChangeType::prolongation) {
                    return $change;
                }

                if (
                    is_null($carry) &&
                    $change->tariff->getting_profit === Tariff::AT_THE_END
                ) {
                    return $change;
                }

                return $carry;
            }, null);

        if (is_null($endChange)) {
            throw new DomainException("Нет перехода на тариф с оплатой в конце срока", 1);
        }

        return $endChange;
    }

    public function isChanging(): bool
    {
        if ($this->status !== ContractStatus::active) {
            return false;
        }
        
        return $this->contractChanges->last()->status === ContractChangeStatus::pending ||
            $this->contractChanges->last()->status === ContractChangeStatus::waitingPeriodEnd;
    }

    public function isLastPeriod(): bool
    {
        return $this->currentTariffDuration() + 1 >= $this->tariff->duration;
    }

    public function amountOnDate(Carbon $date): Amount
    {
        foreach ($this->contractChanges->reverse() as $change) {
            if (is_null($change->starts_at)) {
                continue;
            }

            if (
                $change->starts_at->lessThan($date) &&
                $change->starts_at->addMonths($change->duration)->greaterThan($date)
            ) {
                return $change->amount;
            }
        }

        if ($this->periodEnd()->greaterThan($date)) {
            return $this->amount;
        }

        if ($this->contractChanges->last()->status === ContractChangeStatus::waitingPeriodEnd) {
            return $this->contractChanges->last()->amount;
        }
        
        return $this->amount;
    }

    private function currentTariffChange(): ?ContractChange
    {
        return $this->contractChanges
			->load('tariff')
            ->sortBy('starts_at')
            ->whereIn('status', [ContractChangeStatus::past, ContractChangeStatus::actual])
            ->reduce(function ($carry, ContractChange $change) {
                if ($change->type === ContractChangeType::init) {
                    return $change;
                }

                if ($change->type === ContractChangeType::change) {
                    if ($carry?->tariff_id === $change->tariff_id) {
                        return $carry;
                    }

                    if ($change->tariff->getting_profit === Tariff::MONTHLY) {
                        return $change;
                    }

                    if (
                        $carry->tariff->getting_profit === Tariff::MONTHLY &&
                        $change->tariff->getting_profit === Tariff::AT_THE_END
                    ) {
                        return $change;
                    }

                    return $carry;
                }

                if ($change->type === ContractChangeType::prolongation) {
                    return $change;
                }

                return $carry;
            },
            null);
    }

    private function paymentsSum(PaymentType $type): int
    {
        $sum = $this->payments
            ->where('type', $type)
            ->where('status', PaymentStatus::processed)
            ->reduce(function ($sum, $payment) {
                if ($sum instanceof Payment) {
                    $sum = $sum->amount->raw();
                }

                return $sum + $payment->amount->raw();
            });

        return $sum ?? 0;
    }
}
