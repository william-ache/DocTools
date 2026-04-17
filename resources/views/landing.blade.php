@extends('layouts.app')

@section('title', 'Dr. Javier González Pugh - Otorrinolaringólogo')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[auto] md:min-h-[800px] flex items-center overflow-hidden bg-white py-20">
    <div class="max-w-7xl mx-auto px-6 md:px-8 w-full grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
        <!-- Hero Text -->
        <div class="z-10 text-center md:text-left">
            <span class="inline-block px-5 py-2 mb-8 rounded-full bg-primary/10 text-primary text-[10px] font-black tracking-[0.2em] uppercase fade-element">
                Citas Disponibles • Maternidad La Floresta
            </span>
            <h1 class="text-6xl md:text-8xl font-black text-primary tracking-tighter mb-8 leading-[0.9] fade-element">
                Respirar es <br><span class="text-secondary italic">Vivir.</span>
            </h1>
            <p class="text-lg md:text-xl text-on-surface-variant mb-12 max-w-lg mx-auto md:mx-0 leading-relaxed fade-element font-medium">
                Cuidado integral de <strong>oído, nariz y garganta</strong> para niños y adultos con tecnología de vanguardia.
                <br><br>
                <span class="text-secondary font-black text-2xl italic tracking-tight">"Haciendo lo que amamos"</span>
            </p>
            <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-6 fade-element">
                <a href="/calendario" class="hero-gradient inline-flex items-center justify-center text-white px-12 py-6 rounded-full font-black text-xl shadow-2xl hover:scale-105 active:scale-95 transition-all uppercase tracking-widest">
                    AGENDA TU CITA
                </a>
            </div>
        </div>
        
        <!-- Hero Image -->
        <div class="relative h-[450px] md:h-[650px] w-full fade-element">
            <div class="absolute inset-0 bg-secondary/10 rounded-[4rem] -rotate-3 border border-surface-container"></div>
            <img class="absolute inset-0 w-full h-full object-cover rounded-[4rem] shadow-2xl z-10" src="{{ asset('img/doctor.png') }}" alt="Dr. Javier González Pugh">
            
            <div class="absolute -bottom-8 -right-4 md:-right-8 bg-white p-8 rounded-[2.5rem] shadow-2xl z-20 max-w-[280px] border border-surface-container">
                <div class="flex items-center gap-4 mb-4 text-primary">
                    <i class="fa-solid fa-circle-check text-3xl"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest leading-none">Especialista<br>Certificado</span>
                </div>
                <p class="text-base font-black text-primary font-manrope">Otorrinolaringología Integral</p>
                <p class="text-[10px] text-outline font-bold mt-1 uppercase tracking-tighter">Universidad Central de Venezuela</p>
            </div>
        </div>
    </div>
</section>

<!-- Especialidades -->
<section id="especialidades" class="py-32 bg-surface">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="mb-24 text-center max-w-3xl mx-auto">
            <span class="text-secondary font-black tracking-[0.3em] text-[10px] uppercase mb-4 block">Excelencia Médica</span>
            <h2 class="text-5xl md:text-6xl font-black text-primary tracking-tighter mb-6">Tratamientos Avanzados</h2>
            <div class="w-24 h-1.5 bg-secondary mx-auto rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Servicio 1 -->
            <div class="group bg-white p-12 rounded-[3.5rem] transition-all duration-500 hover:-translate-y-4 ambient-shadow border border-surface-container flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-primary/5 rounded-3xl flex items-center justify-center text-primary mb-10 group-hover:bg-primary group-hover:text-white transition-all shadow-inner">
                    <i class="fa-solid fa-child-reaching text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-primary mb-4">ORL Pediátrica</h3>
                <p class="text-on-surface-variant font-medium leading-relaxed">Atención delicada para infecciones de oído y problemas de amígdalas en niños.</p>
            </div>

            <!-- Servicio 2 -->
            <div class="group bg-white p-12 rounded-[3.5rem] transition-all duration-500 hover:-translate-y-4 ambient-shadow border border-surface-container flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-primary/5 rounded-3xl flex items-center justify-center text-primary mb-10 group-hover:bg-primary group-hover:text-white transition-all shadow-inner">
                    <i class="fa-solid fa-wind text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-primary mb-4">Rinología</h3>
                <p class="text-on-surface-variant font-medium leading-relaxed">Soluciones para sinusitis, tabique desviado y rinitis con técnicas modernas.</p>
            </div>

            <!-- Servicio 3 -->
            <div class="group bg-white p-12 rounded-[3.5rem] transition-all duration-500 hover:-translate-y-4 ambient-shadow border border-surface-container flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-primary/5 rounded-3xl flex items-center justify-center text-primary mb-10 group-hover:bg-primary group-hover:text-white transition-all shadow-inner">
                    <i class="fa-solid fa-ear-listen text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-primary mb-4">Otología</h3>
                <p class="text-on-surface-variant font-medium leading-relaxed">Diagnóstico de pérdida auditiva, manejo de vértigo y salud del oído externo.</p>
            </div>
        </div>
    </div>
</section>

<!-- Trayectoria -->
<section class="py-32 bg-white">
    <div class="max-w-7xl mx-auto px-6 md:px-8 grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
        <div class="relative order-2 lg:order-1">
            <div class="aspect-[4/5] rounded-[4rem] overflow-hidden border-[16px] border-surface-container-low shadow-2xl">
                <img class="w-full h-full object-cover" src="{{ asset('img/consultorio.png') }}" alt="Consulta médica">
            </div>
        </div>
        
        <div class="order-1 lg:order-2 text-center md:text-left">
            <span class="text-secondary font-black tracking-[0.3em] text-[10px] uppercase mb-4 block">Sobre el Especialista</span>
            <h2 class="text-5xl font-black text-primary mb-10 tracking-tighter leading-none">Compromiso con la Salud de tu Familia</h2>
            <div class="space-y-8 text-on-surface-variant font-medium leading-relaxed text-lg">
                <p>El <strong>Dr. Javier González Pugh</strong> es Médico Cirujano egresado de la UCV, con más de 15 años transformando vidas a través de una respiración sana.</p>
                <div class="space-y-6">
                    <div class="flex items-center gap-6">
                        <i class="fa-solid fa-award text-secondary text-3xl"></i>
                        <span class="text-primary font-black">Especialista acreditado en Niños y Adultos.</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <i class="fa-solid fa-microscope text-secondary text-3xl"></i>
                        <span class="text-primary font-black">Cirugía mínimamente invasiva.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ubicaciones -->
<section id="ubicaciones" class="py-32 bg-surface">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="flex flex-col lg:flex-row justify-between lg:items-end mb-20 gap-10 text-center lg:text-left">
            <div class="max-w-xl mx-auto lg:mx-0">
                <span class="text-secondary font-black tracking-[0.3em] text-[10px] uppercase mb-4 block">Visítanos</span>
                <h2 class="text-5xl md:text-6xl font-black text-primary tracking-tighter">Sedes de Atención</h2>
            </div>
            <div id="whatsapp-trigger" class="hero-gradient text-white p-10 rounded-[2.5rem] flex flex-col md:flex-row items-center gap-8 shadow-2xl mx-auto lg:mx-0 cursor-pointer hover:scale-105 transition-all group">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center group-hover:bg-white group-hover:text-primary transition-all">
                    <i class="fa-brands fa-whatsapp text-4xl"></i>
                </div>
                <div class="text-left">
                    <p class="text-[10px] opacity-80 uppercase font-black tracking-widest mb-1">WhatsApp de Reservas</p>
                    <p class="text-xl font-black font-manrope">Solicitar Información</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Maracay -->
            <div class="bg-white p-12 rounded-[3.5rem] border-b-8 border-primary ambient-shadow">
                <h3 class="text-3xl font-black text-primary mb-2">Maracay</h3>
                <p class="text-xl text-secondary mb-8 font-black italic">Maternidad La Floresta</p>
                <div class="flex items-center gap-6 text-on-surface mb-6">
                    <i class="fa-solid fa-location-dot text-primary text-2xl w-6"></i>
                    <p class="text-lg font-bold">Consultorio 1-14</p>
                </div>
                <p class="text-on-surface-variant font-medium">Zona norte de Maracay, atención especializada en un entorno seguro.</p>
            </div>

            <!-- Palo Negro -->
            <div class="bg-white p-12 rounded-[3.5rem] border-b-8 border-secondary ambient-shadow">
                <h3 class="text-3xl font-black text-primary mb-2">Palo Negro</h3>
                <p class="text-xl text-secondary mb-8 font-black italic">C.C. Ovalles</p>
                <div class="flex items-center gap-6 text-on-surface mb-6">
                    <i class="fa-solid fa-location-dot text-primary text-2xl w-6"></i>
                    <p class="text-lg font-bold">Piso 2, Local 10</p>
                </div>
                <p class="text-on-surface-variant font-medium">Ubicación estratégica y céntrica para tu comodidad.</p>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Selección de WhatsApp -->
<div id="whatsappModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-whatsapp" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div id="wModalOverlay" class="fixed inset-0 bg-primary/40 backdrop-blur-md transition-opacity" aria-hidden="true"></div>

        <div class="inline-block align-middle bg-white rounded-[3.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full ambient-shadow border border-surface-container">
            <div class="bg-white px-10 py-12">
                <div class="flex items-center justify-between mb-10">
                    <h3 class="text-3xl font-black text-primary tracking-tighter" id="modal-whatsapp">Contactar vía WhatsApp</h3>
                    <button type="button" id="closeWModal" class="text-outline hover:text-error transition-colors">
                        <i class="fa-solid fa-circle-xmark text-3xl"></i>
                    </button>
                </div>
                
                <div class="space-y-6">
                    <a href="https://wa.me/584144567818" target="_blank" class="flex items-center gap-6 p-6 rounded-[2rem] bg-surface-container-low hover:bg-secondary/10 transition-all group border-2 border-transparent hover:border-secondary">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-sm">
                            <i class="fa-solid fa-mobile-screen-button text-3xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] uppercase font-black tracking-widest text-secondary opacity-80 mb-1">Principal</p>
                            <p class="text-2xl font-black text-primary">0414-4567818</p>
                        </div>
                    </a>

                    <a href="https://wa.me/584140173052" target="_blank" class="flex items-center gap-6 p-6 rounded-[2rem] bg-surface-container-low hover:bg-secondary/10 transition-all group border-2 border-transparent hover:border-secondary">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-sm">
                            <i class="fa-solid fa-mobile-screen-button text-3xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] uppercase font-black tracking-widest text-secondary opacity-80 mb-1">Secundario</p>
                            <p class="text-2xl font-black text-primary">0414-0173052</p>
                        </div>
                    </a>
                </div>
                
                <p class="text-center text-sm text-outline mt-10 font-bold italic">"Haciendo lo que amamos"</p>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#whatsapp-trigger').on('click', function() {
            $('#whatsappModal').removeClass('hidden').hide().fadeIn(400);
            $('body').addClass('overflow-hidden');
        });

        $('#closeWModal, #wModalOverlay').on('click', function() {
            $('#whatsappModal').fadeOut(300, function() {
                $(this).addClass('hidden');
                $('body').removeClass('overflow-hidden');
            });
        });

        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            var $target = $(this.hash);
            if($target.length) {
                $('html, body').stop().animate({
                    'scrollTop': $target.offset().top - 100
                }, 800, 'swing');
            }
        });
    });
</script>

<style>
    .fade-element {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Footer Banner -->
<section class="py-40 bg-primary text-white relative overflow-hidden text-center">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(0,102,135,0.4),_transparent)]"></div>
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <p class="text-2xl font-medium italic mb-6 opacity-80">"@maternidadfl - Cuidando cada respiro"</p>
        <h2 class="text-6xl md:text-8xl font-black mb-16 tracking-tighter leading-none">¿Cómo podemos<br>ayudarte?</h2>
        <a href="/calendario" class="bg-white text-primary px-16 py-8 rounded-full font-black text-2xl hover:scale-105 transition-all shadow-2xl uppercase tracking-[0.2em] inline-block">
            QUIERO MI CITA
        </a>
    </div>
</section>
@endsection
