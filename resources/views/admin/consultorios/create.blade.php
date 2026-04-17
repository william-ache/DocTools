@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    <!-- Header -->
    <div class="fade-element">
        <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Configurar Consultorio</h1>
        <p class="text-on-surface-variant font-medium text-lg">Personaliza la experiencia de tus pacientes en esta sede.</p>
    </div>

    <!-- Stepper Dinámico -->
    <div class="bg-white p-8 rounded-[2.5rem] ambient-shadow border border-surface-container">
        <div class="flex justify-between items-center mb-6 px-2">
            <h3 id="step-title" class="text-xl font-black text-primary tracking-tighter italic">Información básica</h3>
            <span id="step-counter" class="text-xs font-bold text-outline uppercase tracking-widest">Paso 1 de 4</span>
        </div>
        <div class="w-full bg-surface-container-low h-2.5 rounded-full overflow-hidden">
            <div id="step-progress" class="bg-primary h-full w-1/4 rounded-full transition-all duration-500"></div>
        </div>
    </div>

    <form id="multi-step-form" action="{{ route('consultorios.store') }}" method="POST">
        @csrf
        
        <!-- PASO 1: INFORMACIÓN BÁSICA -->
        <div class="step-content px-2 space-y-8" data-step="1">
            <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase">Nombre del Consultorio *</label>
                    <input type="text" name="name" required class="block w-full px-6 py-5 border-2 border-primary/20 rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-lg" placeholder="Ej. Consultorio Médico Central">
                </div>
                <div class="space-y-4">
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase">Ubicación física</label>
                    <div class="relative rounded-2xl overflow-hidden h-48 border border-surface-container">
                        <img src="https://maps.googleapis.com/maps/api/staticmap?center=10.2474,-67.5925&zoom=14&size=800x200&key=mock" class="w-full h-full object-cover">
                    </div>
                    <input type="text" name="address" class="block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none" placeholder="Dirección completa...">
                </div>
            </div>
        </div>

        <!-- PASO 2: CONFIGURACIÓN DE CITAS -->
        <div class="step-content hidden px-2 space-y-8" data-step="2">
            <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-on-surface mb-2 tracking-widest uppercase">Tiempo de descanso (min)</label>
                            <input type="number" name="rest_time_between_appointments" value="15" class="block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-on-surface mb-2 tracking-widest uppercase">Duración estándar (min)</label>
                            <input type="number" name="standard_appointment_duration" value="30" class="block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold">
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-on-surface mb-2 tracking-widest uppercase">Anticipación máxima (días)</label>
                            <input type="number" name="max_days_anticipation" value="30" class="block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-on-surface mb-2 tracking-widest uppercase">Zona horaria</label>
                            <div class="relative">
                                <select name="timezone" class="block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold appearance-none">
                                    <option value="America/Caracas">Caracas (UTC-4)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-10 border-t border-surface-container grid grid-cols-1 md:grid-cols-2 gap-8">
                    <label class="flex items-center gap-4 cursor-pointer group">
                        <input type="checkbox" name="whatsapp_reminders" value="1" checked class="w-6 h-6 rounded-lg text-primary focus:ring-primary border-surface-container-highest">
                        <div>
                            <p class="text-sm font-black text-primary group-hover:underline">Recordatorios por WhatsApp</p>
                            <p class="text-[10px] text-outline">Envía alertas automáticas 24h antes.</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-4 cursor-pointer group">
                        <input type="checkbox" name="accept_bookings" value="1" checked class="w-6 h-6 rounded-lg text-primary focus:ring-primary border-surface-container-highest">
                        <div>
                            <p class="text-sm font-black text-primary group-hover:underline">Aceptar reservas</p>
                            <p class="text-[10px] text-outline">Habilita el agendamiento online.</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- PASO 3: MÉTODOS DE PAGO -->
        <div class="step-content hidden px-2 space-y-8" data-step="3">
            <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container">
                <div class="flex items-center gap-4 mb-10">
                    <i class="fa-solid fa-wallet text-primary text-3xl"></i>
                    <h3 class="text-xl font-black text-primary tracking-tighter">Métodos de Pago Aceptados</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($metodos as $metodo)
                    <label class="relative flex items-center gap-4 p-6 rounded-2xl bg-surface-container-low cursor-pointer hover:bg-secondary-container/30 transition-all border-2 border-transparent has-[:checked]:border-primary has-[:checked]:bg-white">
                        <input type="checkbox" name="metodos[]" value="{{ $metodo->id }}" class="hidden">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: {{ $metodo->color }}20; color: {{ $metodo->color }};">
                            <i class="fa-solid {{ $metodo->icon ?? 'fa-credit-card' }} text-xl"></i>
                        </div>
                        <span class="text-sm font-bold text-primary">{{ $metodo->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- PASO 4: SERVICIOS -->
        <div class="step-content hidden px-2 space-y-8" data-step="4">
            <div class="bg-white p-12 rounded-[2.5rem] ambient-shadow border border-surface-container text-center">
                <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6 text-primary">
                    <i class="fa-solid fa-hand-holding-medical text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-primary mb-2">Agrega los servicios que ofreces</h3>
                <p class="text-on-surface-variant mb-12 max-w-lg mx-auto">Define los servicios disponibles para que los pacientes puedan agendar la atención adecuada.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
                    @foreach($servicios as $servicio)
                    <label class="flex items-center gap-4 p-5 rounded-2xl bg-surface-container-low cursor-pointer hover:bg-secondary-container/30 transition-all border border-transparent has-[:checked]:bg-white has-[:checked]:shadow-md">
                        <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}" class="w-5 h-5 rounded text-primary border-outline-variant">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: {{ $servicio->color }}20; color: {{ $servicio->color }};">
                            <i class="fa-solid {{ $servicio->icon ?? 'fa-stethoscope' }} text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-primary">{{ $servicio->name }}</p>
                            <p class="text-[10px] text-outline uppercase tracking-widest font-black">${{ number_format($servicio->price, 0) }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- FOOTER NAVEGACIÓN -->
        <div class="flex flex-col sm:flex-row gap-6 pt-10 px-2">
            <button type="button" id="prev-btn" class="hidden flex-1 py-5 border-2 border-surface-container rounded-2xl font-bold text-outline hover:bg-surface-container-low transition-all">
                Anterior
            </button>
            <a href="{{ route('consultorios.index') }}" id="cancel-link" class="flex-1 text-center py-5 border-2 border-surface-container rounded-2xl font-bold text-outline hover:bg-surface-container-low transition-all">
                Cancelar
            </a>
            <button type="button" id="next-btn" class="flex-[2] hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                Siguiente <i class="fa-solid fa-arrow-right"></i>
            </button>
            <button type="submit" id="submit-btn" class="hidden flex-[2] hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.02] transition-all">
                CREAR CONSULTORIO
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    let currentStep = 1;
    const totalSteps = 4;
    const titles = {
        1: "Información básica",
        2: "Configuración de citas",
        3: "Métodos de pago",
        4: "Servicios"
    };

    function updateUI() {
        $('.step-content').addClass('hidden');
        $(`.step-content[data-step="${currentStep}"]`).removeClass('hidden');
        $('#step-title').text(titles[currentStep]);
        $('#step-counter').text(`Paso ${currentStep} de ${totalSteps}`);
        $('#step-progress').css('width', `${(currentStep / totalSteps) * 100}%`);
        if (currentStep === 1) {
            $('#prev-btn').addClass('hidden');
            $('#cancel-link').removeClass('hidden');
        } else {
            $('#prev-btn').removeClass('hidden');
            $('#cancel-link').addClass('hidden');
        }
        if (currentStep === totalSteps) {
            $('#next-btn').addClass('hidden');
            $('#submit-btn').removeClass('hidden');
        } else {
            $('#next-btn').removeClass('hidden');
            $('#submit-btn').addClass('hidden');
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    $('#next-btn').on('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateUI();
        }
    });

    $('#prev-btn').on('click', function() {
        if (currentStep > 1) {
            currentStep--;
            updateUI();
        }
    });
});
</script>
@endsection
