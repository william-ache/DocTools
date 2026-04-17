@extends('layouts.admin')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Directorio de Pacientes</h1>
            <p class="text-on-surface-variant font-medium">Gestiona los expedientes y la información de contacto de tus pacientes.</p>
        </div>
        <button onclick="openModal('addPacienteModal')" class="hero-gradient text-white px-8 py-4 rounded-2xl font-bold text-sm shadow-lg hover:shadow-xl transition-all scale-95 hover:scale-100 flex items-center gap-2">
            Añadir Paciente <span class="text-lg">+</span>
        </button>
    </div>

    <!-- Buscador Integrado (Controlado por DataTables) -->
    <div class="bg-white p-6 rounded-[2rem] ambient-shadow border border-surface-container">
        <div class="relative">
            <i class="fa-solid fa-search absolute left-5 top-1/2 -translate-y-1/2 text-outline"></i>
            <input type="text" id="customSearch" placeholder="Buscar por nombre, email, teléfono, cédula..." class="w-full pl-14 pr-6 py-5 bg-surface-container-low border-none rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-lg">
        </div>
    </div>

    <!-- Tabla de Pacientes con DataTables -->
    <div class="bg-white rounded-[2.5rem] ambient-shadow border border-surface-container overflow-hidden p-4">
        <table id="pacientesTable" class="w-full text-left display responsive nowrap" style="width:100%">
            <thead>
                <tr class="text-[11px] font-black text-primary uppercase tracking-[0.15em] border-b border-surface-container">
                    <th class="px-6 py-8">NOMBRE</th>
                    <th class="px-6 py-8">TELÉFONO</th>
                    <th class="px-6 py-8">CÉDULA</th>
                    <th class="px-6 py-8">CORREO</th>
                    <th class="px-6 py-8 text-right">ACCIÓN</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container-low">
                @foreach($pacientes as $paciente)
                <tr class="hover:bg-surface-container-low/50 transition-colors group">
                    <td class="px-6 py-6">
                        <span class="text-sm font-black text-primary">{{ $paciente->full_name }}</span>
                    </td>
                    <td class="px-6 py-6">
                        <span class="text-xs font-bold text-on-surface-variant">{{ $paciente->phone ?? 'S/N' }}</span>
                    </td>
                    <td class="px-6 py-6">
                        <span class="text-xs font-bold text-on-surface-variant">{{ $paciente->id_number ?? 'No definido' }}</span>
                    </td>
                    <td class="px-6 py-6">
                        <span class="text-xs font-bold text-on-surface-variant">{{ $paciente->email ?? 'No definido' }}</span>
                    </td>
                    <td class="px-6 py-6 text-right">
                        <a href="{{ route('pacientes.edit', $paciente) }}" class="inline-flex items-center gap-2 text-primary font-bold text-xs hover:underline">
                            <i class="fa-solid fa-eye text-sm"></i> Ver
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Añadir Paciente -->
<div id="addPacienteModal" class="fixed inset-0 bg-primary/20 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-8 border-b border-surface-container flex justify-between items-center bg-white">
            <h2 class="text-2xl font-black text-primary tracking-tighter">Añadir Paciente</h2>
            <button onclick="closeModal('addPacienteModal')" class="fa-solid fa-xmark text-outline hover:text-error transition-colors"></button>
        </div>
        
        <form action="{{ route('pacientes.store') }}" method="POST" class="p-8 space-y-6 overflow-y-auto max-h-[70vh]">
            @csrf
            
            <!-- Cédula -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-outline uppercase tracking-widest">Cédula</label>
                <div class="flex gap-2">
                    <input type="text" name="id_number" class="flex-1 px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold" placeholder="V-00000000">
                    <button type="button" class="px-6 py-4 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-container transition-all">Modificar</button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-outline uppercase tracking-widest">Nombre *</label>
                    <input type="text" name="name" required class="w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold" placeholder="Ejem: Maria">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-outline uppercase tracking-widest">Apellido *</label>
                    <input type="text" name="last_name" required class="w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold" placeholder="Ejem: Toledo">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-outline uppercase tracking-widest">Teléfono (opcional)</label>
                <div class="flex border border-transparent bg-surface-container-low rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-primary/20 transition-all">
                    <select class="bg-transparent border-none px-4 text-xs font-bold text-primary focus:ring-0">
                        <option>+58</option>
                        <option>+1</option>
                        <option>+34</option>
                    </select>
                    <div class="w-[1px] h-6 bg-outline/20 self-center"></div>
                    <input type="text" name="phone" class="flex-1 bg-transparent border-none px-5 py-4 font-bold" placeholder="+584140001000">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-outline uppercase tracking-widest">Correo electrónico (opcional)</label>
                <input type="email" name="email" class="w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold" placeholder="prueba@pacientes.com">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-outline uppercase tracking-widest">Sexo (opcional)</label>
                    <select name="gender" class="w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold appearance-none">
                        <option value="">Seleccionar...</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-outline uppercase tracking-widest">Fecha de nacimiento (opcional)</label>
                    <input type="date" name="birth_date" class="w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold text-on-surface-variant flex items-center justify-between">
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal('addPacienteModal')" class="flex-1 py-4 bg-surface-container-high text-on-surface-variant font-bold rounded-2xl hover:bg-surface-container-highest transition-all">Atrás</button>
                <button type="submit" class="flex-[2] py-4 hero-gradient text-white font-black rounded-2xl shadow-lg hover:scale-[1.02] transition-all">Añadir Paciente</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* DataTables Custom Styling to match Sanctuary */
    .dataTables_wrapper .dataTables_filter { display: none; }
    .dataTables_wrapper .dataTables_length { display: none; }
    .dataTables_wrapper .dataTables_info {
        padding: 20px 10px;
        font-size: 11px;
        font-weight: 700;
        color: #424752;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .dataTables_wrapper .dataTables_paginate {
        padding: 20px 10px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 12px !important;
        border: none !important;
        font-weight: 800 !important;
        padding: 8px 16px !important;
        background: #f2f4f6 !important;
        color: #00478d !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #00478d !important;
        color: white !important;
    }
    table.dataTable.no-footer { border-bottom: none !important; }
    
    #pacientesTable_wrapper { padding-bottom: 20px; }
    
    table.dataTable thead th {
        color: #00478d !important;
        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
        font-weight: 800 !important;
    }
    
    /* Fix for arrows */
    table.dataTable thead .sorting:after, 
    table.dataTable thead .sorting_asc:after, 
    table.dataTable thead .sorting_desc:after {
        opacity: 0.5 !important;
        color: #00478d !important;
    }
</style>

<script>
    $(document).ready(function() {
        const table = $('#pacientesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
            },
            responsive: true,
            dom: 'itrp',
            pageLength: 8,
        });

        $('#customSearch').on('keyup', function() {
            table.search(this.value).draw();
        });
    });

    function openModal(id) {
        $(`#${id}`).removeClass('hidden').addClass('flex');
        $('body').addClass('overflow-hidden');
    }

    function closeModal(id) {
        $(`#${id}`).addClass('hidden').removeClass('flex');
        $('body').removeClass('overflow-hidden');
    }
</script>
@endsection
