<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'diagnosis',
        'treatment',
        'notes',
    ];

    /**
     * Get the tenant that owns the consultation.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the patient associated with the consultation.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
