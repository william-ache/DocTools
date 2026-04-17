<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Dr. Javier González Pugh</title>
    
    @php
        $appSettings = \App\Models\Setting::first() ?? (object)[
            'app_name' => 'Consultia', 
            'primary_color' => '#00478d',
            'enabled_modules' => ['consultorios', 'servicios', 'finanzas', 'pacientes']
        ];
        $enabledMods = $appSettings->enabled_modules ?? [];
    @endphp
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
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
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
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
        .dark .text-primary { color: #d6e3ff !important; }
        .dark .text-outline, .dark .text-on-surface-variant { color: #c2c7d0 !important; }
        .dark .bg-surface-container-low { background-color: #1d2024 !important; }
        .dark .ambient-shadow { box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .dark input, .dark select, .dark textarea { background-color: #1d2024 !important; color: white !important; }

        .sidebar-active {
            background-color: #d6e3ff;
            color: #00478d;
            font-weight: 800;
        }
        .dark .sidebar-active {
            background-color: #00478d !important;
            color: white !important;
        }

        .swal2-popup {
            border-radius: 2rem !important;
            padding: 2rem !important;
            font-family: 'Manrope', sans-serif !important;
        }
        .swal2-title { font-weight: 900 !important; tracking: -0.05em !important; }
        .swal2-confirm, .swal2-cancel { border-radius: 1rem !important; font-weight: 800 !important; padding: 12px 30px !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; }
        
        /* FAB Animations */
        .fab-container { position: fixed; bottom: 30px; right: 30px; z-index: 50; display: flex; flex-direction: column; align-items: flex-end; gap: 15px; }
        .fab-options { display: none; flex-direction: column; align-items: flex-end; gap: 10px; opacity: 0; transform: translateY(20px); transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .fab-options.show { display: flex; opacity: 1; transform: translateY(0); }
        .fab-main-btn.active { transform: rotate(45deg); }

        /* Ajustes de posición para FAB y Mic */
        .fab-position { bottom: 100px; right: 30px; }
        .mic-position { bottom: 30px; right: 34px; }
    </style>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-surface text-on-surface font-manrope antialiased overflow-hidden h-screen">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="w-72 h-full bg-white border-r border-surface-container flex flex-col p-6 z-20">
            <div class="text-2xl font-black tracking-tighter text-primary flex items-center gap-3 mb-10 px-2 leading-none">
                <i class="fa-solid fa-house-medical text-3xl"></i>
                {{ $appSettings->app_name }}
            </div>

            <nav class="flex-1 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}">
                    <i class="fa-solid fa-chart-pie text-xl w-6"></i>
                    <span class="text-sm">Panel de Control</span>
                </a>

                @if(in_array('consultorios', $enabledMods))
                <a href="{{ route('consultorios.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('consultorios.*') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}">
                    <i class="fa-solid fa-hospital text-xl w-6"></i>
                    <span class="text-sm">Mis Consultorios</span>
                </a>
                @endif

                @if(in_array('servicios', $enabledMods))
                <a href="{{ route('servicios.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('servicios.*') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}">
                    <i class="fa-solid fa-stethoscope text-xl w-6"></i>
                    <span class="text-sm">Servicios</span>
                </a>
                @endif

                @if(in_array('finanzas', $enabledMods))
                <a href="{{ route('metodos.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('metodos.*') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}">
                    <i class="fa-solid fa-wallet text-xl w-6"></i>
                    <span class="text-sm">Finanzas</span>
                </a>
                @endif

                <div class="pt-4 pb-2 px-4">
                    <p class="text-[10px] font-black text-outline uppercase tracking-widest">Atención</p>
                </div>

                <a href="#" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-on-surface-variant hover:bg-surface-container-low transition-all">
                    <i class="fa-solid fa-calendar-check text-xl w-6"></i>
                    <span class="text-sm">Citas Médicas</span>
                </a>

                @if(in_array('pacientes', $enabledMods))
                <a href="{{ route('pacientes.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('pacientes.*') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}">
                    <i class="fa-solid fa-users text-xl w-6"></i>
                    <span class="text-sm">Pacientes</span>
                </a>
                @endif
            </nav>

            <div class="pt-6 border-t border-surface-container space-y-2">
                <a href="{{ route('settings.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl {{ request()->routeIs('settings.*') ? 'sidebar-active' : 'text-on-surface-variant hover:bg-surface-container-low transition-all' }}">
                    <i class="fa-solid fa-gear text-xl w-6"></i>
                    <span class="text-sm font-semibold">Configuración</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl text-error font-bold hover:bg-error-container/30 transition-all">
                        <i class="fa-solid fa-right-from-bracket text-xl w-6"></i>
                        <span class="text-sm">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <header class="h-20 bg-white border-b border-surface-container flex items-center justify-between px-10 shrink-0">
                <div class="flex items-center gap-4">
                    <button class="fa-solid fa-bars lg:hidden text-primary text-xl"></button>
                    <div class="bg-surface-container-low px-5 py-2.5 rounded-full flex items-center gap-3 w-96 border border-transparent focus-within:border-primary/20 focus-within:bg-white transition-all">
                        <i class="fa-solid fa-search text-outline"></i>
                        <input type="text" placeholder="Búsqueda rápida..." class="bg-transparent border-none focus:ring-0 text-sm w-full font-medium placeholder:text-outline">
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <button id="theme-toggle" class="w-10 h-10 rounded-xl bg-surface-container-low text-primary flex items-center justify-center hover:bg-surface-container-high transition-all">
                        <i id="theme-icon" class="fa-solid fa-moon"></i>
                    </button>
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-black text-primary">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] font-bold text-outline uppercase tracking-widest text-secondary">{{ Auth::user()->specialty ?? 'Especialista' }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary shadow-sm overflow-hidden border border-surface-container-highest">
                        <i class="fa-solid fa-user-doctor text-xl"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-10 bg-surface relative">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- DocIA Assistant Mount Point (Smaller & Under FAB) -->
    <div id="doc-ia-global-mic" class="fixed mic-position z-[60]"></div>

    <!-- FAB Button -->
    <div class="fab-container fab-position">
        <div class="fab-options" id="fab-options">
            <a href="#" class="flex items-center gap-3 px-5 py-3 bg-white text-primary rounded-2xl shadow-xl border border-surface-container hover:bg-primary hover:text-white transition-all">
                <span class="text-xs font-bold">Nueva Cita</span>
                <i class="fa-solid fa-calendar-plus text-lg"></i>
            </a>
            @if(in_array('pacientes', $enabledMods))
            <a href="{{ route('pacientes.create') }}" class="flex items-center gap-3 px-5 py-3 bg-white text-primary rounded-2xl shadow-xl border border-surface-container hover:bg-primary hover:text-white transition-all">
                <span class="text-xs font-bold">Nuevo Paciente</span>
                <i class="fa-solid fa-user-plus text-lg"></i>
            </a>
            @endif
        </div>
        <button id="fab-main-btn" class="w-16 h-16 bg-primary text-white rounded-full shadow-2xl flex items-center justify-center transition-all hover:scale-110 active:scale-95 fab-main-btn">
            <i class="fa-solid fa-plus text-3xl"></i>
        </button>
    </div>

    <script>
        $(document).ready(function() {
            const themeToggleBtn = $('#theme-toggle');
            const themeIcon = $('#theme-icon');
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

            $(document).on('submit', '.confirm-delete', function(e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: '¿Confirmar eliminación?',
                    text: "Esta acción es definitiva y no se puede revertir.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ba1a1a',
                    cancelButtonColor: '#00478d',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    background: document.documentElement.classList.contains('dark') ? '#1a1c1e' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#191c1e'
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            });

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
