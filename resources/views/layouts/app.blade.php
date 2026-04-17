<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Consultia - Gestión Médica')</title>
    
    <!-- Fuentes y Iconos (Google Fonts + Font Awesome) -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    
    <!-- Design System Tokens & Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#00478d",
                        "primary-container": "#005eb8",
                        "secondary": "#006687",
                        "secondary-container": "#80d3fd",
                        "tertiary": "#516266",
                        "tertiary-container": "#d4e6eb",
                        "surface": "#f7f9fb",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#424752",
                        "surface-container-low": "#f2f4f6",
                        "surface-container-high": "#e6e8ea",
                        "surface-container-highest": "#e0e3e5",
                        "surface-container-lowest": "#ffffff",
                        "outline-variant": "rgba(194, 198, 212, 0.15)",
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
    
    @vite(['resources/js/app.jsx'])
    
    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #00478d 0%, #005eb8 100%);
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-manrope antialiased">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl shadow-sm transition-all duration-300" id="main-nav">
        <div class="flex justify-between items-center px-8 h-20 max-w-7xl mx-auto tracking-tight font-medium">
            <div class="text-2xl font-black tracking-tighter text-primary flex items-center gap-3">
                <i class="fa-solid fa-house-medical text-3xl"></i>
                Dr. Javier González Pugh
            </div>
            <div class="flex items-center gap-4">
                <a href="/calendario" class="hero-gradient text-white px-8 py-3 rounded-full font-black text-sm uppercase tracking-widest transition-all shadow-lg hover:shadow-xl hover:scale-105 active:scale-95" id="btn-login">
                    Agendar Cita
                </a>
            </div>
        </div>
    </nav>

    <main class="pt-20">
        @yield('content')
    </main>

    <footer class="bg-white w-full border-t border-surface-container">
        <div class="flex flex-col md:flex-row justify-between items-center py-12 px-8 max-w-7xl mx-auto font-manrope font-light tracking-wide text-sm">
            <div class="mb-8 md:mb-0">
                <div class="flex items-center gap-3 text-lg font-black text-primary mb-2">
                    <i class="fa-solid fa-house-medical"></i> Dr. Javier González Pugh
                </div>
                <div class="text-on-surface-variant font-medium">Otorrinolaringología - Niños y Adultos | @maternidadfl</div>
            </div>
            <div class="flex flex-wrap justify-center gap-8 text-on-surface-variant font-bold">
                <a class="hover:text-primary transition-colors" href="#">Privacidad</a>
                <a class="hover:text-primary transition-colors" href="#">Términos</a>
                <a class="hover:text-primary transition-colors text-primary" href="{{ route('admin.dashboard') }}">Acceso Médico</a>
            </div>
        </div>
    </footer>
    
    <script>
        $(document).ready(function() {
            $(window).scroll(function() {
                if ($(window).scrollTop() > 10) {
                    $('#main-nav').addClass('shadow-md');
                } else {
                    $('#main-nav').removeClass('shadow-md');
                }
            });
        });
    </script>
</body>
</html>
