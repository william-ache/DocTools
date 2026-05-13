@extends('layouts.app')

@section('title', 'Dr. Javier González Pugh | Especialista Otorrinolaringólogo')

@section('content')
<!-- Hero Section de Alta Gama -->
<section class="relative min-h-screen flex items-center pt-24 overflow-hidden bg-[#f0f7ff]">
    <!-- Decoración de fondo -->
    <div class="absolute top-0 right-0 w-[60%] h-full bg-gradient-to-l from-white/50 to-transparent z-0"></div>
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-6 md:px-8 w-full grid grid-cols-1 md:grid-cols-2 gap-16 items-center relative z-10">
        <div class="reveal">
            <span class="inline-block px-4 py-2 bg-primary/10 text-primary rounded-full text-xs font-black uppercase tracking-[0.3em] mb-6">Excelencia en Salud Otorrino</span>
            <h1 class="text-6xl md:text-8xl font-black text-primary tracking-tighter mb-8 leading-[0.9]">
                Tu respiración, <br><span class="text-secondary italic font-medium">nuestra pasión.</span>
            </h1>
            <p class="text-xl text-on-surface-variant mb-12 max-w-lg font-medium leading-relaxed">
                Especialista Otorrinolaringólogo dedicado al cuidado integral de oído, nariz y garganta para adultos y niños.
            </p>
            <div class="flex flex-wrap gap-6">
                <a href="#sedes" class="hero-gradient text-white px-12 py-6 rounded-full font-black text-lg shadow-[0_20px_40px_rgba(0,71,141,0.3)] hover:scale-105 active:scale-95 transition-all uppercase tracking-widest flex items-center gap-3">
                    Agendar Cita
                    <i class="fa-solid fa-calendar-days opacity-70"></i>
                </a>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full border-2 border-primary/20 flex items-center justify-center text-primary text-xl">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-primary uppercase tracking-tighter leading-none">Dr. Javier</p>
                        <p class="text-xs text-on-surface-variant font-medium">González-Puga</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="relative reveal delay-300">
            <!-- Frame Cinematográfico -->
            <div class="relative z-10 rounded-[4rem] overflow-hidden shadow-[0_50px_100px_rgba(0,0,0,0.1)] border-[16px] border-white aspect-[4/5] md:aspect-square">
                <img class="w-full h-full object-cover" src="{{ asset('img/doctor.png') }}" alt="Dr. Javier González Pugh">
                <!-- Badge de Confianza -->
                <div class="absolute bottom-10 left-10 right-10 bg-white/80 backdrop-blur-xl p-8 rounded-3xl border border-white/20 shadow-2xl">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-certificate text-3xl text-secondary"></i>
                        <div>
                            <p class="text-primary font-black text-lg uppercase tracking-tight leading-none">Médico Especialista</p>
                            <p class="text-on-surface-variant text-sm font-medium">Certificado Nacional e Internacional</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Elementos flotantes -->
            <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-secondary/10 rounded-full blur-3xl z-0"></div>
        </div>
    </div>
</section>

<!-- Especialidades Premium -->
<section id="especialidades" class="py-40 bg-white relative">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="text-center mb-24 reveal">
            <h2 class="text-5xl md:text-7xl font-black text-primary tracking-tighter mb-6 uppercase">Áreas de Especialización</h2>
            <p class="text-on-surface-variant font-bold uppercase tracking-[0.4em] text-sm">Servicios Médicos de Precisión</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Pediátrica -->
            <div class="group relative bg-[#f8fbff] rounded-[4rem] overflow-hidden border border-surface-container-high hover:border-primary/20 transition-all duration-500 hover:-translate-y-4 reveal shadow-sm hover:shadow-2xl">
                <div class="h-64 overflow-hidden relative">
                    <img src="{{ asset('img/orl_pediatrica.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="ORL Pediátrica">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#f8fbff] via-transparent to-transparent"></div>
                </div>
                <div class="p-12">
                    <h3 class="text-2xl font-black text-primary mb-4 uppercase tracking-tight">ORL Pediátrica</h3>
                    <p class="text-on-surface-variant font-medium leading-relaxed mb-8">Atención cálida y especializada para los problemas de audición y respiración en niños.</p>
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary shadow-inner">
                        <i class="fa-solid fa-child"></i>
                    </div>
                </div>
            </div>

            <!-- Rinología -->
            <div class="group relative bg-[#f8fbff] rounded-[4rem] overflow-hidden border border-surface-container-high hover:border-primary/20 transition-all duration-500 hover:-translate-y-4 reveal delay-100 shadow-sm hover:shadow-2xl">
                <div class="h-64 overflow-hidden relative">
                    <img src="{{ asset('img/rinologia.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Rinología">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#f8fbff] via-transparent to-transparent"></div>
                </div>
                <div class="p-12">
                    <h3 class="text-2xl font-black text-primary mb-4 uppercase tracking-tight">Rinología</h3>
                    <p class="text-on-surface-variant font-medium leading-relaxed mb-8">Soluciones quirúrgicas y médicas para patologías complejas de la nariz y senos paranasales.</p>
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-secondary shadow-lg text-2xl">
                        <i class="fa-solid fa-wind"></i>
                    </div>
                </div>
            </div>

            <!-- Otología -->
            <div class="group relative bg-[#f8fbff] rounded-[4rem] overflow-hidden border border-surface-container-high hover:border-primary/20 transition-all duration-500 hover:-translate-y-4 reveal delay-200 shadow-sm hover:shadow-2xl">
                <div class="h-64 overflow-hidden relative">
                    <img src="{{ asset('img/otologia.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Otología">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#f8fbff] via-transparent to-transparent"></div>
                </div>
                <div class="p-12">
                    <h3 class="text-2xl font-black text-primary mb-4 uppercase tracking-tight">Otología</h3>
                    <p class="text-on-surface-variant font-medium leading-relaxed mb-8">Recupera tu calidad de vida con diagnósticos avanzados de audición y equilibrio.</p>
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary shadow-lg text-2xl">
                        <i class="fa-solid fa-ear-deaf"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección Consultorio: Paz y Confort -->
<section class="py-40 bg-primary relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="absolute top-0 right-0 w-[50%] h-full bg-gradient-to-l from-secondary/20 to-transparent"></div>
    
    <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10 grid grid-cols-1 md:grid-cols-2 gap-20 items-center">
        <div class="reveal">
            <h2 class="text-5xl md:text-7xl font-black text-white mb-8 tracking-tighter leading-none">Paz, confort y <br><span class="italic text-secondary">tecnología.</span></h2>
            <p class="text-white/70 text-lg md:text-xl font-medium mb-12 leading-relaxed">
                Nuestras instalaciones están diseñadas para brindar una experiencia tranquila mientras utilizamos equipos de última generación para tu diagnóstico.
            </p>
            <ul class="space-y-6 mb-12">
                <li class="flex items-center gap-4 text-white font-bold text-lg">
                    <i class="fa-solid fa-circle-check text-secondary text-2xl"></i>
                    Entorno Minimalista y Relajante
                </li>
                <li class="flex items-center gap-4 text-white font-bold text-lg">
                    <i class="fa-solid fa-circle-check text-secondary text-2xl"></i>
                    Privacidad Absoluta para Pacientes
                </li>
                <li class="flex items-center gap-4 text-white font-bold text-lg">
                    <i class="fa-solid fa-circle-check text-secondary text-2xl"></i>
                    Equipamiento de Oto-Video Endoscopia
                </li>
            </ul>
        </div>
        <div class="relative reveal">
            <div class="rounded-[4rem] overflow-hidden border-8 border-white/10 shadow-2xl skew-x-1 hover:skew-x-0 transition-transform duration-700">
                <img src="{{ asset('img/consultorio.png') }}" class="w-full h-full object-cover" alt="Consultorio Premium">
            </div>
        </div>
    </div>
</section>

<!-- Ubicaciones y Contacto Estilizado -->
<section id="sedes" class="py-40 bg-surface">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-24 gap-8">
            <div class="reveal text-center md:text-left">
                <h2 class="text-5xl md:text-7xl font-black text-primary tracking-tighter mb-4">Nuestras Sedes</h2>
                <p class="text-secondary font-black tracking-widest uppercase text-sm">Cerca de ti en el estado Aragua</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Maracay -->
            <div class="bg-white p-12 rounded-[5rem] shadow-xl reveal group border border-primary/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -translate-y-12 translate-x-12"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-8 text-3xl">
                        <i class="fa-solid fa-hospital"></i>
                    </div>
                    <h3 class="text-4xl font-black text-primary mb-2">Maracay</h3>
                    <p class="text-xl text-secondary font-black mb-8">Maternidad La Floresta</p>
                    <div class="space-y-4 mb-10">
                        <div class="flex items-center gap-4 text-on-surface-variant font-bold">
                            <i class="fa-solid fa-location-dot text-primary"></i>
                            <span>Urb. La Floresta, Piso 2, Cons. 25</span>
                        </div>
                    </div>
                    <a href="https://wa.me/584144567818" target="_blank" class="flex items-center justify-center gap-4 bg-primary text-white w-full py-6 rounded-full font-black text-lg shadow-lg hover:shadow-primary/30 transition-all hover:-translate-y-1">
                        <i class="fa-brands fa-whatsapp text-2xl"></i>
                        AGENDAR EN MARACAY
                    </a>
                </div>
            </div>

            <!-- Palo Negro -->
            <div class="bg-white p-12 rounded-[5rem] shadow-xl reveal delay-100 group border border-secondary/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-full -translate-y-12 translate-x-12"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary mb-8 text-3xl">
                        <i class="fa-solid fa-house-medical"></i>
                    </div>
                    <h3 class="text-4xl font-black text-primary mb-2">Palo Negro</h3>
                    <p class="text-xl text-secondary font-black mb-8">C.M. Los Aviadores</p>
                    <div class="space-y-4 mb-10">
                        <div class="flex items-center gap-4 text-on-surface-variant font-bold">
                            <i class="fa-solid fa-location-dot text-primary"></i>
                            <span>C.C. Parque Los Aviadores, Cons. 10</span>
                        </div>
                    </div>
                    <a href="https://wa.me/584140173052" target="_blank" class="flex items-center justify-center gap-4 bg-secondary text-white w-full py-6 rounded-full font-black text-lg shadow-lg hover:shadow-secondary/30 transition-all hover:-translate-y-1">
                        <i class="fa-brands fa-whatsapp text-2xl"></i>
                        AGENDAR EN PALO NEGRO
                    </a>
                </div>
            </div>
        </div>

        <!-- Instagram Block -->
        <div class="mt-20 p-12 bg-white rounded-[5rem] shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8 reveal border-4 border-secondary/10">
            <div class="flex items-center gap-8">
                <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] p-1 shadow-xl">
                    <div class="w-full h-full rounded-full border-4 border-white flex items-center justify-center text-white text-4xl">
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-3xl font-black text-primary tracking-tight">Síguenos en Instagram</h4>
                    <p class="text-on-surface-variant font-medium">Consejos médicos y el día a día de nuestro consultorio</p>
                </div>
            </div>
            <a href="https://www.instagram.com/dr.javier.orl/?hl=es" target="_blank" class="bg-[#262626] text-white px-12 py-6 rounded-full font-black text-lg hover:bg-black transition-all shadow-xl uppercase tracking-widest">
                @dr.javier.orl
            </a>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        const observerOptions = { threshold: 0.1 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });
</script>

<style>
    .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
    .delay-100 { transition-delay: 100ms; }
    .delay-200 { transition-delay: 200ms; }
    .delay-300 { transition-delay: 300ms; }
</style>
@endsection
