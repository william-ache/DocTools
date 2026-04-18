<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cobro;
use App\Models\MetodoPago;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\EmployeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        
        $cobros = Cobro::where('tenant_id', $tenantId)
            ->with(['patient', 'metodoPago'])
            ->latest('payment_date')
            ->get();
            
        $employeePayments = EmployeePayment::where('tenant_id', $tenantId)
            ->with('employee')
            ->latest('payment_date')
            ->get();
            
        $metodos = MetodoPago::where('tenant_id', $tenantId)->get();
        $pacientes = Patient::where('tenant_id', $tenantId)->orderBy('name')->get();
        $empleados = Employee::where('tenant_id', $tenantId)->where('is_active', true)->orderBy('name')->get();

        return view('admin.finanzas.index', compact('cobros', 'employeePayments', 'metodos', 'pacientes', 'empleados'));
    }

    public function storeEmployeePayment(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'concept' => 'required|string|max:255',
            'reference' => 'nullable|string',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;
        $validated['status'] = 'paid';

        EmployeePayment::create($validated);

        return back()->with('success', 'Pago al personal registrado correctamente.');
    }

    public function destroyEmployeePayment($id)
    {
        $payment = EmployeePayment::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $payment->delete();

        return back()->with('success', 'Registro de pago eliminado.');
    }

    public function storeCobro(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'metodo_pago_id' => 'required|exists:metodos_pago,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['tenant_id'] = Auth::user()->tenant_id;
        $validated['status'] = 'completado';

        Cobro::create($validated);

        return back()->with('success', 'Cobro registrado correctamente.');
    }

    public function updateCobro(Request $request, $id)
    {
        $cobro = Cobro::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'metodo_pago_id' => 'required|exists:metodos_pago,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $cobro->update($validated);

        return back()->with('success', 'Cobro actualizado correctamente.');
    }

    public function destroyCobro($id)
    {
        $cobro = Cobro::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $cobro->delete();

        return back()->with('success', 'Cobro eliminado.');
    }
}
