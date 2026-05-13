<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WorkingHour extends Model
{
    use HasUuids;

    protected $fillable = [
        'consultorio_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active'
    ];

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class);
    }
