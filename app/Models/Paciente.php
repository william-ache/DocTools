<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'id_number',
        'birth_date',
        'gender',
        'address',
        'medical_notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->last_name}";
    }
}
