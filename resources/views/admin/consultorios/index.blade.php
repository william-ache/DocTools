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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4">
        @forelse($consultorios as $consultorio)
        <div class="bg-white p-4 rounded-[1.5rem] ambient-shadow border border-surface-container relative group hover:border-primary/20 transition-all">
            <div class="flex flex-col space-y-3">
                <!-- Header: Icono + Título + Editar -->
                <div class="flex justify-between items-start gap-2">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-primary/5 rounded-xl flex-shrink-0 flex items-center justify-center text-primary border border-primary/5">
                            <i class="fa-solid fa-building-user text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base font-black text-primary tracking-tight truncate leading-tight">{{ $consultorio->name }}</h3>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full {{ $consultorio->accept_bookings ? 'bg-green-600' : 'bg-outline' }}"></span>
                                <span class="text-[8px] font-black uppercase tracking-wider {{ $consultorio->accept_bookings ? 'text-green-600' : 'text-outline' }}">
                                    {{ $consultorio->accept_bookings ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('consultorios.edit', $consultorio) }}" class="p-2 rounded-lg bg-surface-container-low text-primary hover:bg-primary hover:text-white transition-all border border-surface-container">
                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                    </a>
                </div>

                <!-- Info: Dirección y Telf (Compacta) -->
                <div class="space-y-1.5 px-0.5">
                    <div class="flex items-center gap-2 text-on-surface-variant">
                        <i class="fa-solid fa-location-dot text-[9px] text-outline w-3"></i>
                        <p class="text-[10px] font-bold truncate">{{ $consultorio->address ?? 'Sin dirección' }}</p>
                    </div>
                    <div class="flex items-center gap-2 text-on-surface-variant">
                        <i class="fa-solid fa-phone text-[9px] text-outline w-3"></i>
                        <p class="text-[10px] font-bold">{{ $consultorio->phone ?? 'Sin teléfono' }}</p>
                    </div>
                </div>

                <!-- Stats: Horizontal Row -->
                <div class="flex items-center justify-between py-2 border-y border-surface-container-low">
                    <div class="flex gap-4">
                        <div class="flex flex-col">
                            <span class="text-[7px] font-black text-outline uppercase">Citas</span>
                            <span class="text-sm font-black text-primary leading-none">0</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[7px] font-black text-outline uppercase">Mes</span>
                            <span class="text-sm font-black text-primary leading-none">0</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[7px] font-black uppercase tracking-widest text-outline">Reserva</span>
                        <form action="{{ route('consultorios.toggle-booking', $consultorio) }}" method="POST">
                            @csrf
                            <button type="submit" class="relative inline-flex h-4 w-8 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $consultorio->accept_bookings ? 'bg-primary' : 'bg-surface-container-highest' }}">
                                <span aria-hidden="true" class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $consultorio->accept_bookings ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Botón Principal -->
                <button class="w-full py-2 rounded-lg bg-primary text-[9px] font-black text-white hover:bg-primary-container transition-all shadow-sm uppercase tracking-widest flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-days text-[10px]"></i> Agenda
                </button>
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
