@extends('layouts.admin')

@section('content')
<div class="space-y-10">
    <!-- Hero Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Mis Consultorios</h1>
            <p class="text-on-surface-variant font-medium">Gestiona tus consultorios y configuraciones.</p>
        </div>
        <a href="{{ route('consultorios.create') }}" class="hero-gradient text-white px-8 py-4 rounded-2xl font-black text-sm shadow-lg hover:shadow-xl transition-all scale-95 hover:scale-100 flex items-center gap-3">
             Registrar Consultorio <i class="fa-solid fa-plus uppercase"></i>
        </a>
    </div>

    <!-- Lista de Consultorios -->
    <div class="space-y-6">
        @forelse($consultorios as $consultorio)
        <div class="bg-white p-8 rounded-[2.5rem] ambient-shadow border border-surface-container relative overflow-hidden group">
            <div class="flex flex-col lg:flex-row justify-between gap-8 h-full">
                <!-- Info del Consultorio -->
                <div class="flex-1 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                            <i class="fa-solid fa-building-user text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-primary tracking-tight">{{ $consultorio->name }}</h3>
                    </div>
                    
                    <div class="space-y-2 ml-1">
                        <div class="flex items-center gap-3 text-on-surface-variant">
                            <i class="fa-solid fa-location-dot text-sm text-outline w-4"></i>
                            <p class="text-sm font-medium">{{ $consultorio->address ?? 'Dirección no especificada' }}</p>
                        </div>
                        <div class="flex items-center gap-3 text-on-surface-variant">
                            <i class="fa-solid fa-phone text-sm text-outline w-4"></i>
                            <p class="text-sm font-medium">{{ $consultorio->phone ?? 'Sin teléfono registrado' }}</p>
                        </div>
                    </div>

                    <!-- Stats Mock -->
                    <div class="flex gap-8 pt-4 border-t border-surface-container-low">
                        <div>
                            <p class="text-[10px] font-black text-outline uppercase tracking-widest mb-1">Total citas</p>
                            <p class="text-lg font-black text-primary">0</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-outline uppercase tracking-widest mb-1">Este mes</p>
                            <p class="text-lg font-black text-primary">0</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-outline uppercase tracking-widest mb-1">Estatus</p>
                            <p class="text-lg font-black text-green-600">Activo</p>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex flex-col justify-between items-end gap-6 text-right lg:min-w-[280px]">
                    <div class="flex gap-3">
                        <a href="{{ route('consultorios.edit', $consultorio) }}" class="flex items-center gap-2 px-5 py-3 rounded-xl bg-surface-container-low text-xs font-black text-primary hover:bg-surface-container-high transition-all border border-surface-container uppercase tracking-widest">
                            <i class="fa-solid fa-pen-to-square"></i> Editar
                        </a>
                        <button class="flex items-center gap-2 px-5 py-3 rounded-xl bg-surface-container-low text-xs font-black text-primary hover:bg-surface-container-high transition-all border border-surface-container uppercase tracking-widest">
                            <i class="fa-solid fa-calendar-days"></i> Agenda
                        </button>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="text-[10px] font-black uppercase tracking-widest {{ $consultorio->accept_bookings ? 'text-green-600' : 'text-outline' }} flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $consultorio->accept_bookings ? 'bg-green-600' : 'bg-outline' }}"></span>
                            Reserva Online
                        </span>
                        
                        <form action="{{ route('consultorios.toggle-booking', $consultorio) }}" method="POST">
                            @csrf
                            <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $consultorio->accept_bookings ? 'bg-primary' : 'bg-surface-container-highest' }}">
                                <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $consultorio->accept_bookings ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white p-16 rounded-[2.5rem] text-center border-2 border-dashed border-surface-container">
            <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6 text-primary">
                <i class="fa-solid fa-hospital text-4xl"></i>
            </div>
            <h3 class="text-xl font-black text-primary mb-2">No tienes consultorios registrados</h3>
            <p class="text-on-surface-variant max-w-sm mx-auto mb-8 font-medium">Comienza registrando el lugar donde atiendes a tus pacientes para habilitar la reserva online.</p>
            <a href="{{ route('consultorios.create') }}" class="hero-gradient text-white px-10 py-5 rounded-2xl font-black text-sm shadow-xl hover:scale-105 transition-all inline-block uppercase tracking-widest">
                Registrar primer consultorio
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
