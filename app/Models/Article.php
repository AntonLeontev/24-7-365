<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

	protected $fillable = [
		'title',
		'thumbnail',
		'slug',
		'text',
		'active',
	];

	protected $perPage = 20;

	protected $casts = [
		'active' => 'boolean',
	];
}
