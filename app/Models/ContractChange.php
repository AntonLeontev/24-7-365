<?php

namespace App\Models;

use App\Casts\AmountCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractChange extends Model
{
    use HasFactory;


    /**
     * Актуальное состояние договора, которое исполняется
     */
    public const STATUS_ACTUAL = 1;

    /**
     * Изменения инициированы пользователем, но оплаты не было (либо другого подтверждения)
     */
    public const STATUS_PENDING = 2;

    /**
     * Пользователь подвердил изменения (доплата получена), но расчетный период не завершен
     */
    public const STATUS_WAITING_FOR_PERIOD_END = 3;

    /**
     * Прошедшее состояние
     */
    public const STATUS_PAST = 4;

    /**
     * Первое состояние. Используется при поступлении первой оплаты
     */
    public const TYPE_INITIAL = 0;

    /**
     * Увеличение суммы договора
     */
    public const TYPE_INCREASE_AMOUNT = 1;

    /**
     * Изменение тарифа
     */
    public const TYPE_CHANGE_TARIFF = 2;

    /**
     * Автопродление
     */
    public const TYPE_PROLONGATION = 3;


    protected $dates = [
        'starts_at',
    ];

	protected $casts = [
		'amount' => AmountCast::class,
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
