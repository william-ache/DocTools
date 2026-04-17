@extends('layouts.admin')

@section('content')
@php
    $appSettings = \App\Models\Setting::first();
    $favorites = $appSettings->favorite_modules ?? [];
    $enabled = $appSettings->enabled_modules ?? [];
    
    $moduleMap = [
        'consultorios' => ['Mis Consultorios', 'Gestión de sedes', 'fa-hospital', route('consultorios.index')],
        'servicios' => ['Servicios Médicos', 'Catálogo de precios', 'fa-stethoscope', route('servicios.index')],
        'finanzas' => ['Finanzas y Pagos', 'Cuentas de cobro', 'fa-wallet', route('metodos.index')],
        'pacientes' => ['Directorio Pacientes', 'Historias clínicas', 'fa-users', route('pacientes.index')]
    ];
@endphp

<div class="space-y-10 pb-20">
    <!-- Mensaje de Bienvenida -->
    <div class="fade-element flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">¡Hola, {{ Auth::user()->name }}!</h1>
            <p class="text-on-surface-variant font-medium text-lg italic opacity-80">"Haciendo lo que amamos: Cuidando cada respiro."</p>
        </div>
        <div class="bg-white px-6 py-3 rounded-2xl border border-surface-container shadow-sm flex items-center gap-4">
            <i class="fa-solid fa-calendar-day text-primary"></i>
            <span class="text-sm font-black text-primary uppercase tracking-widest">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    @if(count($favorites) > 0)
    <!-- Dashboard Pins (Favoritos) -->
    <div class="fade-element">
        <h3 class="text-[10px] font-black text-outline uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
             <i class="fa-solid fa-star text-secondary"></i> Módulos Destacados
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($favorites as $fav)
                @if(isset($moduleMap[$fav]) && in_array($fav, $enabled))
                <a href="{{ $moduleMap[$fav][3] }}" class="group bg-white p-8 rounded-[2.5rem] ambient-shadow border border-surface-container transition-all hover:-translate-y-2 hover:border-secondary/30 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-secondary/5 rounded-full group-hover:scale-150 transition-transform"></div>
                    <div class="relative z-10 flex flex-col h-full">
                        <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary mb-6 group-hover:bg-secondary group-hover:text-white transition-all shadow-inner">
                            <i class="fa-solid {{ $moduleMap[$fav][2] }} text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-black text-primary tracking-tighter mb-1">{{ $moduleMap[$fav][0] }}</h4>
                        <p class="text-[10px] text-outline font-bold uppercase tracking-widest">{{ $moduleMap[$fav][1] }}</p>
                    </div>
                </a>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Dashboard Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 fade-element">
        <div class="bg-white p-7 rounded-[2.5rem] ambient-shadow border border-surface-container group hover:border-primary/20 transition-all">
            <div class="flex justify-between items-center mb-6">
                <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-outline uppercase tracking-widest">Semana Actual</span>
            </div>
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Crecimiento de Pacientes</p>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary tracking-tighter">0</span>
                <span class="text-[10px] font-bold text-outline opacity-60">Nuevos registros</span>
            </div>
        </div>

        <div class="bg-white p-7 rounded-[2.5rem] ambient-shadow border border-surface-container group hover:border-primary/20 transition-all">
            <div class="flex justify-between items-center mb-6">
                <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                    <i class="fa-solid fa-calendar-check text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-outline uppercase tracking-widest">Día de hoy</span>
            </div>
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Citas Confirmadas</p>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary tracking-tighter">0</span>
                <span class="text-[10px] font-bold text-outline opacity-60">Horarios ocupados</span>
            </div>
        </div>

        <div class="bg-white p-7 rounded-[2.5rem] ambient-shadow border border-surface-container group hover:border-primary/20 transition-all">
            <div class="flex justify-between items-center mb-6">
                <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                    <i class="fa-solid fa-laptop-medical text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-outline uppercase tracking-widest">Global</span>
            </div>
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Expedientes Totales</p>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary tracking-tighter">
                    {{ \App\Models\Patient::count() }}
                </span>
                <a href="{{ route('pacientes.index') }}" class="text-[10px] font-black text-primary hover:underline uppercase tracking-widest tracking-tighter">Administrar <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 fade-element">
        <!-- Próximas Citas (Placeholder) -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-surface-container ambient-shadow h-full">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-primary/10 rounded-[1.5rem] flex items-center justify-center text-primary">
                        <i class="fa-solid fa-hourglass-half text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-primary tracking-tighter">Próximas Citas</h3>
                        <p class="text-[10px] text-outline font-black uppercase tracking-widest">Hoy & Mañana</p>
                    </div>
                </div>
            </div>
            <div class="text-center py-12 bg-surface-container-low rounded-[2rem] border border-dashed border-surface-container-highest">
                <p class="text-sm text-outline font-bold italic opacity-60">Sincroniza el calendario para ver tus citas aquí.</p>
            </div>
        </div>

        <!-- Módulos Disponibles -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-surface-container ambient-shadow h-full">
             <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-secondary/10 rounded-[1.5rem] flex items-center justify-center text-secondary">
                        <i class="fa-solid fa-compass text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-primary tracking-tighter">Navegación</h3>
                        <p class="text-[10px] text-outline font-black uppercase tracking-widest">Ecosistema Activo</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($moduleMap as $key => $info)
                    @if(in_array($key, $enabled))
                    <a href="{{ $info[3] }}" class="p-5 bg-surface-container-low rounded-2xl flex items-center gap-4 hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-surface-container">
                        <i class="fa-solid {{ $info[2] }} text-primary text-lg"></i>
                        <span class="text-xs font-black text-primary uppercase tracking-tighter">{{ $info[0] }}</span>
                    </a>
                    @endif
                @endforeach
            </div>
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
