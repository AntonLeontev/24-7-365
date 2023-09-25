<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'payments_start',
        'organization_title',
        'inn',
        'kpp',
        'ogrn',
        'director',
        'director_genitive',
        'accountant',
        'legal_address',
        'actual_address',
        'payment_account',
        'correspondent_account',
        'bik',
        'bank',
        'phone',
        'email',
    ];
}
