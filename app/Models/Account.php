<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
		'organization_id',
        'payment_account',
        'correspondent_account',
        'bik',
        'bank',
        'status',
    ];

	public function payments(): HasMany
	{
		return $this->hasMany(Payment::class);
	}

	public function organization(): BelongsTo
	{
		return $this->belongsTo(Organization::class);
	}
}
