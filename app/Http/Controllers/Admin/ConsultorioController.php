<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\Servicio;
use App\Models\MetodoPago;
use App\Models\WorkingHour;
use Illuminate\Http\Request;

class ConsultorioController extends Controller
{
    public function index()
    {
        $consultorios = Consultorio::where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->with(['servicios', 'metodosPago'])
            ->get();
        return view('admin.consultorios.index', compact('consultorios'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        $metodos = MetodoPago::all();
        return view('admin.consultorios.create', compact('servicios', 'metodos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'nullable|string|max:20',
            'indications' => 'nullable|string',
            
            // Config de citas
            'rest_time_between_appointments' => 'nullable|integer',
            'max_days_anticipation' => 'nullable|integer',
            'standard_appointment_duration' => 'nullable|integer',
            'calendar_interval' => 'nullable|integer',
            'timezone' => 'nullable|string',
            'whatsapp_reminders' => 'nullable|boolean',
            'accept_bookings' => 'nullable|boolean',
            'booking_notifications' => 'nullable|boolean',

            // Relaciones
            'servicios' => 'nullable|array',
            'metodos' => 'nullable|array',
        ]);

        // Inyectar tenant_id y checkbox fix
        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['whatsapp_reminders'] = $request->has('whatsapp_reminders');
        $validated['accept_bookings'] = $request->has('accept_bookings');
        $validated['booking_notifications'] = $request->has('booking_notifications');

        $consultorio = Consultorio::create($validated);

        if ($request->has('servicios')) {
            $consultorio->servicios()->sync($request->servicios);
        }

        if ($request->has('metodos')) {
            $consultorio->metodosPago()->sync($request->metodos);
        }

        // Guardar horarios
        if ($request->has('horarios')) {
            foreach ($request->horarios as $day => $data) {
                if (isset($data['active'])) {
                    $consultorio->workingHours()->create([
                        'tenant_id' => auth()->user()->tenant_id,
                        'day_of_week' => $day,
                        'start_time' => $data['start'],
                        'end_time' => $data['end'],
                        'is_active' => true
                    ]);
                }
            }
        }

        return redirect()->route('consultorios.index')
            ->with('success', 'Consultorio creado exitosamente con sus configuraciones.');
    }

    public function edit(Consultorio $consultorio)
    {
        if ($consultorio->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $servicios = Servicio::all();
        $metodos = MetodoPago::all();
        $consultorio->load(['servicios', 'metodosPago', 'workingHours']);
        return view('admin.consultorios.edit', compact('consultorio', 'servicios', 'metodos'));
    }

    public function update(Request $request, Consultorio $consultorio)
    {
        if ($consultorio->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'nullable|string|max:20',
            'indications' => 'nullable|string',
            'rest_time_between_appointments' => 'nullable|integer',
            'max_days_anticipation' => 'nullable|integer',
            'standard_appointment_duration' => 'nullable|integer',
            'calendar_interval' => 'nullable|integer',
            'timezone' => 'nullable|string',
            'whatsapp_reminders' => 'nullable|boolean',
            'accept_bookings' => 'nullable|boolean',
            'booking_notifications' => 'nullable|boolean',
            'servicios' => 'nullable|array',
            'metodos' => 'nullable|array',
        ]);

        // Fix checkbox values if missing from request
        $validated['whatsapp_reminders'] = $request->has('whatsapp_reminders');
        $validated['accept_bookings'] = $request->has('accept_bookings');
        $validated['booking_notifications'] = $request->has('booking_notifications');

        $consultorio->update($validated);

        $consultorio->servicios()->sync($request->servicios ?? []);
        if ($request->has('metodos')) {
            $consultorio->metodosPago()->sync($request->metodos);
        }

        // Actualizar horarios
        if ($request->has('horarios')) {
            $consultorio->workingHours()->delete();
            foreach ($request->horarios as $day => $data) {
                if (isset($data['active'])) {
                    $consultorio->workingHours()->create([
                        'tenant_id' => auth()->user()->tenant_id,
                        'day_of_week' => $day,
                        'start_time' => $data['start'],
                        'end_time' => $data['end'],
                        'is_active' => true
                    ]);
                }
            }
        }

        return redirect()->route('consultorios.index')
            ->with('success', 'Consultorio actualizado exitosamente.');
    }

    public function destroy(Consultorio $consultorio)
    {
        if ($consultorio->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $consultorio->delete();
        return redirect()->route('consultorios.index')
            ->with('success', 'Consultorio eliminado exitosamente.');
    }

    public function toggleBooking(Consultorio $consultorio)
    {
        $consultorio->update([
            'is_online_booking_enabled' => !$consultorio->is_online_booking_enabled
        ]);
        return back();
    }
}
