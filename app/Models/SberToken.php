<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SberToken extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'access_token',
        'refresh_token',
        'token_type',
        'expires_in',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
