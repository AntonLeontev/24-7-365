<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'account_id',
        'contract_id',
        'amount',
        'type',
        'status',
        'planned_at',
        'paid_at',
        'description',
    ];

    protected $casts = [
        'amount' => AmountCast::class,
        'status' => PaymentStatus::class,
        'type' => PaymentType::class,
        'planned_at' => 'datetime',
        'paid_at' => 'datetime',
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
}
