@extends('layouts.admin')

@section('content')
@php
    $appSettings = \App\Models\Setting::first();
    $enabled = $appSettings->enabled_modules ?? [];
@endphp

<div class="space-y-8 pb-20">
    <!-- Mensaje de Bienvenida -->
    <div class="fade-element">
        <h1 class="text-2xl md:text-3xl font-black text-primary tracking-tight">¡Bienvenido de vuelta, {{ Auth::user()->name }}!</h1>
        <p class="text-outline font-medium text-sm md:text-base opacity-70">Aquí tienes un resumen de tu actividad</p>
    </div>

    <!-- Dashboard Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 fade-element" style="animation-delay: 0.1s">
        <!-- Nuevos Pacientes -->
        <div class="bg-white p-6 rounded-[2rem] border border-surface-container shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-bold text-primary/60 uppercase tracking-widest">Últimos 7 días</span>
                        <i class="fa-solid fa-chevron-down text-[8px] text-primary/40"></i>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-user-doctor text-primary/80"></i>
                        <h4 class="text-sm font-bold text-outline uppercase tracking-tight">Nuevos pacientes</h4>
                    </div>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary tracking-tighter">11</span>
                <div class="flex items-center gap-1 text-success font-black text-[10px] bg-success/10 px-2 py-1 rounded-full">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                    <span>38% vs mes anterior</span>
                </div>
            </div>
        </div>

        <!-- Citas Agendadas -->
        <div class="bg-white p-6 rounded-[2rem] border border-surface-container shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-bold text-primary/60 uppercase tracking-widest">Últimos 7 días</span>
                        <i class="fa-solid fa-chevron-down text-[8px] text-primary/40"></i>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-calendar-check text-primary/80"></i>
                        <h4 class="text-sm font-bold text-outline uppercase tracking-tight">Citas agendadas</h4>
                    </div>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary tracking-tighter">7</span>
                <div class="flex items-center gap-1 text-success font-black text-[10px] bg-success/10 px-2 py-1 rounded-full">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                    <span>250% vs mes anterior</span>
                </div>
            </div>
        </div>

        <!-- Ingresos (USD) -->
        <div class="bg-white p-6 rounded-[2rem] border border-surface-container shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[10px] font-bold text-primary/60 uppercase tracking-widest">Últimos 7 días</span>
                        <i class="fa-solid fa-chevron-down text-[8px] text-primary/40"></i>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-hand-holding-dollar text-primary/80"></i>
                        <h4 class="text-sm font-bold text-outline uppercase tracking-tight">Ingresos (USD)</h4>
                    </div>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary tracking-tighter">$550,00</span>
                <div class="flex items-center gap-1 text-success font-black text-[10px] bg-success/10 px-2 py-1 rounded-full">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                    <span>161% vs mes anterior</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="fade-element" style="animation-delay: 0.2s">
        <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-4">Acciones Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <button class="bg-white p-4 rounded-2xl border border-surface-container flex items-center justify-center gap-3 hover:border-primary/30 hover:bg-primary/5 transition-all group shadow-sm">
                <i class="fa-solid fa-file-medical text-primary group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-bold text-outline group-hover:text-primary transition-colors">Crear Consulta</span>
                <i class="fa-solid fa-plus text-primary text-[10px]"></i>
            </button>
            <button class="bg-white p-4 rounded-2xl border border-surface-container flex items-center justify-center gap-3 hover:border-primary/30 hover:bg-primary/5 transition-all group shadow-sm">
                <i class="fa-solid fa-user-plus text-primary group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-bold text-outline group-hover:text-primary transition-colors">Crear paciente</span>
                <i class="fa-solid fa-plus text-primary text-[10px]"></i>
            </button>
            <button class="bg-white p-4 rounded-2xl border border-surface-container flex items-center justify-center gap-3 hover:border-primary/30 hover:bg-primary/5 transition-all group shadow-sm">
                <i class="fa-solid fa-calendar-plus text-primary group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-bold text-outline group-hover:text-primary transition-colors">Agendar cita</span>
                <i class="fa-solid fa-plus text-primary text-[10px]"></i>
            </button>
        </div>
    </div>

    <!-- Citas de Hoy -->
    <div class="bg-white rounded-[2.5rem] border border-surface-container shadow-sm overflow-hidden fade-element" style="animation-delay: 0.3s">
        <div class="p-8 border-b border-surface-container flex items-center gap-3">
            <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                <i class="fa-solid fa-clock"></i>
            </div>
            <h3 class="text-xl font-bold text-primary tracking-tight">Citas de Hoy</h3>
        </div>
        
        <div class="divide-y divide-surface-container">
            @forelse([] as $cita)
                <!-- Esto es un placeholder que se llenará cuando haya datos -->
            @empty
                <div class="p-6">
                    <!-- Fila de ejemplo 1 -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 hover:bg-surface-container-low transition-colors rounded-3xl group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary font-black text-sm">
                                NR
                            </div>
                            <div>
                                <h4 class="text-base font-black text-primary leading-tight">Nicolas Restrepo</h4>
                                <div class="flex items-center gap-2 text-outline text-xs mt-1">
                                    <i class="fa-solid fa-location-dot text-[10px]"></i>
                                    <span>Centro Médico Maracay</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col md:items-end gap-2">
                             <div class="flex items-center gap-4 text-outline/60 text-[11px] font-bold">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-calendar text-[10px]"></i>
                                    <span>21 ene 2026</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                    <span>03:00 - 03:30</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="bg-success/10 text-success text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tight">Completada</span>
                                <a href="#" class="text-primary text-[11px] font-black flex items-center gap-1 group-hover:translate-x-1 transition-transform uppercase tracking-tighter">
                                    Ver consulta <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Fila de ejemplo 2 -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 hover:bg-surface-container-low transition-colors rounded-3xl group mt-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary font-black text-sm">
                                NR
                            </div>
                            <div>
                                <h4 class="text-base font-black text-primary leading-tight">Nicolas Restrepo</h4>
                                <div class="flex items-center gap-2 text-outline text-xs mt-1">
                                    <i class="fa-solid fa-location-dot text-[10px]"></i>
                                    <span>Consultorio del norte</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col md:items-end gap-2">
                             <div class="flex items-center gap-4 text-outline/60 text-[11px] font-bold">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-calendar text-[10px]"></i>
                                    <span>21 ene 2026</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                    <span>03:30 - 04:00</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="bg-success/10 text-success text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tight">Completada</span>
                                <a href="#" class="text-primary text-[11px] font-black flex items-center gap-1 group-hover:translate-x-1 transition-transform uppercase tracking-tighter">
                                    Ver consulta <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .fade-element {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
