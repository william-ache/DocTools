<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Servicio extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'price',
        'price_from',
        'duration',
        'color',
        'icon',
        'is_active',
    ];


    public function consultorios(): BelongsToMany
    {
        return $this->belongsToMany(Consultorio::class, 'consultorio_servicio');
    }
}
