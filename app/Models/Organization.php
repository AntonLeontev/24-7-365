<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;

	
    protected $fillable = [
        'id',
        'user_id',
        'title',
        'inn',
        'kpp',
        'ogrn',
        'legal_address',
        'director',
        'directors_post',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
