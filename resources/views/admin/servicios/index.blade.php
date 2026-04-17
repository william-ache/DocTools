@extends('layouts.admin')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Servicios Médicos</h1>
            <p class="text-on-surface-variant font-medium">Gestiona el catálogo de servicios y procedimientos que ofreces.</p>
        </div>
        <a href="{{ route('servicios.create') }}" class="hero-gradient text-white px-8 py-4 rounded-2xl font-bold text-sm shadow-lg hover:shadow-xl transition-all scale-95 hover:scale-100 flex items-center gap-2">
            Añadir servicio <span class="text-lg">+</span>
        </a>
    </div>

    <!-- Lista de Servicios -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($servicios as $servicio)
        <div class="bg-white p-8 rounded-[2.5rem] ambient-shadow border border-surface-container relative group transition-all hover:scale-[1.02]">
            <div class="flex flex-col h-full">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-colors" style="background-color: {{ $servicio->color }}20; color: {{ $servicio->color }};">
                        <i class="fa-solid {{ $servicio->icon ?? 'fa-stethoscope' }} text-2xl"></i>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('servicios.edit', $servicio) }}" class="p-2 rounded-lg bg-surface-container-low text-outline hover:text-primary transition-all">
                            <i class="fa-solid fa-pen text-sm"></i>
                        </a>
                    </div>
                </div>

                <div class="flex-1">
                    <h3 class="text-xl font-black text-primary tracking-tight mb-2">{{ $servicio->name }}</h3>
                    <div class="inline-flex items-center gap-2 mb-4">
                        <span class="text-2xl font-black text-secondary">
                            @if($servicio->price_from)<span class="text-xs font-bold text-outline align-middle mr-1 italic">Desde</span>@endif
                            ${{ number_format($servicio->price, 0) }}
                        </span>
                    </div>
                    <p class="text-sm text-on-surface-variant leading-relaxed line-clamp-3 mb-6">
                        {{ $servicio->description ?? 'Sin descripción disponible.' }}
                    </p>
                </div>

                <div class="pt-6 border-t border-surface-container-low flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-2" style="color: {{ $servicio->is_active ? $servicio->color : 'var(--outline)' }}">
                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $servicio->is_active ? $servicio->color : 'var(--outline)' }}"></span>
                            <span class="text-[9px] font-black uppercase tracking-widest">{{ $servicio->is_active ? 'Activo' : 'Pausado' }}</span>
                        </span>
                        <span class="w-1 h-1 rounded-full bg-outline/20"></span>
                        <div class="flex items-center gap-2 text-outline">
                            <i class="fa-regular fa-clock text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-[0.1em]">{{ $servicio->duration }}m</span>
                        </div>
                    </div>
                    <form action="{{ route('servicios.toggle-status', $servicio) }}" method="POST">
                        @csrf
                        <button type="submit" class="relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $servicio->is_active ? 'bg-green-500' : 'bg-surface-container-highest' }}">
                            <span aria-hidden="true" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $servicio->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-16 rounded-[2.5rem] text-center border-2 border-dashed border-surface-container">
            <h3 class="text-xl font-bold text-primary">No hay servicios registrados</h3>
            <p class="text-on-surface-variant mt-2 mb-8">Crea tu primer servicio para empezar a agendar pacientes.</p>
            <a href="{{ route('servicios.create') }}" class="hero-gradient text-white px-8 py-4 rounded-xl font-bold transition-all inline-block shadow-lg">Registrar Servicio</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
