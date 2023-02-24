<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Smscode extends Model
{
    use HasFactory;
    
    protected  $table='smscodes';
    /**
     * @var ["Int"] Opertaion Type with SMS
     */
    
    public const CODE_LENGTH = 5;
 
    /**
     * @var ["Int"] Opertaion Type with SMS
     */
    
    public const PHONE_CONFIRMATION = 1;
    
    
    /**
     * @var ["Int"] Status of SMS
     */
 
    public const STATUS_PENDING = 1;
    
    /**
     * @var ["Int"] Seconds
     */
    
    public const EXPIRATION_DATE = 60*10;
    
    
    
    protected $fillable = [
        'code',
        'operation_type',
        'user_id',
        'phone'
   
    ];
    
 
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    
    
    
}
