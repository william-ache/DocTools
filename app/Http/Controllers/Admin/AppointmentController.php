<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::where('tenant_id', Auth::user()->tenant_id)
            ->with(['patient', 'consultorio']);

        // Filtrar por rango de fechas (enviado por FullCalendar)
        if ($request->has('start') && $request->has('end')) {
            $query->whereBetween('start_time', [
                date('Y-m-d H:i:s', strtotime($request->start)),
                date('Y-m-d H:i:s', strtotime($request->end))
            ]);
        }

        // Filtrar por consultorio
        if ($request->has('consultorio_id') && $request->consultorio_id != 'all') {
            $query->where('consultorio_id', $request->consultorio_id);
        }

        // Filtrar por estado
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $appointments = $query->get();

        $events = $appointments->map(function ($app) {
            return [
                'id' => $app->id,
                'title' => $app->patient ? $app->patient->name : 'Sin paciente',
                'start' => $app->start_time->toDateTimeString(),
                'end' => $app->end_time->toDateTimeString(),
                'status' => $app->status,
                'extendedProps' => [
                    'patient_id' => $app->patient_id,
                    'consultorio_id' => $app->consultorio_id,
                    'status' => $app->status,
                    'notes' => $app->notes,
                    'consultorio_name' => $app->consultorio ? $app->consultorio->name : '',
                ]
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'consultorio_id' => 'required|exists:consultorios,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'notes' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;

        $appointment = Appointment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cita agendada correctamente',
            'appointment' => $appointment
        ]);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'consultorio_id' => 'sometimes|exists:consultorios,id',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'notes' => 'nullable|string',
            'status' => 'sometimes|string',
        ]);

        $appointment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cita actualizada correctamente',
            'appointment' => $appointment
        ]);
    }

    public function destroy($id)
    {
        $appointment = Appointment::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cita eliminada correctamente'
        ]);
    }
}
