@extends('layouts.admin')

@section('content')
<div class="p-4 md:p-10 space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-primary tracking-tight">Finanzas</h1>
            <p class="text-outline font-medium">Gestión de ingresos, cobros y métodos de pago.</p>
        </div>
        <div class="flex items-center gap-3">
             <button onclick="openCobroModal()" class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                <i class="fa-solid fa-plus"></i>
                Registrar Cobro
            </button>
             <a href="{{ route('metodos.create') }}" class="flex items-center gap-2 bg-surface-container-highest text-primary px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-white transition-all border border-surface-container">
                <i class="fa-solid fa-credit-card"></i>
                Nuevo Método
            </a>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-surface-container shadow-sm group hover:border-primary/20 transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-green-500/10 flex items-center justify-center text-green-600">
                    <i class="fa-solid fa-money-bill-trend-up text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-outline uppercase tracking-widest leading-none">Total Cobrados (USD)</p>
                    <h3 class="text-2xl font-black text-on-surface tracking-tighter mt-1">${{ number_format($cobros->sum('amount'), 2) }}</h3>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-full w-fit">
                <i class="fa-solid fa-arrow-up"></i>
                <span>Ingresos este mes</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-surface-container shadow-sm group hover:border-primary/20 transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                    <i class="fa-solid fa-receipt text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-outline uppercase tracking-widest leading-none">Transacciones</p>
                    <h3 class="text-2xl font-black text-on-surface tracking-tighter mt-1">{{ $cobros->count() }}</h3>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-primary bg-primary/5 px-3 py-1.5 rounded-full w-fit">
                <i class="fa-solid fa-check-double"></i>
                <span>Completadas</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-surface-container shadow-sm group hover:border-primary/20 transition-all">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary">
                    <i class="fa-solid fa-wallet text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-outline uppercase tracking-widest leading-none">Métodos Activos</p>
                    <h3 class="text-2xl font-black text-on-surface tracking-tighter mt-1">{{ $metodos->where('is_active', true)->count() }}</h3>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[10px] font-bold text-secondary bg-secondary/5 px-3 py-1.5 rounded-full w-fit">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Configurados</span>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex items-center gap-8 border-b border-surface-container px-2">
        <button onclick="switchTab('cobros')" id="tab-cobros" class="tab-btn active pb-4 text-xs font-black uppercase tracking-widest text-primary border-b-2 border-primary relative transition-all">
            Historial de Cobros
        </button>
        <button onclick="switchTab('estadisticas')" id="tab-estadisticas" class="tab-btn pb-4 text-xs font-black uppercase tracking-widest text-outline hover:text-primary transition-all relative">
            Estadísticas
        </button>
        <button onclick="switchTab('metodos')" id="tab-metodos" class="tab-btn pb-4 text-xs font-black uppercase tracking-widest text-outline hover:text-primary transition-all relative">
            Métodos de Pago
        </button>
        <button onclick="switchTab('pagos')" id="tab-pagos" class="tab-btn pb-4 text-xs font-black uppercase tracking-widest text-outline hover:text-primary transition-all relative">
            Pagos a Personal
        </button>
    </div>

    <!-- Tab Contents -->
    <div id="content-cobros" class="tab-content animate-fade-in">
        <!-- ... (existing cobros table) ... -->
        <div class="bg-white rounded-[2.5rem] border border-surface-container overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low/50">
                            <th class="px-6 py-4 text-[10px] font-black text-outline uppercase tracking-widest">Paciente</th>
                            <th class="px-6 py-4 text-[10px] font-black text-outline uppercase tracking-widest">Fecha</th>
                            <th class="px-6 py-4 text-[10px] font-black text-outline uppercase tracking-widest">Método</th>
                            <th class="px-6 py-4 text-[10px] font-black text-outline uppercase tracking-widest">Monto</th>
                            <th class="px-6 py-4 text-[10px] font-black text-outline uppercase tracking-widest">Referencia</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container">
                        @forelse($cobros as $cobro)
                        <tr class="hover:bg-surface-container-low/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-[10px] font-black uppercase">
                                        {{ substr($cobro->patient->name, 0, 2) }}
                                    </div>
                                    <span class="text-sm font-bold text-on-surface">{{ $cobro->patient->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold text-outline">
                                    {{ $cobro->payment_date->format('d/m/Y h:i A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="{{ $cobro->metodoPago->icon }} text-xs text-outline"></i>
                                    <span class="text-xs font-bold text-on-surface">{{ $cobro->metodoPago->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-primary">
                                    ${{ number_format($cobro->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 uppercase">
                                <span class="text-[10px] font-black text-outline bg-surface-container px-2 py-1 rounded-md">
                                    {{ $cobro->reference ?? '---' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-all">
                                    <button onclick='openEditCobroModal(@json($cobro))' class="no-style text-primary p-2 hover:bg-primary/10 rounded-lg">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('cobros.destroy', $cobro->id) }}" method="POST" onsubmit="return confirm('¿Eliminar registro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="no-style text-error p-2 hover:bg-error/10 rounded-lg">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center text-outline/50 font-bold uppercase tracking-widest text-[10px]">Sin registros</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Pagos Personal -->
    <div id="content-pagos" class="tab-content hidden animate-fade-in space-y-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-black text-primary tracking-tight">Pagos realizados al personal</h2>
            <button onclick="openStaffPaymentModal()" class="bg-error text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-error/10 hover:scale-105 transition-all flex items-center gap-2">
                <i class="fa-solid fa-hand-holding-dollar"></i>
                Registrar Pago
            </button>
        </div>
        <div class="bg-white rounded-[2.5rem] border border-surface-container overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low/50">
                            <th class="px-6 py-4 text-[10px] font-black text-outline uppercase tracking-widest">Empleado</th>
                            <th class="px-6 py-4 text-[10px] font-black text-outline uppercase tracking-widest">Concepto</th>
                            <th class="px-6 py-4 text-[10px] font-black text-outline uppercase tracking-widest">Fecha</th>
                            <th class="px-6 py-4 text-[10px] font-black text-outline uppercase tracking-widest">Monto</th>
                            <th class="px-6 py-4 text-[10px] font-black text-outline uppercase tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container">
                        @forelse($employeePayments as $payment)
                        <tr class="hover:bg-error/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-error/10 flex items-center justify-center text-error text-[10px] font-black uppercase">
                                        {{ substr($payment->employee->name, 0, 2) }}
                                    </div>
                                    <span class="text-sm font-bold text-on-surface">{{ $payment->employee->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-outline">{{ $payment->concept }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-outline">
                                {{ $payment->payment_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-error">-${{ number_format($payment->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('employee-payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('¿Eliminar registro de pago?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="no-style text-error/40 hover:text-error transition-all p-2">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-outline/50 font-bold uppercase tracking-widest text-[10px]">Sin pagos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="content-estadisticas" class="tab-content hidden animate-fade-in space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] border border-surface-container shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-black text-primary tracking-tight">Ingresos Mensuales</h3>
                        <p class="text-xs text-outline font-medium">Seguimiento de cobros en USD</p>
                    </div>
                </div>
                <div class="h-80 w-full">
                    <canvas id="revenueMainChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-surface-container shadow-sm">
                 <h3 class="text-lg font-black text-primary tracking-tight mb-8">Distribución por Método</h3>
                 <div class="aspect-square relative flex items-center justify-center max-w-[240px] mx-auto">
                    <canvas id="distributionMainChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-[10px] font-black text-outline uppercase">Total</span>
                        <span class="text-2xl font-black text-primary tracking-tighter">${{ number_format($cobros->sum('amount'), 0) }}</span>
                    </div>
                 </div>
            </div>
        </div>
    </div>

    <div id="content-metodos" class="tab-content hidden animate-fade-in">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left: Methods Grid -->
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($metodos as $metodo)
                    <div class="bg-white p-4 rounded-[1.75rem] border border-surface-container shadow-sm hover:border-primary/20 transition-all group relative overflow-hidden">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shadow-inner shrink-0" 
                                     style="background-color: {{ $metodo->color }}15; color: {{ $metodo->color }}">
                                    <i class="fa-solid {{ $metodo->icon }}"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-black text-on-surface tracking-tight leading-tight truncate">{{ $metodo->name }}</h3>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="w-1 h-1 rounded-full {{ $metodo->is_active ? 'bg-green-600' : 'bg-outline' }}"></span>
                                        <span class="text-[7px] font-black uppercase tracking-wider {{ $metodo->is_active ? 'text-green-600' : 'text-outline' }}">
                                            {{ $metodo->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('metodos.edit', $metodo->id) }}" class="p-1.5 rounded-lg bg-surface-container-low text-primary hover:bg-primary hover:text-white transition-all">
                                    <i class="fa-solid fa-pen-to-square text-[9px]"></i>
                                </a>
                            </div>
                        </div>

                        <div class="space-y-2 px-0.5">
                            <div class="flex items-center gap-1.5 text-on-surface-variant">
                                <i class="fa-solid fa-tag text-[8px] text-outline w-2.5"></i>
                                <p class="text-[9px] font-bold uppercase tracking-widest">{{ $metodo->type }}</p>
                            </div>
                            @if($metodo->details)
                            <div class="flex items-start gap-1.5 text-on-surface-variant">
                                <i class="fa-solid fa-circle-info text-[8px] text-outline w-2.5 mt-0.5"></i>
                                <p class="text-[9px] font-medium leading-tight line-clamp-1 text-outline/80">{{ $metodo->details }}</p>
                            </div>
                            @endif
                        </div>

                        <div class="mt-3 pt-3 border-t border-surface-container-low flex items-center justify-between">
                             <div class="flex items-center gap-2">
                                <div class="flex flex-col">
                                    <span class="text-[6px] font-black text-outline uppercase tracking-wider">Uso</span>
                                    <span class="text-[10px] font-black text-primary leading-none">{{ $cobros->where('metodo_pago_id', $metodo->id)->count() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('metodos.toggle-status', $metodo->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="relative inline-flex h-3.5 w-7 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $metodo->is_active ? 'bg-green-500' : 'bg-surface-container-highest' }}">
                                        <span aria-hidden="true" class="pointer-events-none inline-block h-2.5 w-2.5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $metodo->is_active ? 'translate-x-3.5' : 'translate-x-0' }}"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Stats Sidebar -->
            <div class="w-full lg:w-[320px] space-y-6">
                <div class="bg-white p-6 rounded-[2rem] border border-surface-container shadow-sm">
                    <h4 class="text-[10px] font-black text-outline uppercase tracking-widest mb-4">Uso de Métodos</h4>
                    <div class="aspect-square relative flex items-center justify-center">
                        <canvas id="methodsDistributionChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-surface-container shadow-sm">
                    <h4 class="text-[10px] font-black text-outline uppercase tracking-widest mb-4">Tendencia</h4>
                    <div class="h-28">
                        <canvas id="weeklyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registrar Cobro -->
<div id="cobroModal" class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div id="cobroModalContent" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-8 border-b border-surface-container flex items-center justify-between bg-surface-container-low/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                    <i class="fa-solid fa-money-bill-transfer text-lg"></i>
                </div>
            <button onclick="closeCobroModal()" class="w-8 h-8 rounded-full hover:bg-surface-container-high transition-colors text-outline">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('cobros.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <!-- Paciente -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Paciente</label>
                <select name="patient_id" class="select2-basic" required>
                    <option value="">Seleccione un paciente...</option>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id }}">{{ $paciente->name }} ({{ $paciente->id_number }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Monto -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Monto (USD)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-outline font-black text-sm">$</span>
                        <input type="number" step="0.01" name="amount" required class="w-full pl-8 pr-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm" placeholder="0.00">
                    </div>
                </div>
                <!-- Fecha -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Fecha</label>
                    <input type="datetime-local" name="payment_date" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
                </div>
            </div>

            <!-- Método -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Método de Pago</label>
                <select name="metodo_pago_id" class="select2-basic" required>
                    <option value="">Seleccione método...</option>
                    @foreach($metodos as $metodo)
                        @if($metodo->is_active)
                        <option value="{{ $metodo->id }}">{{ $metodo->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Referencia -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Referencia / No. Operación</label>
                <input type="text" name="reference" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm" placeholder="Ej: 450912">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-white py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-lg hover:scale-[1.02] transition-all">
                    Registrar Cobro
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Cobro -->
<div id="editCobroModal" class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div id="editCobroModalContent" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-8 border-b border-surface-container flex items-center justify-between bg-surface-container-low/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary tracking-tight">Editar Registro</h3>
                </div>
            </div>
            <button onclick="closeEditCobroModal()" class="w-8 h-8 rounded-full hover:bg-surface-container-high transition-colors text-outline">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editCobroForm" method="POST" class="p-8 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="currency" value="USD">
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Paciente</label>
                <select name="patient_id" id="edit_patient_id" class="select2-modal-edit w-full" required>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id }}">{{ $paciente->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Monto (USD)</label>
                    <input type="number" step="0.01" name="amount" id="edit_amount" required class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Fecha</label>
                    <input type="datetime-local" name="payment_date" id="edit_payment_date" required class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Método de Pago</label>
                <select name="metodo_pago_id" id="edit_metodo_pago_id" class="select2-modal-edit w-full" required>
                    @foreach($metodos as $metodo)
                        <option value="{{ $metodo->id }}">{{ $metodo->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Referencia</label>
                <input type="text" name="reference" id="edit_reference" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg hover:scale-[1.02] transition-all">
                    Actualizar Registro
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Registrar Pago a Personal -->
<div id="staffPaymentModal" class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div id="staffPaymentModalContent" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-8 border-b border-surface-container flex items-center justify-between bg-error/5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-error/10 rounded-xl flex items-center justify-center text-error">
                    <i class="fa-solid fa-hand-holding-dollar text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-error tracking-tight">Registrar Pago</h3>
                    <p class="text-[10px] font-black uppercase tracking-widest text-outline">Gasto Operativo / Nómina</p>
                </div>
            </div>
            <button onclick="closeStaffPaymentModal()" class="w-8 h-8 rounded-full hover:bg-surface-container-high transition-colors text-outline">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('employee-payments.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Empleado</label>
                <select name="employee_id" class="select2-modal-staff w-full" required>
                    <option value="">Seleccione un empleado...</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id }}">{{ $empleado->name }} ({{ $empleado->position }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Monto (USD)</label>
                    <input type="number" step="0.01" name="amount" required class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-error/20 font-bold text-sm text-error" placeholder="0.00">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Fecha</label>
                    <input type="date" name="payment_date" required value="{{ now()->format('Y-m-d') }}" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-error/20 font-bold text-sm">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Concepto</label>
                <select name="concept" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none font-bold text-sm">
                    <option value="Sueldo/Quincena">Sueldo / Quincena</option>
                    <option value="Bono/Comisión">Bono / Comisión</option>
                    <option value="Adelanto">Adelanto</option>
                    <option value="Liquidación">Liquidación</option>
                    <option value="Otros">Otros</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Referencia / Comentario</label>
                <input type="text" name="reference" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-error/20 font-bold text-sm" placeholder="Opcional">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-error text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg hover:scale-[1.02] transition-all">
                    Confirmar Pago
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        initCharts();
    });

    function initCharts() {
        // ... (Charts logic remains the same) ...
        const ctxRevenue = document.getElementById('revenueMainChart');
        if (ctxRevenue) {
            @php
                $revenueData = [];
                for($i = 1; $i <= 12; $i++) {
                    $revenueData[] = (float) $cobros->filter(fn($c) => $c->payment_date->format('m') == str_pad($i, 2, '0', STR_PAD_LEFT))->sum('amount');
                }
            @endphp
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{ data: @json($revenueData), borderColor: '#6D4AFF', backgroundColor: 'rgba(109, 74, 255, 0.05)', fill: true, tension: 0.4, borderWidth: 3, pointRadius: 4 }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }

        const ctxDist = document.getElementById('distributionMainChart');
        if (ctxDist) {
            @php
                $distData = $metodos->map(fn($m) => ['name' => $m->name, 'color' => $m->color, 'total' => (float) $cobros->where('metodo_pago_id', $m->id)->sum('amount')])->filter(fn($d) => $d['total'] > 0)->values();
            @endphp
            const metodosDist = @json($distData);
            new Chart(ctxDist, {
                type: 'doughnut',
                data: { labels: metodosDist.map(m => m.name), datasets: [{ data: metodosDist.map(m => m.total), backgroundColor: metodosDist.map(m => m.color), borderWidth: 0, cutout: '85%' }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }

        const ctxPieMini = document.getElementById('methodsDistributionChart');
        if (ctxPieMini) {
            @php $mc = $metodos->map(fn($m) => ['name' => $m->name, 'color' => $m->color, 'count' => $cobros->where('metodo_pago_id', $m->id)->count()]); @endphp
            const mcData = @json($mc);
            const hasUsage = mcData.some(m => m.count > 0);
            new Chart(ctxPieMini, {
                type: 'doughnut',
                data: { labels: hasUsage ? mcData.filter(m => m.count > 0).map(m => m.name) : ['Sin datos'], datasets: [{ data: hasUsage ? mcData.filter(m => m.count > 0).map(m => m.count) : [1], backgroundColor: hasUsage ? mcData.filter(m => m.count > 0).map(m => m.color) : ['#f1f5f9'], borderWidth: 0, cutout: '80%' }] },
                options: { plugins: { legend: { display: false } }, responsive: true, maintainAspectRatio: false }
            });
        }
        
        const ctxLineMini = document.getElementById('weeklyTrendChart');
        if (ctxLineMini) {
            new Chart(ctxLineMini, { type: 'line', data: { labels: ['L', 'M', 'M', 'J', 'V', 'S', 'D'], datasets: [{ data: [5, 12, 8, 15, 10, 20, 12], borderColor: '#6D4AFF', backgroundColor: 'rgba(109, 74, 255, 0.05)', fill: true, tension: 0.4, pointRadius: 0 }] }, options: { plugins: { legend: { display: false } }, scales: { x: { display: false }, y: { display: false } }, responsive: true, maintainAspectRatio: false } });
        }
    }

    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('content-' + tab).classList.remove('hidden');
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active', 'text-primary', 'border-b-2', 'border-primary');
            b.classList.add('text-outline');
        });
        const btn = document.getElementById('tab-' + tab);
        btn.classList.add('active', 'text-primary', 'border-b-2', 'border-primary');
        btn.classList.remove('text-outline');
    }

    function openCobroModal() {
        const modal = document.getElementById('cobroModal');
        const content = document.getElementById('cobroModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => content.classList.remove('scale-95', 'opacity-0'), 10);
        if (typeof jQuery !== 'undefined') { $('.select2-modal-create').select2({ dropdownParent: $('#cobroModal') }); }
    }

    function closeCobroModal() {
        const modal = document.getElementById('cobroModal');
        const content = document.getElementById('cobroModalContent');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openEditCobroModal(cobro) {
        const modal = document.getElementById('editCobroModal');
        const content = document.getElementById('editCobroModalContent');
        const form = document.getElementById('editCobroForm');
        form.action = `/admin/finanzas/cobros/${cobro.id}`;
        document.getElementById('edit_patient_id').value = cobro.patient_id;
        document.getElementById('edit_amount').value = cobro.amount;
        document.getElementById('edit_reference').value = cobro.reference || '';
        if (cobro.payment_date) {
            const date = new Date(cobro.payment_date);
            document.getElementById('edit_payment_date').value = date.toISOString().slice(0, 16);
        }
        document.getElementById('edit_metodo_pago_id').value = cobro.metodo_pago_id;
        modal.classList.remove('hidden');
        setTimeout(() => content.classList.remove('scale-95', 'opacity-0'), 10);
        if (typeof jQuery !== 'undefined') { $('.select2-modal-edit').select2({ dropdownParent: $('#editCobroModal') }); }
    }

    function closeEditCobroModal() {
        const modal = document.getElementById('editCobroModal');
        const content = document.getElementById('editCobroModalContent');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openStaffPaymentModal() {
        const modal = document.getElementById('staffPaymentModal');
        const content = document.getElementById('staffPaymentModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => content.classList.remove('scale-95', 'opacity-0'), 10);
        if (typeof jQuery !== 'undefined') { $('.select2-modal-staff').select2({ dropdownParent: $('#staffPaymentModal') }); }
    }

    function closeStaffPaymentModal() {
        const modal = document.getElementById('staffPaymentModal');
        const content = document.getElementById('staffPaymentModalContent');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
