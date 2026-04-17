@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    <!-- Header -->
    <div class="fade-element">
        <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Editar Consultorio</h1>
        <p class="text-on-surface-variant font-medium text-lg">Actualiza la configuración de tu sede de atención.</p>
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

    <form id="multi-step-form" action="{{ route('consultorios.update', $consultorio) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- PASO 1: INFORMACIÓN BÁSICA -->
        <div class="step-content px-2 space-y-8" data-step="1">
            <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase">Nombre del Consultorio *</label>
                    <input type="text" name="name" required value="{{ $consultorio->name }}" class="block w-full px-6 py-5 border-2 border-primary/20 rounded-2xl transition-all font-bold text-lg">
                </div>
                <div class="space-y-4">
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase">Ubicación física</label>
                    <input type="text" name="address" value="{{ $consultorio->address }}" class="block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none" placeholder="Dirección completa...">
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
                            <input type="number" name="rest_time_between_appointments" value="{{ $consultorio->rest_time_between_appointments }}" class="block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-on-surface mb-2 tracking-widest uppercase">Duración estándar (min)</label>
                            <input type="number" name="standard_appointment_duration" value="{{ $consultorio->standard_appointment_duration }}" class="block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold">
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-on-surface mb-2 tracking-widest uppercase">Anticipación máxima (días)</label>
                            <input type="number" name="max_days_anticipation" value="{{ $consultorio->max_days_anticipation }}" class="block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-on-surface mb-2 tracking-widest uppercase">Zona horaria</label>
                            <div class="relative">
                                <select name="timezone" class="block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold appearance-none">
                                    <option value="America/Caracas" {{ $consultorio->timezone == 'America/Caracas' ? 'selected' : '' }}>Caracas (UTC-4)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-10 border-t border-surface-container grid grid-cols-1 md:grid-cols-2 gap-8">
                    <label class="flex items-center gap-4 cursor-pointer group">
                        <input type="checkbox" name="whatsapp_reminders" value="1" {{ $consultorio->whatsapp_reminders ? 'checked' : '' }} class="w-6 h-6 rounded-lg text-primary focus:ring-primary border-surface-container-highest">
                        <div>
                            <p class="text-sm font-black text-primary group-hover:underline">Recordatorios por WhatsApp</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-4 cursor-pointer group">
                        <input type="checkbox" name="accept_bookings" value="1" {{ $consultorio->accept_bookings ? 'checked' : '' }} class="w-6 h-6 rounded-lg text-primary focus:ring-primary border-surface-container-highest">
                        <div>
                            <p class="text-sm font-black text-primary group-hover:underline">Aceptar reservas</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- PASO 3: MÉTODOS DE PAGO -->
        <div class="step-content hidden px-2 space-y-8" data-step="3">
            <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @php $pagoIds = $consultorio->metodosPago->pluck('id')->toArray(); @endphp
                    @foreach($metodos as $metodo)
                    <label class="relative flex items-center gap-4 p-6 rounded-2xl bg-surface-container-low cursor-pointer hover:bg-secondary-container/30 transition-all border-2 border-transparent has-[:checked]:border-primary has-[:checked]:bg-white">
                        <input type="checkbox" name="metodos[]" value="{{ $metodo->id }}" {{ in_array($metodo->id, $pagoIds) ? 'checked' : '' }} class="hidden">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-primary" style="background-color: {{ $metodo->color }}20; color: {{ $metodo->color }};">
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
            <div class="bg-white p-12 rounded-[2.5rem] ambient-shadow border border-surface-container">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
                    @php $servicioIds = $consultorio->servicios->pluck('id')->toArray(); @endphp
                    @foreach($servicios as $servicio)
                    <label class="flex items-center gap-4 p-5 rounded-2xl bg-surface-container-low cursor-pointer hover:bg-secondary-container/30 transition-all border border-transparent has-[:checked]:bg-white has-[:checked]:shadow-md">
                        <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}" {{ in_array($servicio->id, $servicioIds) ? 'checked' : '' }} class="w-5 h-5 rounded text-primary border-outline-variant">
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
                ACTUALIZAR CONSULTORIO
            </button>
        </div>
    </form>

    <div class="pt-10 border-t border-surface-container mx-2">
        <form action="{{ route('consultorios.destroy', $consultorio) }}" method="POST" class="confirm-delete">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-error font-bold flex items-center gap-3 hover:underline text-sm uppercase tracking-widest">
                <i class="fa-solid fa-trash"></i> Eliminar consultorio definitivamente
            </button>
        </form>
    </div>
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
