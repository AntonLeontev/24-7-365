<?php

namespace App\Models;

use App\Casts\AmountCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profitability extends Model
{
    use HasFactory;


    protected $fillable = [
        'contract_id',
        'payment_id',
        'amount',
        'accrued_at'
    ];

    protected $casts = [
        'amount' => AmountCast::class,
        'accrued_at' => 'datetime',
    ];

	
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
