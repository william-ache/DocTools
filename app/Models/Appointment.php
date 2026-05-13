<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'consultorio_id',
        'patient_id',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
