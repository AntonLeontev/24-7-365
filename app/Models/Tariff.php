<?php

namespace App\Models;

use App\Casts\AmountCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tariff extends Model
{
    use HasFactory;
    use SoftDeletes;

    //Выплата доходности
    public const MONTHLY = 1;

    public const AT_THE_END = 2;

    protected $fillable = [
        'title',
        'annual_rate',
        'duration',
        'min_amount',
        'max_amount',
        'getting_profit',
        'getting_deposit',
        'is_active',
    ];

    protected $casts = [
        'min_amount' => AmountCast::class,
        'max_amount' => AmountCast::class,
        'is_active' => 'boolean',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
