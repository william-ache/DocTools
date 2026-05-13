@extends('layouts.admin')

@section('content')
@php
    $appSettings = \App\Models\Setting::first();
    $enabled = $appSettings->enabled_modules ?? [];
@endphp

<div class="space-y-10 pb-20">
    <!-- Header Hero Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 fade-element">
        <div>
            <h1 class="text-3xl md:text-5xl font-black text-primary tracking-tighter leading-none mb-2">
                ¡Hola, {{ Auth::user()->name }}!
            </h1>
            <p class="text-outline font-medium text-sm md:text-lg opacity-60">Resumen operativo para el día de hoy.</p>
        </div>
        <div class="flex items-center gap-3 bg-white p-2 pr-6 rounded-full shadow-sm border border-surface-container">
            <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
            <span class="text-xs font-black text-primary uppercase tracking-widest">{{ now()->translatedFormat('l, d F') }}</span>
        </div>
    </div>

    <!-- Stats Grid: Neumorphic Style -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 fade-element" style="animation-delay: 0.1s">
        <!-- Nuevos Pacientes -->
        <div class="relative overflow-hidden bg-white p-8 rounded-[3rem] border border-surface-container shadow-[0_15px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.05)] transition-all group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:bg-primary/10 transition-colors"></div>
            <div class="flex flex-col gap-6">
                <div class="w-14 h-14 bg-primary/5 rounded-2xl flex items-center justify-center text-primary text-xl">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-outline/50 uppercase tracking-[0.2em] mb-1">Nuevos Pacientes</h4>
                    <div class="flex items-end gap-3">
                        <span class="text-5xl font-black text-primary tracking-tighter">11</span>
                        <span class="text-[10px] font-bold text-success bg-success/10 px-2 py-0.5 rounded-full mb-2">+38%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Citas Agendadas -->
        <div class="relative overflow-hidden bg-white p-8 rounded-[3rem] border border-surface-container shadow-[0_15px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.05)] transition-all group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:bg-secondary/10 transition-colors"></div>
            <div class="flex flex-col gap-6">
                <div class="w-14 h-14 bg-secondary/5 rounded-2xl flex items-center justify-center text-secondary text-xl">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-outline/50 uppercase tracking-[0.2em] mb-1">Citas Agendadas</h4>
                    <div class="flex items-end gap-3">
                        <span class="text-5xl font-black text-primary tracking-tighter">07</span>
                        <span class="text-[10px] font-bold text-success bg-success/10 px-2 py-0.5 rounded-full mb-2">+250%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos USD -->
        <div class="relative overflow-hidden bg-white p-8 rounded-[3rem] border border-surface-container shadow-[0_15px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.05)] transition-all group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-tertiary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:bg-tertiary/10 transition-colors"></div>
            <div class="flex flex-col gap-6">
                <div class="w-14 h-14 bg-tertiary/5 rounded-2xl flex items-center justify-center text-tertiary text-xl">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-outline/50 uppercase tracking-[0.2em] mb-1">Ingresos Brutos</h4>
                    <div class="flex items-end gap-3">
                        <span class="text-4xl font-black text-primary tracking-tighter">$550<span class="text-xl opacity-30">,00</span></span>
                        <span class="text-[10px] font-bold text-success bg-success/10 px-2 py-0.5 rounded-full mb-2">+161%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="fade-element" style="animation-delay: 0.2s">
        <h3 class="text-[10px] font-black text-outline uppercase tracking-[0.4em] mb-6 px-1">Atajos Directos</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <button class="bg-primary p-6 rounded-3xl flex items-center justify-between group shadow-[0_10px_30px_rgba(var(--primary-rgb),0.2)] hover:shadow-[0_20px_40px_rgba(var(--primary-rgb),0.3)] hover:-translate-y-1 transition-all">
                <div class="flex items-center gap-4 text-white">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-file-medical text-lg"></i>
                    </div>
                    <span class="text-base font-black tracking-tight">Nueva Consulta</span>
                </div>
                <i class="fa-solid fa-chevron-right text-white/50 group-hover:translate-x-1 transition-transform"></i>
            </button>
            <button class="bg-white p-6 rounded-3xl border border-surface-container flex items-center justify-between group hover:border-primary/20 transition-all shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-surface/50 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary/5">
                        <i class="fa-solid fa-user-plus text-lg transition-transform group-hover:scale-110"></i>
                    </div>
                    <span class="text-base font-black text-primary tracking-tight">Registrar Paciente</span>
                </div>
                <i class="fa-solid fa-chevron-right text-outline/20 group-hover:translate-x-1 transition-transform"></i>
            </button>
            <button class="bg-white p-6 rounded-3xl border border-surface-container flex items-center justify-between group hover:border-primary/20 transition-all shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-surface/50 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary/5">
                        <i class="fa-solid fa-calendar-plus text-lg transition-transform group-hover:scale-110"></i>
                    </div>
                    <span class="text-base font-black text-primary tracking-tight">Programar Cita</span>
                </div>
                <i class="fa-solid fa-chevron-right text-outline/20 group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </div>

    <!-- Agenda de Hoy -->
    <div class="bg-white rounded-[3.5rem] border border-surface-container shadow-sm overflow-hidden fade-element" style="animation-delay: 0.3s">
        <div class="p-10 border-b border-surface-container flex items-center justify-between bg-surface-container-low/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center shadow-[0_10px_20px_rgba(var(--primary-rgb),0.2)]">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-primary tracking-tight">Agenda de Hoy</h3>
                    <p class="text-[10px] font-black text-outline/50 uppercase tracking-widest mt-1">Sincronizado en tiempo real</p>
                </div>
            </div>
            <a href="{{ route('appointments.index') }}" class="text-xs font-black text-primary uppercase tracking-widest hover:underline px-6 py-2 rounded-full border border-primary/10 bg-primary/5">Ver todo</a>
        </div>
        
        <div class="p-6">
            @forelse([] as $cita)
                <!-- Esto es un placeholder que se llenará cuando haya datos -->
            @empty
                <div class="space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 p-6 hover:bg-surface transition-all rounded-[2.5rem] border border-transparent hover:border-surface-container-high group">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary/5 to-primary/10 rounded-3xl flex items-center justify-center text-primary font-black text-xl shadow-inner uppercase">
                                NR
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-primary tracking-tight group-hover:text-secondary transition-colors">Nicolas Restrepo</h4>
                                <div class="flex items-center gap-4 mt-2">
                                    <div class="flex items-center gap-2 text-outline font-bold text-xs">
                                        <i class="fa-solid fa-location-dot text-secondary"></i>
                                        <span>Maternidad La Floresta</span>
                                    </div>
                                    <div class="w-1 h-1 bg-outline/20 rounded-full"></div>
                                    <div class="flex items-center gap-2 text-primary font-black text-xs uppercase tracking-tighter">
                                        <i class="fa-solid fa-clock opacity-50"></i>
                                        <span>03:00 - 03:30</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="bg-success text-white text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-md shadow-success/20">Completada</span>
                            <a href="#" class="w-12 h-12 rounded-2xl bg-white border border-surface-container flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 p-6 hover:bg-surface transition-all rounded-[2.5rem] border border-transparent hover:border-surface-container-high group">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-secondary/5 to-secondary/10 rounded-3xl flex items-center justify-center text-secondary font-black text-xl shadow-inner uppercase">
                                AC
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-primary tracking-tight group-hover:text-secondary transition-colors">Ana Castillo</h4>
                                <div class="flex items-center gap-4 mt-2">
                                    <div class="flex items-center gap-2 text-outline font-bold text-xs">
                                        <i class="fa-solid fa-location-dot text-secondary"></i>
                                        <span>C.M. Los Aviadores</span>
                                    </div>
                                    <div class="w-1 h-1 bg-outline/20 rounded-full"></div>
                                    <div class="flex items-center gap-2 text-primary font-black text-xs uppercase tracking-tighter">
                                        <i class="fa-solid fa-clock opacity-50"></i>
                                        <span>04:00 - 04:30</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="bg-blue-500 text-white text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-md shadow-blue-500/20">En Espera</span>
                            <a href="#" class="w-12 h-12 rounded-2xl bg-white border border-surface-container flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all shadow-sm">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
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
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
