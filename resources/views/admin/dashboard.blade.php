@extends('layouts.admin')

@section('content')
<div class="space-y-10">
    <!-- Mensaje de Bienvenida -->
    <div class="fade-element">
        <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">¡Bienvenido de vuelta, {{ Auth::user()->name }}!</h1>
        <p class="text-on-surface-variant font-medium text-lg">Aquí tienes un resumen de tu actividad reciente.</p>
    </div>

    <!-- Dashboard Stats Grid (Últimos 7 días) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white p-7 rounded-[2rem] ambient-shadow border border-surface-container transition-all hover:scale-[1.02]">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[10px] font-black text-outline uppercase tracking-widest">Últimos 7 días</span>
                <i class="fa-solid fa-chevron-down text-outline text-xs"></i>
            </div>
            <div class="flex items-center gap-3 mb-4">
                <i class="fa-solid fa-user-plus text-primary opacity-60"></i>
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Nuevos pacientes</span>
            </div>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary leading-none">0</span>
                <span class="text-[10px] font-bold text-error flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-down text-[10px]"></i> -100% vs. 7 días anteriores
                </span>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white p-7 rounded-[2rem] ambient-shadow border border-surface-container transition-all hover:scale-[1.02]">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[10px] font-black text-outline uppercase tracking-widest">Últimos 7 días</span>
                <i class="fa-solid fa-chevron-down text-outline text-xs"></i>
            </div>
            <div class="flex items-center gap-3 mb-4">
                <i class="fa-solid fa-calendar-alt text-primary opacity-60"></i>
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Citas agendadas</span>
            </div>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary leading-none">2</span>
                <span class="text-[10px] font-bold text-outline flex items-center gap-1">
                    <i class="fa-solid fa-minus text-[10px]"></i> 0% vs. 7 días anteriores
                </span>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white p-7 rounded-[2rem] ambient-shadow border border-surface-container transition-all hover:scale-[1.02]">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[10px] font-black text-outline uppercase tracking-widest">Últimos 7 días</span>
                <i class="fa-solid fa-chevron-down text-outline text-xs"></i>
            </div>
            <div class="flex items-center gap-3 mb-4">
                <i class="fa-solid fa-circle-check text-primary opacity-60"></i>
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Consultas concretadas</span>
            </div>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary leading-none">3</span>
                <span class="text-[10px] font-bold text-error flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-down text-[10px]"></i> -25% vs. 7 días anteriores
                </span>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div>
        <h3 class="text-[11px] font-black text-primary uppercase tracking-[0.2em] mb-6">Acciones Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <button class="flex items-center justify-between p-6 bg-white border border-surface-container rounded-2xl hover:bg-surface-container-low transition-all ambient-shadow group text-left">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                        <i class="fa-solid fa-file-signature text-lg"></i>
                    </div>
                    <span class="text-sm font-bold text-on-surface">Crear Consulta</span>
                </div>
                <i class="fa-solid fa-plus text-outline group-hover:text-primary transition-colors"></i>
            </button>
            <button class="flex items-center justify-between p-6 bg-white border border-surface-container rounded-2xl hover:bg-surface-container-low transition-all ambient-shadow group text-left" onclick="window.location.href='/calendario'">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                        <i class="fa-solid fa-calendar-day text-lg"></i>
                    </div>
                    <span class="text-sm font-bold text-on-surface">Agendar cita</span>
                </div>
                <i class="fa-solid fa-plus text-outline group-hover:text-primary transition-colors"></i>
            </button>
            <button class="flex items-center justify-between p-6 bg-white border border-surface-container rounded-2xl hover:bg-surface-container-low transition-all ambient-shadow group text-left" onclick="window.location.href='{{ route('pacientes.index') }}'">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                        <i class="fa-solid fa-user-plus text-lg"></i>
                    </div>
                    <span class="text-sm font-bold text-on-surface">Crear paciente</span>
                </div>
                <i class="fa-solid fa-plus text-outline group-hover:text-primary transition-colors"></i>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Citas de Hoy -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-surface-container ambient-shadow">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                        <i class="fa-solid fa-clock text-xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-primary tracking-tighter">Citas de Hoy</h3>
                </div>
                <span class="px-4 py-1.5 bg-primary/5 text-primary text-[10px] font-black rounded-full uppercase tracking-widest">{{ now()->format('d M, Y') }}</span>
            </div>
            <div class="text-center py-10">
                <div class="bg-surface-container-low w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-calendar-check text-outline opacity-40 text-2xl"></i>
                </div>
                <p class="text-sm text-on-surface-variant font-bold opacity-60 italic">No hay citas agendadas para hoy.</p>
            </div>
        </div>

        <!-- Pacientes Recientes -->
        <div class="bg-white rounded-[2.5rem] ambient-shadow border border-surface-container overflow-hidden">
            <div class="p-8 pb-4 flex justify-between items-center">
                <h3 class="text-xl font-black text-primary tracking-tighter">Pacientes Recientes</h3>
                <a href="{{ route('pacientes.index') }}" class="text-xs font-bold text-primary hover:underline">Ver todos</a>
            </div>

            <div class="px-8 pb-8">
                <div class="divide-y divide-surface-container-low">
                    <div class="py-4 flex items-center justify-between group cursor-pointer">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-secondary-container/40 rounded-xl flex items-center justify-center text-secondary font-black text-[10px]">EM</div>
                            <div>
                                <p class="text-sm font-black text-primary">Elena Martínez</p>
                                <p class="text-[10px] text-outline font-bold uppercase tracking-widest">ID #6932-10</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-outline text-xs opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0"></i>
                    </div>
                    <div class="py-4 flex items-center justify-between group cursor-pointer">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary font-black text-[10px]">JG</div>
                            <div>
                                <p class="text-sm font-black text-primary">Juan Guerrero</p>
                                <p class="text-[10px] text-outline font-bold uppercase tracking-widest">ID #4421-08</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-outline text-xs opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fade-element {
        opacity: 0;
        transform: translateY(10px);
        animation: fadeInUp 0.5s ease-out forwards;
    }
    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
