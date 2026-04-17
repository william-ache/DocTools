@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-10 pb-20">
    <!-- Header -->
    <div class="fade-element">
        <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Configuración del Sistema</h1>
        <p class="text-on-surface-variant font-medium text-lg">Personaliza tu espacio de trabajo y gestiona tu perfil profesional.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Sidebar de Configuración -->
        <div class="lg:col-span-1 space-y-4">
            <button onclick="showSection('profile')" class="config-tab w-full flex items-center gap-4 p-5 rounded-2xl bg-white border border-surface-container shadow-sm hover:shadow-md transition-all text-primary font-bold active-tab" id="tab-profile">
                <i class="fa-solid fa-user-doctor text-xl"></i>
                Perfil Profesional
            </button>
            <button onclick="showSection('app')" class="config-tab w-full flex items-center gap-4 p-5 rounded-2xl bg-white border border-surface-container shadow-sm hover:shadow-md transition-all text-on-surface-variant font-bold" id="tab-app">
                <i class="fa-solid fa-sliders text-xl"></i>
                Identidad Visual
            </button>
            <button onclick="showSection('modules')" class="config-tab w-full flex items-center gap-4 p-5 rounded-2xl bg-white border border-surface-container shadow-sm hover:shadow-md transition-all text-on-surface-variant font-bold" id="tab-modules">
                <i class="fa-solid fa-cubes text-xl"></i>
                Módulos del Sistema
            </button>
            <button onclick="showSection('favorites')" class="config-tab w-full flex items-center gap-4 p-5 rounded-2xl bg-white border border-surface-container shadow-sm hover:shadow-md transition-all text-on-surface-variant font-bold" id="tab-favorites">
                <i class="fa-solid fa-star text-xl"></i>
                Favoritos (Dashboard)
            </button>
        </div>

        <!-- Contenido -->
        <div class="lg:col-span-2">
            <!-- SECCIÓN: PERFIL -->
            <div id="section-profile" class="config-section fade-in">
                <form action="{{ route('settings.updateProfile') }}" method="POST" class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex items-center gap-6 mb-4">
                        <div class="w-20 h-20 rounded-3xl bg-primary/10 flex items-center justify-center text-primary relative group">
                            <i class="fa-solid fa-user-md text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-primary">Información Profesional</h3>
                            <p class="text-xs text-outline font-bold">Datos que aparecen en tus informes y dashboard.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-outline uppercase tracking-widest mb-2">Nombre Completo</label>
                            <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-6 py-4 bg-surface-container-low rounded-2xl border-none font-bold text-primary">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-outline uppercase tracking-widest mb-2">Especialidad</label>
                            <input type="text" name="specialty" value="{{ $user->specialty }}" class="w-full px-6 py-4 bg-surface-container-low rounded-2xl border-none font-bold text-primary" placeholder="Ej. Otorrinolaringólogo">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-outline uppercase tracking-widest mb-2">Correo de Acceso</label>
                            <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-6 py-4 bg-surface-container-low rounded-2xl border-none font-bold text-primary">
                        </div>
                        <div class="pt-4 border-t border-surface-container-low">
                            <button type="submit" class="w-full hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.01] transition-all">Actualizar mi Perfil</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- SECCIÓN: APP CONFIG -->
            <div id="section-app" class="config-section hidden fade-in">
                <form action="{{ route('settings.update') }}" method="POST" class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-10">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <h3 class="text-xl font-black text-primary mb-2">Personalización Visual</h3>
                        <p class="text-xs text-outline font-bold uppercase tracking-wider">Ajusta el tono cromático de tu consultorio digital.</p>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-black text-outline uppercase tracking-widest mb-3">Nombre del Proyecto (Inmutable)</label>
                            <div class="w-full px-6 py-5 border-2 border-surface-container bg-surface-container-low rounded-2xl font-black text-xl text-outline flex items-center justify-between">
                                {{ $settings->app_name }}
                                <i class="fa-solid fa-lock text-sm opacity-50"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-outline uppercase tracking-widest mb-4">Color de Identidad</label>
                            <div class="flex items-center gap-6 p-6 rounded-3xl bg-surface-container-low border border-surface-container">
                                <input type="color" name="primary_color" value="{{ $settings->primary_color }}" class="w-20 h-20 rounded-2xl border-none p-0 cursor-pointer overflow-hidden shadow-2xl">
                                <div>
                                    <p class="text-lg font-black text-primary">{{ strtoupper($settings->primary_color) }}</p>
                                    <p class="text-[10px] font-bold text-outline uppercase tracking-tighter">Color principal del sistema</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.01] transition-all">Guardar Identidad Visual</button>
                    </div>
                </form>
            </div>

            <!-- SECCIÓN: MÓDULOS -->
            <div id="section-modules" class="config-section hidden fade-in">
                <form action="{{ route('settings.update') }}" method="POST" class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-10">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="primary_color" value="{{ $settings->primary_color }}">

                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-black text-primary mb-1">Módulos del Sistema</h3>
                            <p class="text-xs text-outline font-bold uppercase tracking-wider">Activa o oculta funciones globales.</p>
                        </div>
                        <i class="fa-solid fa-layer-group text-3xl text-primary/20"></i>
                    </div>

                    @php $enabled = $settings->enabled_modules ?? []; @endphp
                    <div class="grid grid-cols-1 gap-4">
                        @foreach([
                            'consultorios' => ['Consultorios', 'Gestión de sedes físicas.', 'fa-hospital'],
                            'servicios' => ['Servicios Médicos', 'Catálogo de procedimientos.', 'fa-stethoscope'],
                            'finanzas' => ['Finanzas y Pagos', 'Cuentas y métodos de cobro.', 'fa-wallet'],
                            'pacientes' => ['Pacientes', 'Historias clínicas digitales.', 'fa-users']
                        ] as $key => $info)
                        <label class="flex items-center justify-between p-6 rounded-3xl border-2 border-surface-container has-[:checked]:border-primary/30 has-[:checked]:bg-primary/5 transition-all cursor-pointer group">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-[1.25rem] bg-surface-container-low flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-inner">
                                    <i class="fa-solid {{ $info[2] }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-primary">{{ $info[0] }}</p>
                                    <p class="text-[10px] text-outline font-bold uppercase tracking-widest">{{ $info[1] }}</p>
                                </div>
                            </div>
                            <input type="checkbox" name="modules[]" value="{{ $key }}" {{ in_array($key, $enabled) ? 'checked' : '' }} class="w-7 h-7 rounded-xl text-primary focus:ring-primary border-surface-container-highest transition-all">
                        </label>
                        @endforeach
                    </div>

                    <p class="bg-surface-container-low p-5 rounded-2xl text-[10px] text-on-surface-variant font-bold leading-relaxed">
                        <i class="fa-solid fa-circle-info text-primary mr-2"></i>
                        LOS MÓDULOS DESACTIVADOS SOLO SE OCULTARÁN DEL MENÚ. NO SE ELIMINARÁ NINGUNA INFORMACIÓN EXISTENTE EN LA BASE DE DATOS.
                    </p>

                    <div class="pt-6">
                        <button type="submit" class="w-full hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.01] transition-all">Sincronizar Ecosistema</button>
                    </div>
                </form>
            </div>

            <!-- SECCIÓN: FAVORITOS -->
            <div id="section-favorites" class="config-section hidden fade-in">
                <form action="{{ route('settings.update') }}" method="POST" class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-10">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="primary_color" value="{{ $settings->primary_color }}">
                    @php 
                        $enabled = $settings->enabled_modules ?? []; 
                        $favorites = $settings->favorite_modules ?? []; 
                    @endphp

                    @foreach($enabled as $mod)
                        <input type="hidden" name="modules[]" value="{{ $mod }}">
                    @endforeach

                    <div>
                        <h3 class="text-xl font-black text-primary mb-1">Módulos Favoritos</h3>
                        <p class="text-xs text-outline font-bold uppercase tracking-wider">Selecciona funciones para destacar en el Dashboard principal.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        @foreach([
                            'consultorios' => ['Mis Consultorios', 'Acceso rápido a sedes.', 'fa-hospital'],
                            'servicios' => ['Catálogo de Servicios', 'Gestión veloz de precios.', 'fa-stethoscope'],
                            'finanzas' => ['Billetera Financiera', 'Revisar cuentas de cobro.', 'fa-wallet'],
                            'pacientes' => ['Nuevo Paciente', 'Registro inmediato de expedientes.', 'fa-users']
                        ] as $key => $info)
                        @if(in_array($key, $enabled))
                        <label class="flex items-center justify-between p-6 rounded-3xl border-2 border-surface-container has-[:checked]:border-secondary/30 has-[:checked]:bg-secondary/5 transition-all cursor-pointer group">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-[1.25rem] bg-surface-container-low flex items-center justify-center text-secondary group-hover:scale-110 transition-transform shadow-inner">
                                    <i class="fa-solid {{ $info[2] }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-primary">{{ $info[0] }}</p>
                                    <p class="text-[10px] text-outline font-bold uppercase tracking-widest">{{ $info[1] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="hidden group-has-[:checked]:block text-[9px] font-black text-secondary uppercase tracking-tighter">Destacado</span>
                                <input type="checkbox" name="favorites[]" value="{{ $key }}" {{ in_array($key, $favorites) ? 'checked' : '' }} class="w-7 h-7 rounded-xl text-secondary focus:ring-secondary border-surface-container-highest transition-all">
                            </div>
                        </label>
                        @endif
                        @endforeach
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.01] transition-all">Fijar en el Dashboard Principal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .active-tab {
        border-color: #00478d !important;
        background-color: #f7f9fb !important;
        box-shadow: 0 10px 40px -10px rgba(0,71,141,0.15) !important;
    }
</style>

<script>
    function showSection(id) {
        $('.config-section').addClass('hidden');
        $(`#section-${id}`).removeClass('hidden').addClass('fade-in');
        
        $('.config-tab').removeClass('active-tab text-primary').addClass('text-on-surface-variant');
        $(`#tab-${id}`).addClass('active-tab text-primary').removeClass('text-on-surface-variant');
    }

    // Auto-select tab if in URL or default to profile
    $(document).ready(function() {
        const hash = window.location.hash.replace('#', '');
        if (hash) showSection(hash);
    });
</script>
@endsection
