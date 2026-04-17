<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultorio;
use App\Models\Servicio;
use App\Models\MetodoPago;
use Illuminate\Http\Request;

class ConsultorioController extends Controller
{
    public function index()
    {
        $consultorios = Consultorio::latest()->with(['servicios', 'metodosPago'])->get();
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
            'phone' => 'nullable|string|max:20',
            'indications' => 'nullable|string',
            
            // Config de citas
            'rest_time_between_appointments' => 'nullable|integer',
            'max_days_anticipation' => 'nullable|integer',
            'standard_appointment_duration' => 'nullable|integer',
            'timezone' => 'nullable|string',
            'whatsapp_reminders' => 'boolean',
            'accept_bookings' => 'boolean',
            'booking_notifications' => 'boolean',

            // Relaciones
            'servicios' => 'nullable|array',
            'metodos' => 'nullable|array',
        ]);

        $consultorio = Consultorio::create($validated);

        if ($request->has('servicios')) {
            $consultorio->servicios()->sync($request->servicios);
        }

        if ($request->has('metodos')) {
            $consultorio->metodosPago()->sync($request->metodos);
        }

        return redirect()->route('consultorios.index')
            ->with('success', 'Consultorio creado exitosamente con sus configuraciones.');
    }

    public function edit(Consultorio $consultorio)
    {
        $servicios = Servicio::all();
        $metodos = MetodoPago::all();
        $consultorio->load(['servicios', 'metodosPago']);
        return view('admin.consultorios.edit', compact('consultorio', 'servicios', 'metodos'));
    }

    public function update(Request $request, Consultorio $consultorio)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'indications' => 'nullable|string',
            'whatsapp_reminders' => 'boolean',
            'accept_bookings' => 'boolean',
            'booking_notifications' => 'boolean',
            'servicios' => 'nullable|array',
            'metodos' => 'nullable|array',
        ]);

        // Fix checkbox values if missing from request
        $validated['whatsapp_reminders'] = $request->has('whatsapp_reminders');
        $validated['accept_bookings'] = $request->has('accept_bookings');
        $validated['booking_notifications'] = $request->has('booking_notifications');

        $consultorio->update($validated);

        $consultorio->servicios()->sync($request->servicios ?? []);
        $consultorio->metodosPago()->sync($request->metodos ?? []);

        return redirect()->route('consultorios.index')
            ->with('success', 'Consultorio actualizado exitosamente.');
    }

    public function destroy(Consultorio $consultorio)
    {
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
