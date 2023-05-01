<?php

namespace App\Models;

use App\Casts\AmountCast;
use App\Enums\ContractChangeStatus;
use App\Enums\ContractChangeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractChange extends Model
{
    use HasFactory;
    use SoftDeletes;

	
    protected $casts = [
        'amount' => AmountCast::class,
        'status' => ContractChangeStatus::class,
        'type' => ContractChangeType::class,
        'starts_at' => 'datetime'
    ];

    protected $fillable = [
        'contract_id',
        'type',
        'tariff_id',
        'status',
        'amount',
        'starts_at',
        'duration',
    ];


    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }

    public function name(): string
    {
        return match (true) {
            $this->type === self::TYPE_INCREASE_AMOUNT => 'Увеличение суммы',
            $this->type === self::TYPE_CHANGE_TARIFF => 'Смена тарифа',
            $this->type === self::TYPE_PROLONGATION => 'Атопродление',
            $this->type === self::TYPE_INITIAL => 'Первичный',
        };
    }
}
