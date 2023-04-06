<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Enums\ContractChangeStatus;
use App\Enums\ContractStatus;
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
    ];

    protected $dates = [
        'paid_at',
    ];

    protected $casts = [
        'amount' => AmountCast::class,
        'paid_at' => 'date:d m Y',
        'status' => ContractStatus::class,
    ];


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

    public function income(): int
    {
        return $this->paymentsSum(Payment::TYPE_DEBET);
    }

    public function outgoing(): int
    {
        return $this->paymentsSum(Payment::TYPE_CREDIT);
    }

    public function duration(): int
    {
        return $this->contractChanges->sum('duration');
    }

    public function isChanging(): bool
    {
        if ($this->status->value !== ContractStatus::active->value) {
            return false;
        }
        
        return $this->contractChanges->last()->status->value === ContractChangeStatus::pending->value ||
            $this->contractChanges->last()->status->value === ContractChangeStatus::waitingPeriodEnd->value;
    }

    private function paymentsSum(int $type): int
    {
        $sum = $this->payments
            ->where('type', $type)
            ->where('status', Payment::STATUS_PROCESSED)
            ->reduce(function ($sum, $payment) {
                if ($sum instanceof Payment) {
                    $sum = $sum->amount->raw();
                }

                return $sum + $payment->amount->raw();
            });

        return $sum ?? 0;
    }
}
