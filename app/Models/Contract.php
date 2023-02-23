<?php

namespace App\Models;

use App\Casts\AmountCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory;
    use SoftDeletes;


    public const TERMINATED = 0;
    public const ACTIVE = 1;


    protected $fillable = [
        'user_id',
        'organization_id',
        'tariff_id',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => AmountCast::class,
    ];													


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

	public function account(): HasManyThrough
	{
		return $this->hasManyThrough(Account::class, Organization::class);
	}

    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
