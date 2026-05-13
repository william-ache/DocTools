<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MetodoPago extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'metodos_pago';

    protected $fillable = [
        'name',
        'type',
        'color',
        'icon',
        'details',
        'is_active',
    ];


    public function consultorios(): BelongsToMany
    {
        return $this->belongsToMany(Consultorio::class, 'consultorio_metodo_pago');
    }
}
