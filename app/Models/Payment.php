<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Jobs\SyncPaymentInAccountingSystem;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;

    protected $fillable = [
        'account_id',
        'contract_id',
        'amount',
        'type',
        'status',
        'planned_at',
        'paid_at',
        'description',
        'is_body',
    ];

    protected $casts = [
        'amount' => AmountCast::class,
        'status' => PaymentStatus::class,
        'type' => PaymentType::class,
        'planned_at' => 'datetime',
        'paid_at' => 'datetime',
        'is_body' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function profitabilities(): HasMany
    {
        return $this->hasMany(Profitability::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            $lastNumber = $payment->latest()->first()?->number;

            $payment->number = ($lastNumber ?? 0) + 1;

            if ($payment->type === PaymentType::debet) {
                $date = now()->format('d.m.Y');
                $payment->description = "Оплата по счету №{$payment->number} от {$date}. ".$payment->description;
            }
        });

        static::created(function (Payment $payment) {
            if ($payment->type === PaymentType::debet) {
                return;
            }

            if (app()->isProduction()) {
                dispatch(new SyncPaymentInAccountingSystem($payment));
            }
        });

        static::updated(function (Payment $payment) {
            if ($payment->type === PaymentType::debet) {
                return;
            }

            if (app()->isProduction()) {
                dispatch(new SyncPaymentInAccountingSystem($payment));
            }
        });
    }
}
