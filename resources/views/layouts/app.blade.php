<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Consultia - Gestión Médica')</title>
    
    <!-- Fuentes y Iconos -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#00478d",
                        "secondary": "#00b4d8",
                        "surface": "#f8fbff",
                        "on-surface": "#121417",
                        "on-surface-variant": "#4a5568",
                    }
                }
            }
        }
    </script>
    
    @vite(['resources/js/app.jsx'])
    
    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
</head>
<body class="bg-surface font-manrope selection:bg-primary/10">

    <!-- Navbar Minimalista -->
    <nav class="fixed top-0 left-0 right-0 z-[100] h-24 flex items-center bg-white/80 backdrop-blur-xl border-b border-primary/5">
        <div class="max-w-7xl mx-auto px-6 md:px-8 w-full flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white text-xl shadow-lg">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                <span class="text-2xl font-black text-primary tracking-tighter uppercase">DocTools <span class="text-secondary font-medium">Premium</span></span>
            </div>
            
            <div class="hidden md:flex items-center gap-10">
                <a href="#especialidades" class="text-xs font-black text-primary hover:text-secondary transition-colors uppercase tracking-[0.2em]">Especialidades</a>
                <a href="#sedes" class="text-xs font-black text-primary hover:text-secondary transition-colors uppercase tracking-[0.2em]">Sedes</a>
                <a href="{{ route('login') }}" class="px-8 py-3 bg-primary text-white rounded-full font-black text-xs uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
                    Iniciar Sesión
                </a>
            </div>

            <button class="md:hidden text-primary text-2xl">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Footer de Calidad -->
    <footer class="bg-white py-24 border-t border-primary/5">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-20 text-center md:text-left">
                <div class="col-span-1 md:col-span-2">
                    <p class="text-2xl font-black text-primary tracking-tighter uppercase mb-6 text-center md:text-left">
                        Dr. Javier <span class="text-secondary">González-Puga</span>
                    </p>
                    <p class="text-on-surface-variant font-medium max-w-sm mx-auto md:mx-0">Otorrinolaringología especializada para adultos y niños. Atención integral con los más altos estándares de calidad.</p>
                </div>
                <div>
                    <h4 class="text-xs font-black text-primary uppercase tracking-[0.3em] mb-8">Navegación</h4>
                    <ul class="space-y-4 font-bold text-on-surface-variant text-sm">
                        <li><a href="#especialidades" class="hover:text-primary transition-colors uppercase tracking-widest">Especialidades</a></li>
                        <li><a href="#sedes" class="hover:text-primary transition-colors uppercase tracking-widest">Sedes</a></li>
                        <li><a href="{{ route('politica-privacidad') }}" class="hover:text-primary transition-colors uppercase tracking-widest">Privacidad</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-black text-primary uppercase tracking-[0.3em] mb-8">Redes Sociales</h4>
                    <div class="flex justify-center md:justify-start gap-6 text-2xl text-primary">
                        <a href="https://www.instagram.com/dr.javier.orl/?hl=es" target="_blank" class="hover:text-secondary transition-colors"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="hover:text-secondary transition-colors"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <p class="text-center text-[10px] text-on-surface-variant/40 font-black tracking-[0.4em] uppercase">© {{ date('Y') }} Dr. Javier González-Puga • DocTools Premium Edition</p>
        </div>
    </footer>

    <!-- Lenis Smooth Scroll -->
    <script src="https://unpkg.com/lenis@1.0.45/dist/lenis.min.js"></script> 
    <script>
        const lenis = new Lenis()
        function raf(time) {
            lenis.raf(time)
            requestAnimationFrame(raf)
        }
        requestAnimationFrame(raf)
    </script>
</body>
</html>
