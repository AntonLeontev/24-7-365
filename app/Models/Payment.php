<?php

namespace App\Models;

use App\Casts\AmountCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;


    /**
     * Входящий платеж
     */
    public const TYPE_DEBET = 1;

    /**
     * Исходящий платеж
     */
    public const TYPE_CREDIT = 2;

    /**
     * Платеж ожидается
     */
    public const STATUS_PENDING = 0;

    /**
     * Платеж проведен банком
     */
    public const STATUS_PROCESSED = 1;
    

    protected $dates = [
        'planned_at',
        'paid_at',
    ];

    protected $fillable = [
        'account_id',
        'contract_id',
        'amount',
        'type',
        'status',
        'planned_at',
        'paid_at'
    ];

    protected $casts = [
        'amount' => AmountCast::class,
    ];


    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}
