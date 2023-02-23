<?php

namespace App\Models;

use App\Casts\AmountCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory;
    use SoftDeletes;


    public const TERMINATED     = 0;// Прерван клиентом
    public const ACTIVE         = 1;// В работе
    public const CANCELED       = 2;// Клиент нажал отмену, но выплата еще не сделана
    public const PENDING        = 3;// Ожидает оплаты от клиента
    public const FINISHED       = 4;// Успешно выполнен


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

    public function income(): int
    {
        $sum = $this->payments
            ->where('type', Payment::TYPE_DEBET)
            ->where('status', Payment::STATUS_PROCESSED)
            ->reduce(function ($sum, $payment) {
                if ($sum instanceof Payment) {
                    $sum = $sum->amount->raw();
                }
                return $sum + $payment->amount->raw();
            });
        
        return $sum ?? 0;
    }

    public function outgoing(): int
    {
        $sum = $this->payments
            ->where('type', Payment::TYPE_CREDIT)
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
