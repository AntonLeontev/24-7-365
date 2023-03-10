<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;


    // Входящий платеж
    public const TYPE_DEBET = 1;

    // Исходящий платеж
    public const TYPE_CREDIT = 2;


    protected $fillable = [
        'account_id',
        'contract_id',
        'amount',
        'type',
        'status',
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
