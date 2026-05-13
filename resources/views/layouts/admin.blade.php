<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Dr. Javier González Pugh</title>
    
    @php
        $appSettings = \App\Models\Setting::first() ?? (object)[
            'app_name' => 'Consultia', 
            'primary_color' => '#6D4AFF',
            'enabled_modules' => ['consultorios', 'servicios', 'finanzas', 'pacientes']
        ];
        $enabledMods = $appSettings->enabled_modules ?? [];

        // Calcular iniciales del usuario
        $userName = Auth::check() ? Auth::user()->name : '';
        $words = explode(' ', $userName);
        $initials = '';
        foreach ($words as $w) {
            $initials .= !empty($w) ? strtoupper($w[0]) : '';
        }
        $initials = substr($initials, 0, 2);
    @endphp
    
    <!-- Tipografía Local (Manrope) -->
    <link rel="stylesheet" href="{{ asset('assets/libs/manrope/manrope.css') }}">
    
    <!-- Font Awesome 6 (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
    
    <!-- Cropper.js (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/libs/cropperjs/cropper.min.css') }}">
    <script src="{{ asset('assets/libs/cropperjs/cropper.min.js') }}"></script>
    
    @viteReactRefresh
    @vite(['resources/js/app.jsx'])

    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">
    
    <!-- PWA Support -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="{{ $appSettings->primary_color }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/style.css') }}">
    <script src="{{ asset('assets/libs/sweetalert2/script.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "{{ $appSettings->primary_color }}",
                        "primary-container": "{{ $appSettings->primary_color }}ee",
                        "secondary": "#006687",
                        "secondary-container": "#80d3fd",
                        "surface": "#f7f9fb",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#424752",
                        "surface-container-low": "#f2f4f6",
                        "surface-container-high": "#e6e8ea",
                        "surface-container-highest": "#e0e3e5",
                        "surface-container-lowest": "#ffffff",
                        "outline-variant": "rgba(194, 198, 212, 0.15)",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                    },
                    borderRadius: {
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "4xl": "2rem",
                        "5xl": "3.5rem",
                    },
                    fontFamily: {
                        manrope: ["Manrope", "sans-serif"]
                    }
                }
            }
        }
    </script>

    
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- Google Fonts: Manrope (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/libs/manrope/manrope.css') }}">
    
    <!-- DataTables CSS (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/responsive.dataTables.min.css') }}">
    <script src="{{ asset('assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.responsive.min.js') }}"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>

    <script>
        // Configuración Global para Librerías en Español
        $(document).ready(function() {
            if ($.fn.select2) {
                $.fn.select2.defaults.set('language', 'es');
                
                // Auto-inicializar todos los selectores básicos
                $('.select2-basic').select2({
                    width: '100%',
                    minimumResultsForSearch: 10
                });
            }
        });
    </script>

    <style>
        /* Select2 Custom Styling to Match Theme */
        .select2-container--default .select2-selection--single {
            background-color: #f2f4f6 !important;
            border: none !important;
            border-radius: 1rem !important;
            height: 52px !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #424752 !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            padding-left: 1.25rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 13px !important;
            right: 15px !important;
        }
        .select2-dropdown {
            border: 1px solid #e6e8ea !important;
            border-radius: 1rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
        }
        .select2-search__field {
            border: 1px solid #e6e8ea !important;
            border-radius: 0.75rem !important;
            padding: 8px 12px !important;
        }
        .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary) !important;
        }
        .select2-container--default .select2-results__option--selectable {
            padding: 10px 20px !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
        }

    <style>
        :root {
            --primary: {{ $appSettings->primary_color }};
            --primary-container: {{ $appSettings->primary_color }}ee;
            --on-primary: #ffffff;
            --primary-rgb: {{ implode(',', sscanf($appSettings->primary_color, "#%02x%02x%02x")) }};
        }

        /* Custom Scrollbar Dynamic */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { 
            background: rgba(var(--primary-rgb), 0.6); 
            border-radius: 20px; 
            transition: all 0.3s ease;
            border: 2px solid transparent;
            background-clip: content-box;
        }
        ::-webkit-scrollbar-thumb:hover { 
            background: var(--primary); 
        }
        
        /* Firefox Support */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(var(--primary-rgb), 0.6) transparent;
        }

        /* Dark Mode Definitions */
        .dark {
            --surface: #111418;
            --on-surface: #e2e2e6;
            --surface-container-lowest: #1a1c1e;
            --surface-container-low: #1d2024;
            --surface-container: #212429;
            --surface-container-high: #2b2f33;
            --surface-container-highest: #33383d;
            --outline: #8e9199;
            --on-surface-variant: #c2c7d0;
        }
        .dark body { background-color: #111418; color: #e2e2e6; }
        .dark aside, .dark header, .dark .bg-white { background-color: #1a1c1e !important; border-color: #33383d !important; }
        .dark .bg-surface { background-color: #111418 !important; }
        .dark .text-primary { color: var(--primary) !important; filter: brightness(1.2); }
        .dark .text-outline, .dark .text-on-surface-variant { color: #c2c7d0 !important; }
        .dark .bg-surface-container-low { background-color: #1d2024 !important; }
        .dark .ambient-shadow { box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .dark input, .dark select, .dark textarea { background-color: #1d2024 !important; color: white !important; }

        .sidebar-active { 
            background-color: rgba(var(--primary-rgb), 0.08);
            color: var(--primary) !important; 
            font-weight: 800;
        }
        .dark .sidebar-active {
            background-color: rgba(var(--primary-rgb), 0.2) !important;
            color: white !important;
        }

        .swal2-popup {
            border-radius: 2rem !important;
            padding: 1.5rem !important;
            font-family: 'Manrope', sans-serif !important;
            border: 1px solid rgba(var(--primary-rgb), 0.1) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
        }
        .swal2-title { font-weight: 900 !important; tracking: -0.05em !important; font-size: 1.1rem !important; color: var(--primary) !important; }
        .swal2-html-container { font-weight: 500 !important; color: #424752 !important; font-size: 0.9rem !important; }
        .swal2-confirm { background-color: var(--primary) !important; border-radius: 1.25rem !important; font-weight: 800 !important; padding: 12px 30px !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; }
        .swal2-cancel { border-radius: 1.25rem !important; font-weight: 800 !important; padding: 12px 30px !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; }
        
        .dark .swal2-popup { background: #1a1c1e !important; color: #fff !important; }
        .dark .swal2-title { color: var(--primary-color) !important; filter: brightness(1.2); }
        .dark .swal2-html-container { color: #c2c7d0 !important; }

        /* DataTables Custom Color Fixes */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary) !important;
            color: white !important;
            border: none !important;
            border-radius: 10px !important;
            font-weight: 800 !important;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary) !important;
            ring: 2px solid rgba(var(--primary-rgb), 0.2) !important;
        }
        
        /* General Buttons that might not be captured by Tailwind primary class if compiled */
        .btn-primary, button[type="submit"]:not(.swal2-styled):not(.no-style) {
            background-color: var(--primary) !important;
            color: white !important;
        }

        /* Cropper Modal Custom */
        #cropper-modal { display: none; }
        #cropper-modal.show { display: flex; }
        .cropper-view-box, .cropper-face { border-radius: 50%; }
        
        /* FAB Animations */
        .fab-container { position: fixed; bottom: 30px; right: 30px; z-index: 50; display: flex; flex-direction: column; align-items: flex-end; gap: 15px; }
        .fab-main-btn { background-color: var(--primary) !important; }
        .fab-options { display: none; flex-direction: column; align-items: flex-end; gap: 10px; opacity: 0; transform: translateY(20px); transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .fab-options.show { display: flex; opacity: 1; transform: translateY(0); }
        .fab-main-btn.active { transform: rotate(45deg); }

        /* Ajustes de posición para FAB y Mic */
        .fab-position { bottom: 100px; right: 30px; }
        .mic-position { bottom: 30px; right: 34px; }

        /* Sidebar Mini Styles (Solo para Desktop) */
        @media (min-width: 1024px) {
            .sidebar-mini { width: 5rem !important; padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
            .sidebar-mini .sidebar-text, .sidebar-mini .sidebar-header-text { display: none; }
            .sidebar-mini .sidebar-logo { justify-content: center; padding: 0; }
            .sidebar-mini nav a { justify-content: center; padding-left: 0; padding-right: 0; }
            .sidebar-mini .sidebar-footer { padding-left: 0; padding-right: 0; }
            .sidebar-mini .sidebar-footer a, .sidebar-mini .sidebar-footer button { justify-content: center; }
            .sidebar-mini .section-label { display: none; }
        }

        /* Mobile Overlay */
        #sidebar-overlay { display: none; }
        .sidebar-open { overflow: hidden; }
        .sidebar-open #sidebar-overlay { display: block; }
        .sidebar-open #main-sidebar { 
            transform: translateX(0) !important; 
            width: 80% !important; 
            max-width: 300px !important; 
        }
    </style>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-surface text-on-surface font-manrope antialiased overflow-hidden h-screen transition-colors duration-300">
    <!-- Overlay for mobile sidebar -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 lg:hidden backdrop-blur-sm transition-opacity"></div>

    <div class="flex h-full relative">
        <!-- Sidebar -->
        <aside id="main-sidebar" class="fixed inset-y-0 left-0 lg:relative lg:translate-x-0 -translate-x-full {{ (trim($__env->yieldContent('sidebarWidth'))) ?: 'w-60' }} h-full bg-white border-r border-surface-container flex flex-col p-4 z-50 transition-all duration-300 ease-in-out">
            @section('sidebar')
            <div class="sidebar-logo text-2xl font-black tracking-tighter text-primary flex items-center gap-2 mb-8 px-2 leading-none">
                <i class="fa-solid fa-square-person-confined text-3xl"></i>
                <span class="sidebar-header-text truncate">{{ $appSettings->app_name }}</span>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto overflow-x-hidden pr-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}" title="Inicio">
                    <i class="fa-solid fa-house text-lg w-6 shrink-0"></i>
                    <span class="sidebar-text text-xs font-semibold">Inicio</span>
                </a>

                <a href="{{ route('admin.calendario') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.calendario') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}" title="Calendario">
                    <i class="fa-solid fa-calendar text-lg w-6 shrink-0"></i>
                    <span class="sidebar-text text-xs font-semibold">Calendario</span>
                </a>

                @if(in_array('pacientes', $enabledMods))
                <a href="{{ route('pacientes.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('pacientes.*') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}" title="Pacientes">
                    <i class="fa-solid fa-user-group text-lg w-6 shrink-0"></i>
                    <span class="sidebar-text text-xs font-semibold">Pacientes</span>
                </a>
                @endif

                @if(in_array('finanzas', $enabledMods))
                <a href="{{ route('finanzas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('finanzas.*') || request()->routeIs('metodos.*') || request()->routeIs('cobros.*') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}" title="Finanzas">
                    <i class="fa-solid fa-coins text-lg w-6 shrink-0"></i>
                    <span class="sidebar-text text-xs font-semibold">Finanzas</span>
                </a>

                <a href="{{ route('employees.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('employees.*') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}" title="Personal">
                    <i class="fa-solid fa-users-gear text-lg w-6 shrink-0"></i>
                    <span class="sidebar-text text-xs font-semibold">Personal</span>
                </a>
                @endif

                <div class="section-label pt-4 pb-1 px-3">
                    <p class="text-[9px] font-black text-outline/60 uppercase tracking-widest">Gestión</p>
                </div>

                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-on-surface-variant hover:bg-surface-container-low transition-all" title="Recordatorios">
                    <i class="fa-solid fa-bell text-lg w-6 shrink-0"></i>
                    <span class="sidebar-text text-xs font-semibold">Recordatorios</span>
                </a>

                <div class="section-label pt-4 pb-1 px-3">
                    <p class="text-[9px] font-black text-outline/60 uppercase tracking-widest">Configuración</p>
                </div>

                @if(in_array('consultorios', $enabledMods))
                <a href="{{ route('consultorios.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('consultorios.*') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}" title="Consultorios">
                    <i class="fa-solid fa-building text-lg w-6 shrink-0"></i>
                    <span class="sidebar-text text-xs font-semibold">Consultorios</span>
                </a>
                @endif

                <a href="{{ route('templates.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('templates.*') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}" title="Plantillas">
                    <i class="fa-solid fa-puzzle-piece text-lg w-6 shrink-0"></i>
                    <span class="sidebar-text text-xs font-semibold">Plantillas</span>
                </a>
            </nav>

            <div class="sidebar-footer pt-4 border-t border-surface-container space-y-3">
                <!-- Trial Status Card Oculto temporalmente
                <div class="sidebar-text p-3 rounded-2xl bg-primary/5 border border-primary/10">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-wand-magic-sparkles text-primary text-[10px]"></i>
                            <span class="text-[9px] font-black text-primary uppercase tracking-tight">Prueba gratis</span>
                        </div>
                        <span class="text-[9px] font-bold text-outline">3 días restantes</span>
                    </div>
                    <div class="w-full h-1 bg-surface-container-highest rounded-full overflow-hidden mb-2">
                        <div class="w-1/3 h-full bg-primary rounded-full"></div>
                    </div>
                    <a href="#" class="flex items-center justify-center gap-2 text-[9px] font-black text-primary hover:underline">
                        Ver detalles
                        <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                    </a>
                </div>
                -->

                <!-- User Profile Footer -->
                <div class="flex items-center gap-3 px-2 py-2 rounded-xl bg-surface-container-low/50 border border-surface-container/50">
                    <div class="group relative shrink-0">
                        <!-- Intense Status Ring Sidebar -->
                        <div class="absolute -inset-0.5 rounded-lg bg-green-500/20 animate-pulse ring-1 ring-green-600/40 pointer-events-none"></div>
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-xs font-black shrink-0 overflow-hidden relative">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo }}" class="w-full h-full object-cover">
                            @else
                                {{ $initials }}
                            @endif
                            <!-- Status Dot Sidebar -->
                            <div class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 border border-white rounded-full shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-black text-primary truncate leading-tight">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('settings.index') }}" class="p-1 text-outline hover:text-primary transition-colors" title="Configuración">
                            <i class="fa-solid fa-gear text-xs"></i>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="no-style p-1 text-error hover:scale-110 transition-all bg-transparent border-none shadow-none" title="Salir">
                                <i class="fa-solid fa-right-from-bracket text-[13px]"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @show
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            @section('header')
            <header class="h-16 md:h-20 bg-white border-b border-surface-container flex items-center justify-between px-4 md:px-10 shrink-0">
                <div class="flex items-center gap-2 md:gap-3 flex-1">
                    <button id="sidebar-toggle" class="fa-solid fa-bars text-primary text-lg md:text-xl hover:bg-surface-container-low p-2 md:p-3 rounded-xl transition-all"></button>
                    <div class="bg-surface-container-low px-3 md:px-5 py-2 md:py-2.5 rounded-xl md:rounded-full flex items-center gap-2 md:gap-3 w-full max-w-[120px] md:max-w-[320px] border border-transparent focus-within:border-primary/20 focus-within:bg-white transition-all">
                        <i class="fa-solid fa-search text-outline text-xs md:text-sm"></i>
                        <input type="text" placeholder="Buscar..." class="bg-transparent border-none focus:ring-0 text-xs md:text-sm w-full font-medium placeholder:text-outline p-0">
                    </div>
                    
                    <!-- Reloj en Tiempo Real -->
                    <div class="hidden lg:flex items-center gap-2 md:gap-3 px-3 md:px-4 h-10 md:h-12 rounded-xl md:rounded-2xl bg-surface-container-low/50 border border-surface-container-high/30">
                        <i class="fa-regular fa-clock text-primary text-xs"></i>
                        <div class="flex flex-col">
                            <span id="header-time" class="text-[10px] md:text-[11px] font-black text-primary leading-none uppercase">--:-- --</span>
                            <span id="header-date" class="text-[8px] md:text-[9px] font-medium text-outline uppercase tracking-wider mt-0.5 whitespace-nowrap">Cargando...</span>
                        </div>
                    </div>

                    <!-- Tasa BCV Widget -->
                    <div id="bcv-rate-navbar" class="hidden xl:flex items-center gap-2 md:gap-3 px-3 md:px-4 h-10 md:h-12 rounded-xl md:rounded-2xl bg-green-500/5 border border-green-500/10">
                        <i class="fa-solid fa-money-bill-transfer text-green-600 text-xs"></i>
                        <div class="flex flex-col justify-center">
                            <span class="rate-val text-[10px] md:text-[11px] font-black text-green-600 leading-none uppercase">...</span>
                            <span id="bcv-date" class="text-[7px] md:text-[8px] font-bold text-outline opacity-70 uppercase tracking-tight mt-0.5">BCV</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 md:gap-3 ml-2">
                    <!-- Campanita de Notificaciones -->
                    <div class="relative">
                        <button class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-surface-container-low text-primary flex items-center justify-center hover:bg-surface-container-high transition-all">
                            <i class="fa-solid fa-bell text-sm md:text-base"></i>
                        </button>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-error text-white text-[9px] font-black flex items-center justify-center rounded-full border-2 border-white">3</span>
                    </div>

                    <!-- Botón Pantalla Completa -->
                    <button id="fullscreen-toggle" class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-surface-container-low text-primary hidden sm:flex items-center justify-center hover:bg-surface-container-high transition-all">
                        <i class="fa-solid fa-expand text-sm md:text-base"></i>
                    </button>

                    <button id="theme-toggle" class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-surface-container-low text-primary flex items-center justify-center hover:bg-surface-container-high transition-all">
                        <i id="theme-icon" class="fa-solid fa-moon text-sm md:text-base"></i>
                    </button>
                    
                    <div class="text-right hidden sm:flex flex-col items-end">
                        <p class="text-[11px] md:text-sm font-black text-primary whitespace-nowrap leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] font-bold text-outline truncate max-w-[150px] mb-1 opacity-80">{{ Auth::user()->specialty ?? 'Especialista' }}</p>
                    </div>
                    
                    <div class="group relative">
                        <!-- Intense Status Glow Navbar -->
                        <div class="absolute -inset-1 rounded-lg md:rounded-xl bg-green-500/20 animate-pulse ring-2 ring-green-500/40 pointer-events-none"></div>
                        <button class="w-8 h-8 md:w-10 md:h-10 rounded-lg md:rounded-xl bg-primary/10 flex items-center justify-center text-primary shadow-sm overflow-hidden border border-surface-container-highest shrink-0 relative">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-user-doctor text-sm md:text-base"></i>
                            @endif
                            <!-- Intense Status Dot -->
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full shadow-[0_0_10px_rgba(34,197,94,0.8)]"></div>
                        </button>
                    </div>
                </div>
            </header>
            @show

            <main class="flex-1 overflow-y-auto {{ (trim($__env->yieldContent('noPadding'))) ? '' : 'p-4 md:p-10' }} bg-surface relative">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- DocIA Assistant Mount Point (Smaller & Under FAB) -->
    <div id="doc-ia-global-mic" class="fixed mic-position z-[60]"></div>

    <!-- Modal de Recorte (Cropper) -->
    <div id="cropper-modal" class="fixed inset-0 z-[100] items-center justify-center bg-black/80 backdrop-blur-sm p-4 hidden">
        <div class="bg-white dark:bg-[#1a1c1e] rounded-[2.5rem] w-full max-w-lg overflow-hidden flex flex-col shadow-2xl">
            <div class="p-6 border-b border-surface-container flex justify-between items-center">
                <h3 class="font-black text-primary dark:text-d6e3ff text-xl tracking-tighter">Ajustar Foto de Perfil</h3>
                <button onclick="closeCropper()" class="w-10 h-10 rounded-full hover:bg-surface-container flex items-center justify-center transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 flex-1 bg-[#f0f2f5] dark:bg-[#111418] flex items-center justify-center min-h-[300px]">
                <img id="cropper-image" src="" class="max-w-full block">
            </div>
            <!-- Barra de Zoom -->
            <div class="px-8 py-4 bg-surface-container-low border-t border-surface-container flex items-center gap-4">
                <i class="fa-solid fa-magnifying-glass-minus text-outline"></i>
                <input type="range" id="cropper-zoom" min="0" max="2" step="0.01" value="1" class="flex-1 accent-primary">
                <i class="fa-solid fa-magnifying-glass-plus text-primary"></i>
            </div>
            <div class="p-6 flex gap-3">
                <button onclick="closeCropper()" class="flex-1 py-4 rounded-2xl bg-surface-container text-on-surface-variant font-black">Cancelar</button>
                <button id="save-crop" class="flex-[2] py-4 rounded-2xl hero-gradient text-white font-black shadow-xl">Guardar y Aplicar</button>
            </div>
        </div>
    </div>

    <!-- No FAB -->

    <script>
        $(document).ready(function() {
            const themeToggleBtn = $('#theme-toggle');
            const themeIcon = $('#theme-icon');
            const sidebarBtn = $('#sidebar-toggle');
            const sidebar = $('#main-sidebar');

            // Persistencia del estado del sidebar
            if (localStorage.getItem('sidebar-mini') === 'true') {
                sidebar.addClass('sidebar-mini');
            }

            sidebarBtn.on('click', function() {
                if (window.innerWidth < 1024) {
                    $('body').toggleClass('sidebar-open');
                } else {
                    sidebar.toggleClass('sidebar-mini');
                    localStorage.setItem('sidebar-mini', sidebar.hasClass('sidebar-mini'));
                }
            });

            // Cerrar sidebar al hacer clic en overlay (mobile)
            $('#sidebar-overlay').on('click', function() {
                $('body').removeClass('sidebar-open');
            });

            // Ajustar clases al cambiar tamaño de pantalla
            $(window).on('resize', function() {
                if (window.innerWidth >= 1024) {
                    $('body').removeClass('sidebar-open');
                }
            });

            function updateIcon() {
                if (document.documentElement.classList.contains('dark')) {
                    themeIcon.removeClass('fa-moon').addClass('fa-sun');
                } else {
                    themeIcon.removeClass('fa-sun').addClass('fa-moon');
                }
            }
            updateIcon();
            themeToggleBtn.on('click', function() {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
                updateIcon();
            });

            $('#fab-main-btn').on('click', function() { $(this).toggleClass('active'); $('#fab-options').toggleClass('show'); });
            $(document).on('click', function(e) { if (!$(e.target).closest('.fab-container').length) { $('#fab-main-btn').removeClass('active'); $('#fab-options').removeClass('show'); } });

            // --- Lógica de Recorte y Carga de Foto ---
            let cropper = null;
            const profileInput = $('#profile-upload');
            const cropperModal = $('#cropper-modal');
            const cropperImage = document.getElementById('cropper-image');

            window.closeCropper = function() {
                cropperModal.removeClass('show').fadeOut(200);
                if (cropper) { cropper.destroy(); cropper = null; }
                profileInput.val('');
            };

            profileInput.on('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(event) {
                    cropperImage.src = event.target.result;
                    cropperModal.css('display', 'flex').hide().fadeIn(300).addClass('show');
                    
                    if (cropper) cropper.destroy();
                    
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 1,
                        restore: false,
                        guides: false,
                        center: true,
                        highlight: false,
                        cropBoxMovable: false,
                        cropBoxResizable: false,
                        toggleDragModeOnDblclick: false,
                        ready() { 
                            $('.cropper-view-box, .cropper-face').css('border-radius', '50%'); 
                            // Inicializar slider con el zoom actual
                            const data = cropper.getData();
                            const containerData = cropper.getContainerData();
                            $('#cropper-zoom').on('input', function() {
                                cropper.zoomTo($(this).val());
                            });
                        },
                        zoom(e) {
                            // Sincronizar slider si se hace zoom con rueda
                            $('#cropper-zoom').val(e.detail.ratio);
                        }
                    });
                };
                reader.readAsDataURL(file);
            });

            $('#save-crop').on('click', function() {
                if (!cropper) return;

                const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
                const base64String = canvas.toDataURL('image/jpeg', 0.8);
                
                // Optimismo visual
                $('#header-profile-img').attr('src', base64String);
                $('#section-profile-img').attr('src', base64String);
                closeCropper();

                $.ajax({
                    url: "{{ route('settings.updatePhoto') }}",
                    method: "POST",
                    data: { _token: "{{ csrf_token() }}", photo: base64String },
                    success: function() {
                        Swal.fire({
                            toast: true, position: 'top-end', icon: 'success', 
                            title: '¡Foto actualizada!', showConfirmButton: false, timer: 3000
                        });
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo guardar la imagen.' });
                    }
                });
            });

            $(document).on('submit', '.confirm-delete', function(e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: '¿Confirmar eliminación?',
                    text: "Esta acción es definitiva y no se puede revertir.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ba1a1a',
                    cancelButtonColor: '#6D4AFF',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    background: document.documentElement.classList.contains('dark') ? '#1a1c1e' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#191c1e'
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });

            // Reloj y Fecha en Tiempo Real
            function updateClock() {
                const now = new Date();
                const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
                const dateOptions = { weekday: 'long', day: 'numeric', month: 'long' };
                
                $('#header-time').text(now.toLocaleTimeString('es-ES', timeOptions));
                $('#header-date').text(now.toLocaleDateString('es-ES', dateOptions));
            }
            setInterval(updateClock, 10000); // Actualizar cada 10 segundos
            updateClock();

            // Toggle Fullscreen con Persistencia
            function enterFullscreen() {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                    $('#fullscreen-toggle').find('i').removeClass('fa-expand').addClass('fa-compress');
                    localStorage.setItem('fullscreenEnabled', 'true');
                }
            }

            function exitFullscreen() {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                    $('#fullscreen-toggle').find('i').removeClass('fa-compress').addClass('fa-expand');
                    localStorage.setItem('fullscreenEnabled', 'false');
                }
            }

            $('#fullscreen-toggle').on('click', function(e) {
                e.preventDefault();
                if (!document.fullscreenElement) {
                    enterFullscreen();
                } else {
                    exitFullscreen();
                }
            });

            // Persistencia entre páginas (requiere interacción del usuario)
            if (localStorage.getItem('fullscreenEnabled') === 'true') {
                $(document).one('click', function() {
                    if (!document.fullscreenElement) {
                        enterFullscreen();
                    }
                });
            }

            // Detectar cambios manuales (tecla Esc, etc)
            document.addEventListener('fullscreenchange', () => {
                if (!document.fullscreenElement) {
                    $('#fullscreen-toggle').find('i').removeClass('fa-compress').addClass('fa-expand');
                } else {
                    $('#fullscreen-toggle').find('i').removeClass('fa-expand').addClass('fa-compress');
                }
            });

            // Tasa BCV Logic
            let bcvRate = 1.0;
            async function fetchBcvRate() {
                try {
                    const response = await fetch('https://ve.dolarapi.com/v1/dolares/oficial');
                    const data = await response.json();

                    if (data && data.promedio) {
                        bcvRate = data.promedio;
                        $('#bcv-rate-navbar .rate-val').text(`Bs. ${bcvRate.toFixed(2)}`);
                        if (data.fechaActualizacion) {
                            let dateObj = new Date(data.fechaActualizacion);
                            $('#bcv-date').text('Act: ' + dateObj.toLocaleString('es-VE', { dateStyle: 'short', timeStyle: 'short' }));
                        }
                    }
                } catch (error) {
                    $('#bcv-rate-navbar .rate-val').text(`Bs. --.--`);
                    $('#bcv-date').text('Error al cargar tasa');
                }
            }
            fetchBcvRate();
            setInterval(fetchBcvRate, 300000); // Actualizar cada 5 minutos

            const toastConfig = { toast: true, position: 'top-end', showConfirmButton: false, timer: 3500, timerProgressBar: true };
            @if (Session::has('success')) Swal.fire({ ...toastConfig, icon: 'success', title: "{{ Session::get('success') }}" }); @endif
            @if (Session::has('error')) Swal.fire({ ...toastConfig, icon: 'error', title: "{{ Session::get('error') }}" }); @endif

            // Registro de PWA Service Worker
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js').then((registration) => {
                        console.log('Consultia PWA ServiceWorker registrado con éxito.');
                        
                        // Enviar señal Keep-Alive cada 4 minutos para evitar el throttling agresivo
                        setInterval(() => {
                            if (registration.active) {
                                registration.active.postMessage({ type: 'KEEP_ALIVE' });
                            }
                        }, 240000);
                    }).catch((error) => {
                        console.error('Error al registrar ServiceWorker:', error);
                    });
                });
            }
        });
    </script>
</body>
</html>
