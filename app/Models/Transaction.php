<?php

namespace App\Models;

use App\Casts\AmountCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


	protected $fillable = [
		'operation_id',
		'direction',
		'purpose',
		'amount',
		'currency',
		'payer_account',
		'payer_name',
		'payer_inn',
		'payer_kpp',
		'payer_bank_name',
		'payer_bank_bic',
		'payer_bank_corr_account',
		'payee_account',
		'payee_name',
		'payee_inn',
		'payee_kpp',
		'payee_bank_name',
		'payee_bank_bic',
		'payee_bank_corr_account',
	];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
		'amount' => AmountCast::class,
	];
}
