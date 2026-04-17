<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $pacientes = Patient::latest()->get();
        return view('admin.pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('admin.pacientes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|uuid',
            'name' => 'required',
            'id_number' => 'nullable',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'antecedentes' => 'nullable',
        ]);

        Patient::create($validated);

        return redirect()->route('pacientes.index')->with('success', 'Paciente registrado exitosamente.');
    }

    public function edit(Patient $paciente)
    {
        return view('admin.pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Patient $paciente)
    {
        $validated = $request->validate([
            'name' => 'required',
            'id_number' => 'nullable',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'antecedentes' => 'nullable',
        ]);

        $paciente->update($validated);

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado.');
    }

    public function destroy(Patient $paciente)
    {
        $paciente->delete();
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado.');
    }
}
