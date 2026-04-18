<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() && !$request->has('q')) {
            $query = Patient::where('tenant_id', auth()->user()->tenant_id);

            // Search
            if ($request->has('search') && $request->input('search.value')) {
                $search = $request->input('search.value');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                      ->orWhere('id_number', 'LIKE', "%$search%")
                      ->orWhere('email', 'LIKE', "%$search%")
                      ->orWhere('phone', 'LIKE', "%$search%");
                });
            }

            $totalData = $query->count();
            $totalFiltered = $totalData;

            // Order
            $columns = ['name', 'phone', 'id_number', 'email'];
            $orderColumn = $columns[$request->input('order.0.column')] ?? 'name';
            $orderDir = $request->input('order.0.dir') ?? 'asc';
            $query->orderBy($orderColumn, $orderDir);

            // Pagination
            $start = $request->input('start');
            $length = $request->input('length');
            $pacientes = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($pacientes as $p) {
                $data[] = [
                    'name' => '<a href="'.route('pacientes.show', $p).'" class="text-sm font-black text-primary hover:underline">'.$p->name.'</a>',
                    'phone' => '<span class="text-xs font-bold text-on-surface-variant">'.($p->phone ?? 'S/N').'</span>',
                    'id_number' => '<span class="text-xs font-bold text-on-surface-variant">'.($p->id_number ?? 'No definido').'</span>',
                    'email' => '<span class="text-xs font-bold text-on-surface-variant">'.($p->email ?? 'No definido').'</span>',
                    'actions' => '<div class="text-right"><a href="'.route('pacientes.show', $p).'" class="inline-flex items-center gap-2 text-primary font-bold text-xs hover:underline"><i class="fa-solid fa-eye text-sm"></i> Ver</a></div>',
                    'DT_RowAttr' => [
                        'onclick' => "window.location='".route('pacientes.show', $p)."'",
                        'class' => "hover:bg-surface-container-low/50 transition-colors group cursor-pointer"
                    ]
                ];
            }

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalData),
                "data" => $data
            ]);
        }

        $pacientes = Patient::where('tenant_id', auth()->user()->tenant_id)->latest()->limit(10)->get();
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

    public function show(Patient $paciente)
    {
        $templates = \App\Models\Template::where('tenant_id', auth()->user()->tenant_id)
            ->where('is_active', true)
            ->get();
            
        return view('admin.pacientes.show', compact('paciente', 'templates'));
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

    public function search(Request $request)
    {
        $search = $request->input('q');
        $pacientes = Patient::where('tenant_id', auth()->user()->tenant_id)
            ->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                      ->orWhere('id_number', 'LIKE', "%$search%");
            })
            ->limit(20)
            ->get();

        return response()->json([
            'results' => $pacientes->map(function($p) {
                return [
                    'id' => $p->id,
                    'text' => $p->name . " (" . ($p->id_number ?? 'S/C') . ")"
                ];
            })
        ]);
    }
}
