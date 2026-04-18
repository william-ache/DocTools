@extends('layouts.admin')

@section('title', 'Gestión de Personal')

@section('content')
<div class="space-y-10 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Directorio de Personal</h1>
            <p class="text-on-surface-variant font-medium uppercase tracking-[0.2em] text-[10px]">Administración de Recursos Humanos</p>
        </div>
        <button onclick="openEmployeeModal()" class="hero-gradient text-white px-8 py-4 rounded-2xl font-bold text-sm shadow-lg hover:shadow-xl transition-all scale-95 hover:scale-100 flex items-center gap-2">
            Añadir Empleado <span class="text-lg">+</span>
        </button>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2.5rem] border border-surface-container shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-outline uppercase tracking-wider">Total Empleados</p>
                <p class="text-2xl font-black text-on-surface">{{ $employees->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2.5rem] border border-surface-container shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-outline uppercase tracking-wider">Nómina Mensual</p>
                <p class="text-2xl font-black text-on-surface">${{ number_format($employees->where('is_active', true)->sum('salary'), 2) }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2.5rem] border border-surface-container shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-outline uppercase tracking-wider">Promedio Salarial</p>
                <p class="text-2xl font-black text-on-surface">${{ number_format($employees->avg('salary') ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Buscador Integrado -->
    <div class="bg-white p-6 rounded-[2rem] border border-surface-container shadow-sm">
        <div class="relative">
            <i class="fa-solid fa-search absolute left-5 top-1/2 -translate-y-1/2 text-outline"></i>
            <input type="text" id="employeeSearch" placeholder="Buscar por nombre, cargo, email, teléfono..." class="w-full pl-14 pr-6 py-5 bg-surface-container-low border-none rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-lg">
        </div>
    </div>

    <!-- Tabla de Empleados -->
    <div class="bg-white rounded-[2.5rem] border border-surface-container overflow-hidden p-4 shadow-sm">
        <table id="employeesTable" class="w-full text-left display responsive nowrap" style="width:100%">
            <thead>
                <tr class="text-[11px] font-black text-primary uppercase tracking-[0.15em] border-b border-surface-container">
                    <th class="px-6 py-8">NOMBRE / CARGO</th>
                    <th class="px-6 py-8">CONTACTO</th>
                    <th class="px-6 py-8">SALARIO</th>
                    <th class="px-6 py-8 text-center">ESTADO</th>
                    <th class="px-6 py-8 text-right">ACCIÓN</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container-low">
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nuevo Empleado -->
<div id="employeeModal" class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div id="employeeModalContent" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-8 border-b border-surface-container flex items-center justify-between">
            <h3 class="text-2xl font-black text-primary tracking-tight">Añadir Empleado</h3>
            <button onclick="closeEmployeeModal()" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-outline">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('employees.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Nombre Completo</label>
                <input type="text" name="name" required class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm" placeholder="Ejem: Juan Pérez">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Cargo / Posición</label>
                <input type="text" name="position" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm" placeholder="Ejem: Recepcionista, Enfermero...">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Correo</label>
                    <input type="email" name="email" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm" placeholder="correo@ejemplo.com">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Teléfono</label>
                    <input type="text" name="phone" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm" placeholder="0414-0000000">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Salario Mensual (USD)</label>
                <input type="number" step="0.01" name="salary" required class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm" placeholder="0.00">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full hero-gradient text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg hover:scale-[1.02] transition-all">
                    Guardar Empleado
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Empleado -->
<div id="editEmployeeModal" class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div id="editEmployeeModalContent" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-8 border-b border-surface-container flex items-center justify-between">
            <h3 class="text-2xl font-black text-primary tracking-tight">Editar Empleado</h3>
            <button onclick="closeEditEmployeeModal()" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-outline">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editEmployeeForm" method="POST" class="p-8 space-y-4">
            @csrf
            @method('PUT')
            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Nombre Completo</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Cargo</label>
                <input type="text" name="position" id="edit_position" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Correo</label>
                    <input type="email" name="email" id="edit_email" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Teléfono</label>
                    <input type="text" name="phone" id="edit_phone" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Salario (USD)</label>
                    <input type="number" step="0.01" name="salary" id="edit_salary" required class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Estado</label>
                    <select name="is_active" id="edit_is_active" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none font-bold text-sm">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg hover:scale-[1.02] transition-all">
                    Actualizar Datos
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .dataTables_wrapper .dataTables_filter { display: none; }
    .dataTables_wrapper .dataTables_length { display: none; }
    .dataTables_wrapper .dataTables_info { padding: 20px 10px; font-size: 11px; font-weight: 700; color: #424752; text-transform: uppercase; letter-spacing: 0.1em; }
    .dataTables_wrapper .dataTables_paginate { padding: 20px 10px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius: 12px !important; border: none !important; font-weight: 800 !important; padding: 8px 16px !important; background: #f2f4f6 !important; color: #00478d !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #00478d !important; color: white !important; }
    table.dataTable.no-footer { border-bottom: none !important; }
    table.dataTable thead th { color: #00478d !important; border-bottom: 1px solid rgba(0,0,0,0.05) !important; font-weight: 800 !important; }
    .animate-fade-in { animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    $(document).ready(function() {
        const table = $('#employeesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employees.index') }}",
            columns: [
                { data: 'name_position', name: 'name' },
                { data: 'contact', name: 'email' },
                { data: 'salary_currency', name: 'salary' },
                { data: 'status', name: 'is_active', className: 'text-center' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right' }
            ],
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            responsive: true,
            dom: 'itrp',
            pageLength: 8
        });

        $('#employeeSearch').on('keyup', function() { table.search(this.value).draw(); });
    });

    function openEmployeeModal() {
        const modal = document.getElementById('employeeModal');
        const content = document.getElementById('employeeModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => { content.classList.remove('scale-95', 'opacity-0'); }, 10);
    }

    function closeEmployeeModal() {
        const modal = document.getElementById('employeeModal');
        const content = document.getElementById('employeeModalContent');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
    }

    function openEditEmployeeModal(employee) {
        const modal = document.getElementById('editEmployeeModal');
        const content = document.getElementById('editEmployeeModalContent');
        const form = document.getElementById('editEmployeeForm');
        
        form.action = `/admin/employees/${employee.id}`;
        document.getElementById('edit_name').value = employee.name;
        document.getElementById('edit_position').value = employee.position || '';
        document.getElementById('edit_email').value = employee.email || '';
        document.getElementById('edit_phone').value = employee.phone || '';
        document.getElementById('edit_salary').value = employee.salary;
        document.getElementById('edit_is_active').value = employee.is_active ? "1" : "0";

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => { content.classList.remove('scale-95', 'opacity-0'); }, 10);
    }

    function closeEditEmployeeModal() {
        const modal = document.getElementById('editEmployeeModal');
        const content = document.getElementById('editEmployeeModalContent');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
    }
</script>
@endsection
