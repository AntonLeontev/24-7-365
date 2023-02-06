<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tariff extends Model
{
    use HasFactory;
	use SoftDeletes;


	public const MONTHLY = 1;
	public const AT_THE_END = 2;


	protected $fillable = [
		'title',
		'annual_rate',
		'duration_month',
		'min_amount',
		'max_amount',
		'getting_profit',
		'getting_deposit',
	];
	
	public function contracts(): HasMany
	{
		return $this->hasMany(Contract::class);
	}
}
