<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        if ($request->ajax()) {
            $baseQuery = Employee::where('tenant_id', $tenantId);
            $recordsTotal = (clone $baseQuery)->count();
            
            $query = (clone $baseQuery)->withCount('payments');
            
            // Search
            if ($request->has('search') && $request->input('search.value')) {
                $search = $request->input('search.value');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                      ->orWhere('position', 'LIKE', "%$search%")
                      ->orWhere('email', 'LIKE', "%$search%")
                      ->orWhere('phone', 'LIKE', "%$search%");
                });
            }

            $recordsFiltered = (clone $query)->count();

            // Order
            $columns = ['name', 'email', 'salary', 'is_active', 'id'];
            $orderIdx = $request->input('order.0.column');
            $orderColumn = $columns[$orderIdx] ?? 'name';
            $orderDir = $request->input('order.0.dir') ?? 'asc';
            $query->orderBy($orderColumn, $orderDir);

            // Pagination
            $start = $request->input('start');
            $length = $request->input('length');
            $employees = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($employees as $e) {
                $employeeJson = htmlspecialchars(json_encode($e), ENT_QUOTES, 'UTF-8');
                $color = $e->is_active ? 'bg-green-50 text-green-600' : 'bg-surface-container text-outline';
                $text = $e->is_active ? 'Activo' : 'Inactivo';

                $data[] = [
                    'name_position' => '<div>
                        <div class="text-sm font-black text-on-surface">' . $e->name . '</div>
                        <div class="text-[9px] font-black text-primary uppercase tracking-widest">' . ($e->position ?? 'Sin Cargo') . '</div>
                    </div>',
                    'contact' => '<div class="space-y-0.5">
                        <div class="text-xs font-bold text-outline">' . ($e->email ?? 'N/A') . '</div>
                        <div class="text-[10px] font-medium text-outline/60">' . ($e->phone ?? '') . '</div>
                    </div>',
                    'salary_currency' => '<span class="text-sm font-black text-on-surface">$' . number_format($e->salary, 2) . '</span>',
                    'status' => '<span class="text-[8px] font-black uppercase px-2 py-0.5 rounded-full ' . $color . '">' . $text . '</span>',
                    'actions' => '<div class="flex items-center justify-end gap-2">
                        <button onclick=\'openEditEmployeeModal(' . $employeeJson . ')\' class="w-8 h-8 rounded-lg bg-primary/5 text-primary hover:bg-primary hover:text-white transition-all flex items-center justify-center text-xs">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="' . route('employees.destroy', $e->id) . '" method="POST" onsubmit="return confirm(\'¿Eliminar empleado?\')" class="inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="w-8 h-8 rounded-lg bg-error/5 text-error hover:bg-error hover:text-white transition-all flex items-center justify-center text-xs">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>'
                ];
            }

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($recordsTotal),
                "recordsFiltered" => intval($recordsFiltered),
                "data" => $data
            ]);
        }

        $employees = Employee::where('tenant_id', $tenantId)->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'salary' => 'required|numeric|min:0',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;
        $validated['is_active'] = true;

        Employee::create($validated);

        return back()->with('success', 'Empleado registrado correctamente.');
    }

    public function update(Request $request, string $id)
    {
        $employee = Employee::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'salary' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $employee->update($validated);

        return back()->with('success', 'Datos del empleado actualizados.');
    }

    public function destroy(string $id)
    {
        $employee = Employee::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $employee->delete();

        return back()->with('success', 'Empleado eliminado.');
    }
}
