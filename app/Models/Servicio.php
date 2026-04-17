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
        'tenant_id',
        'name',
        'description',
        'price',
        'price_from',
        'duration',
        'color',
        'icon',
        'is_active',
    ];

    /**
     * Get the tenant that owns the service.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function consultorios(): BelongsToMany
    {
        return $this->belongsToMany(Consultorio::class, 'consultorio_servicio');
    }
}
