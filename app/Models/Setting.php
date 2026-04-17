<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'primary_color',
        'enabled_modules',
        'favorite_modules',
    ];

    protected $casts = [
        'enabled_modules' => 'array',
        'favorite_modules' => 'array',
    ];
}
