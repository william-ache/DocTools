<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Consultorio extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'phone',
        'indications',
        'rest_time_between_appointments',
        'max_days_anticipation',
        'standard_appointment_duration',
        'calendar_interval',
        'timezone',
        'whatsapp_reminders',
        'accept_bookings',
        'booking_notifications',
    ];


    public function workingHours()
    {
        return $this->hasMany(WorkingHour::class);
    }

    public function metodosPago(): BelongsToMany
    {
        return $this->belongsToMany(MetodoPago::class, 'consultorio_metodo_pago');
    }

    public function servicios(): BelongsToMany
    {
        return $this->belongsToMany(Servicio::class, 'consultorio_servicio');
    }
}
