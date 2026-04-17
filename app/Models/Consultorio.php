<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'indications',
        'is_online_booking_enabled',
        'lat',
        'lng',
        'rest_time_between_appointments',
        'max_days_anticipation',
        'standard_appointment_duration',
        'timezone',
        'whatsapp_reminders',
        'accept_bookings',
        'booking_notifications'
    ];

    protected $casts = [
        'is_online_booking_enabled' => 'boolean',
        'whatsapp_reminders' => 'boolean',
        'accept_bookings' => 'boolean',
        'booking_notifications' => 'boolean',
    ];

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'consultorio_servicio');
    }

    public function metodosPago()
    {
        return $this->belongsToMany(MetodoPago::class, 'consultorio_metodo_pago');
    }
}
