<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'domain',
        'database',
        'plan',
        'is_active',
    ];

    /**
     * Get the users for the tenant.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the patients for the tenant.
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }
}
