@extends('layouts.app')

@section('title', 'Agendar Cita - Dr. Javier González Pugh')

@section('content')
<div class="bg-surface py-20 min-h-[calc(100vh-80px)]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Botón Volver -->
        <div class="mb-10">
            <a href="/" class="inline-flex items-center gap-3 text-primary font-black hover:text-secondary transition-all group uppercase text-xs tracking-widest">
                <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
                Volver al Inicio
            </a>
        </div>

        <!-- Encabezado del Calendario -->
        <div class="mb-12 p-10 sm:p-12 hero-gradient text-white rounded-[3.5rem] shadow-2xl flex flex-col sm:flex-row items-start sm:items-center gap-10 relative overflow-hidden">
             <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.1),_transparent)]"></div>
            <div class="bg-white/20 w-24 h-24 rounded-[2rem] backdrop-blur-md flex items-center justify-center border border-white/20 relative z-10 shadow-inner">
                <i class="fa-solid fa-calendar-check text-4xl"></i>
            </div>
            <div class="relative z-10">
                <h1 class="text-4xl sm:text-5xl font-black mb-3 tracking-tighter">Reserva tu Cita</h1>
                <p class="text-secondary-container text-lg font-medium opacity-90 max-w-xl">Gestione su salud respiratoria hoy mismo con el Dr. Javier González Pugh.</p>
            </div>
        </div>

        <!-- Contenedor Principal -->
        <div class="bg-white p-6 sm:p-12 rounded-[3.5rem] ambient-shadow border border-surface-container relative">
            <div id="calendar" class="fc-theme-standard font-manrope"></div>
        </div>
    </div>
</div>

<!-- Modal de Agendamiento -->
<div id="appointmentModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div id="modalOverlay" class="fixed inset-0 bg-primary/40 backdrop-blur-md transition-opacity" aria-hidden="true"></div>

        <div class="inline-block align-middle bg-white rounded-[3rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full ambient-shadow border border-surface-container">
            <div class="bg-white px-10 pt-10 pb-6">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-primary/5 rounded-[1.5rem] flex items-center justify-center text-primary shadow-inner">
                            <i class="fa-solid fa-clock-rotate-left text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-primary tracking-tighter" id="modal-title">Agendar Solicitud</h3>
                            <p class="text-[10px] font-black uppercase tracking-widest text-outline mt-1" id="selectedDateDisplay"></p>
                        </div>
                    </div>
                    <button type="button" id="closeModal" class="text-outline hover:text-error transition-colors">
                        <i class="fa-solid fa-circle-xmark text-3xl"></i>
                    </button>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Paciente *</label>
                        <input type="text" id="name" required class="block w-full px-6 py-4 rounded-2xl bg-surface-container-low border-none focus:ring-4 focus:ring-primary/10 transition-all font-bold placeholder:text-outline/50" placeholder="Nombre completo">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Teléfono de Contacto *</label>
                        <input type="tel" id="phone" required class="block w-full px-6 py-4 rounded-2xl bg-surface-container-low border-none focus:ring-4 focus:ring-primary/10 transition-all font-bold placeholder:text-outline/50" placeholder="04XX-XXXXXXX">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Motivo de Consulta</label>
                        <textarea id="reason" rows="3" class="block w-full px-6 py-4 rounded-2xl bg-surface-container-low border-none focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none shadow-inner placeholder:text-outline/50" placeholder="Breve descripción..."></textarea>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-low px-10 py-8">
                <button type="button" id="submitAppointment" class="w-full hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.02] transition-all uppercase tracking-[0.2em]">
                    Confirmar Cita
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/fullcalendar.min.js') }}"></script>

<style>
/* Custom FullCalendar Theme */
.fc-theme-standard .fc-scrollgrid { border: none !important; border-radius: 24px; overflow: hidden; }
.fc-theme-standard td, .fc-theme-standard th { border-color: #f2f4f6 !important; }
.fc-col-header-cell { background-color: #f7f9fb !important; padding: 20px 0 !important; }
.fc-col-header-cell-cushion { color: #00478d !important; font-weight: 900 !important; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.1em; }
.fc-daygrid-day-number { color: #191c1e !important; font-weight: 800; padding: 15px !important; font-size: 1.1rem; }
.fc-day-today { background-color: rgba(0, 71, 141, 0.05) !important; }
.fc-button-primary { background: #00478d !important; border: none !important; border-radius: 16px !important; padding: 12px 24px !important; font-weight: 900 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 0.7rem !important; }
.fc-toolbar-title { font-weight: 900 !important; color: #00478d !important; tracking: -0.05em; font-size: 1.5rem !important; }
.fc-daygrid-day:hover { background-color: #f7f9fb; cursor: pointer; transition: background 0.2s; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            locale: 'es',
            headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
            initialView: 'dayGridMonth',
            height: 'auto',
            dateClick: function(info) {
                $('#selectedDateDisplay').text(info.dateStr);
                $('#appointmentModal').removeClass('hidden').hide().fadeIn(400);
                 $('body').addClass('overflow-hidden');
            }
        });
        calendar.render();

        $('#closeModal, #modalOverlay').on('click', function() {
            $('#appointmentModal').fadeOut(300, function() {
                $(this).addClass('hidden');
                $('body').removeClass('overflow-hidden');
            });
        });

        $('#submitAppointment').on('click', function() {
            if(!$('#name').val() || !$('#phone').val()) {
                alert('Por favor complete los campos obligatorios.');
                return;
            }
            alert('¡Excelente! Su solicitud ha sido enviada. El Dr. Pugh se contactará con usted pronto.');
            location.reload();
        });
    });
</script>
@endsection
