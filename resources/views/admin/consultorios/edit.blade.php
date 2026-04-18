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
            <span id="step-counter" class="text-xs font-bold text-outline uppercase tracking-widest">Paso 1 de 5</span>
        </div>
        <div class="w-full bg-surface-container-low h-2.5 rounded-full overflow-hidden">
            <div id="step-progress" class="bg-primary h-full w-1/5 rounded-full transition-all duration-500"></div>
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
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase">Teléfono del Consultorio</label>
                    <input type="text" name="phone" value="{{ $consultorio->phone }}" class="block w-full px-6 py-5 border-2 border-primary/20 rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-lg" placeholder="Ej. +58 412 1234567">
                </div>
                <div class="space-y-4">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-3 mb-2">
                        <label class="block text-xs font-black text-on-surface tracking-widest uppercase">Ubicación exacta (Selecciona en el mapa)</label>
                        <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                            <div class="relative w-full md:w-32">
                                <select id="map-country" class="select2-basic w-full px-4 py-3 bg-white border border-surface-container rounded-xl text-xs font-bold focus:ring-2 focus:ring-primary/20 appearance-none transition-all">
                                    <option value="ve" selected>Venezuela</option>
                                    <option value="co">Colombia</option>
                                    <option value="es">España</option>
                                    <option value="us">USA</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-primary text-[10px] pointer-events-none"></i>
                            </div>
                            <div class="relative w-full md:w-64">
                                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-primary opacity-50"></i>
                                <input type="text" id="map-search" class="w-full pl-10 pr-24 py-3 bg-white border border-surface-container rounded-xl text-xs font-bold focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Zona o ciudad...">
                                <button type="button" id="btn-search-map" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-primary text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-primary-container transition-all">Buscar</button>
                            </div>
                        </div>
                    </div>

                    <div id="map" class="relative rounded-3xl overflow-hidden h-72 border-2 border-surface-container-low shadow-inner">
                        <div class="absolute inset-0 flex items-center justify-center bg-surface-container-low z-10 pointer-events-none" id="map-loader">
                            <i class="fa-solid fa-circle-notch animate-spin text-primary text-2xl"></i>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="hidden" name="latitude" id="lat" value="{{ $consultorio->latitude }}">
                        <input type="hidden" name="longitude" id="lng" value="{{ $consultorio->longitude }}">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-outline uppercase tracking-widest ml-1">Dirección Detectada / Sugerida</label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-5 top-1/2 -translate-y-1/2 text-primary"></i>
                            <input type="text" name="address" id="address-input" value="{{ $consultorio->address }}" class="block w-full pl-12 pr-6 py-5 bg-surface-container-low rounded-2xl border-none font-bold text-sm focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Haz clic en el mapa para detectar la dirección...">
                        </div>
                        <p class="text-[10px] text-outline italic ml-1">* Puedes editar la dirección manualmente si es necesario.</p>
                    </div>
                </div>
            </div>
        </div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .leaflet-container { font-family: 'Manrope', sans-serif !important; border-radius: 1.5rem; }
    .leaflet-popup-content-wrapper { border-radius: 1rem !important; padding: 5px !important; }
    .leaflet-marker-icon { filter: drop-shadow(0 10px 10px rgba(0,0,0,0.2)); }
</style>

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
                        <div>
                            <label class="block text-xs font-black text-on-surface mb-2 tracking-widest uppercase">Intervalo del Calendario (min)</label>
                            <select name="calendar_interval" class="select2-basic block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold">
                                <option value="15" {{ $consultorio->calendar_interval == 15 ? 'selected' : '' }}>15 Minutos</option>
                                <option value="30" {{ $consultorio->calendar_interval == 30 ? 'selected' : '' }}>30 Minutos</option>
                                <option value="45" {{ $consultorio->calendar_interval == 45 ? 'selected' : '' }}>45 Minutos</option>
                                <option value="60" {{ $consultorio->calendar_interval == 60 ? 'selected' : '' }}>60 Minutos (1 Hora)</option>
                            </select>
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
                                <select name="timezone" class="select2-basic block w-full px-5 py-4 bg-surface-container-low rounded-xl border-none font-bold">
                                    <option value="America/Caracas" {{ $consultorio->timezone == 'America/Caracas' ? 'selected' : '' }}>Caracas (UTC-4)</option>
                                </select>
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

        <!-- PASO 5: HORARIOS DE ATENCIÓN -->
        <div class="step-content hidden px-2 space-y-8" data-step="5">
            <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container">
                <div class="flex items-center gap-4 mb-8">
                    <i class="fa-solid fa-clock text-primary text-3xl"></i>
                    <h3 class="text-xl font-black text-primary tracking-tighter">Horarios de Atención</h3>
                </div>
                
                <div class="space-y-4">
                    @php
                        $dias = [
                            1 => 'Lunes',
                            2 => 'Martes',
                            3 => 'Miércoles',
                            4 => 'Jueves',
                            5 => 'Viernes',
                            6 => 'Sábado',
                            0 => 'Domingo'
                        ];
                        // Mapear los horarios actuales del consultorio
                        $currentHours = $consultorio->workingHours ?? collect();
                    @endphp

                    @foreach($dias as $index => $dia)
                    @php
                        $wh = $currentHours->where('day_of_week', $index)->first();
                    @endphp
                    <div class="flex flex-col md:flex-row items-center gap-4 p-4 bg-surface-container-low rounded-2xl border border-transparent hover:border-primary/20 transition-all">
                        <div class="w-full md:w-32 flex items-center gap-3">
                            <input type="checkbox" name="horarios[{{ $index }}][active]" value="1" class="w-5 h-5 rounded text-primary border-outline-variant" {{ ($wh && $wh->is_active) || (!$wh && $index > 0 && $index < 6) ? 'checked' : '' }}>
                            <span class="text-sm font-black text-primary uppercase tracking-widest">{{ $dia }}</span>
                        </div>
                        
                        <div class="flex-1 flex items-center gap-4 w-full">
                            <div class="flex-1">
                                <input type="time" name="horarios[{{ $index }}][start]" value="{{ $wh ? \Carbon\Carbon::parse($wh->start_time)->format('H:i') : '08:00' }}" class="w-full px-4 py-2 bg-white rounded-xl border border-surface-container font-bold text-sm">
                            </div>
                            <span class="text-xs font-black text-outline">A</span>
                            <div class="flex-1">
                                <input type="time" name="horarios[{{ $index }}][end]" value="{{ $wh ? \Carbon\Carbon::parse($wh->end_time)->format('H:i') : '17:00' }}" class="w-full px-4 py-2 bg-white rounded-xl border border-surface-container font-bold text-sm">
                            </div>
                        </div>
                    </div>
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
            <button type="submit" class="no-style bg-transparent text-red-600 border-none font-bold flex items-center gap-3 hover:underline text-sm uppercase tracking-widest p-0 shadow-none">
                <i class="fa-solid fa-trash"></i> Eliminar consultorio definitivamente
            </button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // LEAFLET MAP LOGIC
    const initialLat = $('#lat').val() || 10.4806;
    const initialLng = $('#lng').val() || -66.8983;

    const map = L.map('map', { 
        zoomControl: false,
        attributionControl: false
    }).setView([initialLat, initialLng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    L.control.zoom({ position: 'bottomright' }).addTo(map);

    let marker;
    $('#map-loader').fadeOut();

    // Mostrar el marcador si ya tiene coordenadas
    if ($('#lat').val() && $('#lng').val()) {
        marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);
    }

    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng, { draggable: true }).addTo(map);
        }

        updateCoords(lat, lng);
        reverseGeocode(lat, lng);
    });

    // BÚSQUEDA EN MAPA
    async function searchInMap() {
        const query = $('#map-search').val();
        const country = $('#map-country').val();
        if (!query) return;

        $('#map-loader').fadeIn();
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&countrycodes=${country}&limit=1`);
            const data = await response.json();

            if (data && data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);

                map.setView([lat, lng], 16);

                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                }

                updateCoords(lat, lng);
                $('#address-input').val(result.display_name).removeClass('animate-pulse');
            } else {
                Swal.fire({
                    title: 'No encontrado',
                    text: 'No pudimos localizar esa zona. Intenta con algo más específico.',
                    icon: 'warning',
                    confirmButtonColor: '#6D4AFF'
                });
            }
        } catch (error) {
            console.error('Search error:', error);
        } finally {
            $('#map-loader').fadeOut();
        }
    }

    $('#btn-search-map').on('click', searchInMap);
    $('#map-search').on('keypress', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            searchInMap();
        }
    });

    function updateCoords(lat, lng) {
        $('#lat').val(lat);
        $('#lng').val(lng);
    }

    async function reverseGeocode(lat, lng) {
        $('#address-input').val('Detectando dirección...').addClass('animate-pulse');
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
            const data = await response.json();
            
            if (data && data.display_name) {
                const parts = data.display_name.split(',');
                const cleanAddress = parts.slice(0, 3).join(',').trim();
                $('#address-input').val(cleanAddress).removeClass('animate-pulse');
            }
        } catch (error) {
            $('#address-input').val('').removeClass('animate-pulse');
            console.error('Error geocoding:', error);
        }
    }

    let currentStep = 1;
    const totalSteps = 5;
    const titles = {
        1: "Información básica",
        2: "Configuración de citas",
        3: "Métodos de pago",
        4: "Servicios",
        5: "Horarios de Atención"
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
