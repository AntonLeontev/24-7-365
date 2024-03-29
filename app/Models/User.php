<?php

namespace App\Models;

use App\Enums\ContractStatus;
use App\ValueObjects\Amount;
use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use PasswordsCanResetPassword;

    protected $fillable = [
        'email',
        'email_verified_at',
        'password',
        'phone',
        'phone_verified_at',
        'first_name',
        'last_visit_at',
        'is_blocked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_visit_at' => 'datetime',
        'is_blocked' => 'boolean',
    ];

    public function contractsAmount(): Amount
    {
        $amount = $this->contracts
            ->whereIn('status', [ContractStatus::active, ContractStatus::canceled])
            ->reduce(function ($sum, $contract) {
                return $sum += $contract->amount->raw();
            }, 0);

        return new Amount($amount);
    }

    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function account(): HasOneThrough
    {
        return $this->hasOneThrough(Account::class, Organization::class);
    }
}
