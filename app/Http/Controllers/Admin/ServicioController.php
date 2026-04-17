<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::latest()->get();
        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('admin.servicios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_from' => 'boolean',
            'duration' => 'required|integer|min:5',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['price_from'] = $request->has('price_from');

        Servicio::create($validated);

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_from' => 'boolean',
            'duration' => 'required|integer|min:5',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['price_from'] = $request->has('price_from');

        $servicio->update($validated);

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
    }

    public function toggleStatus(Servicio $servicio)
    {
        $servicio->update([
            'is_active' => !$servicio->is_active
        ]);
        return back();
    }
}
