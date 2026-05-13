<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'id_number',
        'birth_date',
        'gender',
        'allergies',
        'antecedentes',
        'phone',
        'email',
        'address',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'allergies' => 'array',
    ];


    /**
     * Get the consultations for the patient.
     */
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }
}
