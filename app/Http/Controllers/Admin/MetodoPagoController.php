<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use Illuminate\Http\Request;

class MetodoPagoController extends Controller
{
    public function index()
    {
        $metodos = MetodoPago::latest()->get();
        return view('admin.metodos.index', compact('metodos'));
    }

    public function create()
    {
        return view('admin.metodos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'icon' => 'required|string',
            'details' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        MetodoPago::create($validated);

        return redirect()->route('metodos.index')->with('success', 'Método de pago registrado.');
    }

    public function edit(MetodoPago $finanza)
    {
        // Notice the route parameter is 'finanza' but the model is MetodoPago
        $metodo = $finanza;
        return view('admin.metodos.edit', compact('metodo'));
    }

    public function update(Request $request, MetodoPago $finanza)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'icon' => 'required|string',
            'details' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $finanza->update($validated);

        return redirect()->route('metodos.index')->with('success', 'Método de pago actualizado.');
    }

    public function destroy(MetodoPago $finanza)
    {
        $finanza->delete();
        return redirect()->route('metodos.index')->with('success', 'Método de pago eliminado.');
    }

    public function toggleStatus(MetodoPago $finanza)
    {
        $finanza->update([
            'is_active' => !$finanza->is_active
        ]);
        return back();
    }
}
