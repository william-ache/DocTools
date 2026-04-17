<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'price_from',
        'duration',
        'is_active',
        'icon',
        'color',
    ];

    protected $casts = [
        'price_from' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function consultorios()
    {
        return $this->belongsToMany(Consultorio::class, 'consultorio_servicio');
    }
}
