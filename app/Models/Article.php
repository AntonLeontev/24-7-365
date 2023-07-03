<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'message_id',
        'text',
        'from',
        'photo',
        'photo_height',
        'photo_width',
    ];

    protected $perPage = 20;

    protected $casts = [];
}
