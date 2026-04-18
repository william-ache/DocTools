@extends('layouts.admin')

@section('content')
<div class="fade-element space-y-6 pb-10">
    <!-- Header del Calendario -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl md:text-3xl font-black text-primary tracking-tight">Calendario</h1>
            <i class="fa-regular fa-circle-question text-primary/40 text-lg cursor-help"></i>
        </div>
        <button class="bg-primary text-white px-6 py-3 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            Agendar Cita
            <i class="fa-solid fa-plus text-[10px]"></i>
        </button>
    </div>

    <!-- Pestañas de Vista y Filtros -->
    <div class="flex flex-col gap-4">
        <!-- Vistas -->
        <div class="flex items-center gap-6 border-b border-surface-container overflow-x-auto pb-px">
            <button class="calendar-view-tab active px-2 py-3 text-[11px] font-black text-primary uppercase tracking-[0.1em] relative" data-view="timeGridWeek">
                Semana
                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-primary rounded-t-full"></div>
            </button>
            <button class="calendar-view-tab px-2 py-3 text-[11px] font-bold text-outline uppercase tracking-[0.1em] hover:text-primary transition-colors" data-view="timeGridDay">
                Día
            </button>
            <button class="calendar-view-tab px-2 py-3 text-[11px] font-bold text-outline uppercase tracking-[0.1em] hover:text-primary transition-colors" data-view="listWeek">
                Lista
            </button>
        </div>

        <!-- Barra de Herramientas / Filtros -->
        <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-3 rounded-2xl border border-surface-container shadow-sm">
            <div class="flex items-center gap-2">
                <div class="flex items-center bg-surface-container-low rounded-lg p-0.5 px-1 border border-surface-container-high/30">
                    <button id="cal-prev" class="p-1.5 hover:bg-white rounded-md transition-all text-primary"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
                    <span id="cal-date-range" class="px-3 text-xs font-black text-primary tracking-tight whitespace-nowrap">-- - --</span>
                    <button id="cal-next" class="p-1.5 hover:bg-white rounded-md transition-all text-primary"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
                </div>
                <button id="cal-today" class="px-4 py-2 bg-surface-container-low text-primary text-[11px] font-black rounded-lg border border-surface-container-high/30 hover:bg-white transition-all uppercase tracking-tighter">Hoy</button>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Dropdown Consultorios -->
                <div class="relative">
                    <select id="consultorio-filter" class="select2-basic w-full min-w-[200px] px-4 py-2 bg-surface-container-low text-primary text-[11px] font-black rounded-lg border border-surface-container-high/30 focus:ring-2 focus:ring-primary/20 appearance-none transition-all">
                        <option value="all" data-interval="30">Todos los consultorios</option>
                        @foreach($consultorios as $cons)
                            <option value="{{ $cons->id }}" data-interval="{{ $cons->calendar_interval ?? 30 }}">
                                {{ $cons->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Chips de Estado -->
                <div class="flex items-center gap-1.5 overflow-x-auto" id="status-filters">
                    <button data-status="all" class="status-chip active flex items-center gap-1.5 px-3 py-1.5 bg-surface-container-low text-outline rounded-full border border-surface-container-high/30 text-[10px] font-black uppercase tracking-tighter whitespace-nowrap shadow-sm hover:shadow-md transition-all">
                        Todos
                    </button>
                    <button data-status="agendada" class="status-chip flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-full border border-blue-100/50 text-[10px] font-black uppercase tracking-tighter whitespace-nowrap shadow-sm hover:shadow-md transition-all">
                        <i class="fa-solid fa-eye text-[8px]"></i>
                        Agendadas
                    </button>
                    <button data-status="confirmada" class="status-chip flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-600 rounded-full border border-purple-100/50 text-[10px] font-black uppercase tracking-tighter whitespace-nowrap shadow-sm hover:shadow-md transition-all">
                        <i class="fa-solid fa-eye text-[8px]"></i>
                        Confirmadas
                    </button>
                    <button data-status="completada" class="status-chip flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-600 rounded-full border border-green-100/50 text-[10px] font-black uppercase tracking-tighter whitespace-nowrap shadow-sm hover:shadow-md transition-all">
                        <i class="fa-solid fa-eye text-[8px]"></i>
                        Completadas
                    </button>
                    <button data-status="cancelada" class="status-chip flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 rounded-full border border-red-100/50 text-[10px] font-black uppercase tracking-tighter whitespace-nowrap shadow-sm hover:shadow-md transition-all">
                        <i class="fa-solid fa-eye-slash text-[8px]"></i>
                        Canceladas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor del Calendario -->
    <div class="bg-white p-4 md:p-6 rounded-3xl border border-surface-container shadow-sm overflow-hidden content-fade">
        <div id="calendar" class="min-h-[600px]"></div>
    </div>
</div><!-- Modal de Nueva Cita -->
<div id="eventModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-primary/20 backdrop-blur-sm transition-all">
    <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl border border-surface-container overflow-hidden scale-95 opacity-0 transition-all duration-300" id="modalContent">
        <div class="p-8 border-b border-surface-container flex items-center justify-between bg-surface-container-low/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                    <i class="fa-solid fa-calendar-plus text-lg"></i>
                </div>
                <div>
                    <h3 id="modalTitle" class="text-xl font-bold text-primary tracking-tight">Nueva Cita</h3>
                    <p class="text-[10px] font-black uppercase tracking-widest text-outline">Programación Manual</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button id="btnDeleteAppointment" style="display: none;" class="w-8 h-8 rounded-full hover:bg-red-50 text-red-500 transition-colors flex items-center justify-center">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                <button onclick="closeModal()" class="w-8 h-8 rounded-full hover:bg-surface-container-high transition-colors text-outline">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <form id="appointmentForm" class="p-8 space-y-4">
            <input type="hidden" name="id" id="appointment_id">
            <!-- Buscar Paciente -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Paciente (Nombre o Cédula)</label>
                <div class="relative no-icon-select">
                    <select name="patient_id" id="patient_select" class="w-full" required>
                        <option value="">Seleccione un paciente...</option>
                    </select>
                </div>
            </div>

            <!-- Fila: Consultorio y Estado -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Consultorio</label>
                    <select name="consultorio_id" id="consultorio_select" class="w-full select2-basic" required>
                        @foreach($consultorios as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Estado Initial</label>
                    <select name="status" class="w-full select2-basic">
                        <option value="agendada">Agendada</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="completada">Completada</option>
                    </select>
                </div>
            </div>

            <!-- Fila: Hora -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Hora Inicio</label>
                    <input type="time" name="start_time" id="start_time" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Hora Fin</label>
                    <input type="time" name="end_time" id="end_time" class="w-full px-5 py-3 rounded-2xl bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 font-bold text-sm">
                </div>
            </div>

            <!-- Motivo -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-outline uppercase tracking-widest ml-1">Motivo de Consulta (Opcional)</label>
                <textarea name="reason" rows="2" class="w-full px-5 py-3 bg-surface-container-low rounded-2xl border-none focus:ring-2 focus:ring-primary/20 font-medium text-sm resize-none"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-white py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] shadow-lg hover:scale-[1.02] transition-all">
                    Guardar Cita
                </button>
            </div>
        </form>
    </div>
</div>

<!-- FullCalendar Dependencies -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

<style>
    /* Reset FullCalendar Styles to match Design */
    .fc { 
        --fc-border-color: #e2e8f0; 
        --fc-today-bg-color: rgba(109, 74, 255, 0.03); 
    }
    .fc-theme-standard .fc-scrollgrid { border: 1px solid #e2e8f0 !important; }
    .fc .fc-col-header-cell { padding: 0 !important; background: transparent !important; }
    
    /* Etiqueta persistente de HORARIO */
    .fc .fc-col-header-cell-axis .fc-scrollgrid-sync-inner::before {
        content: 'HORARIO';
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        font-size: 11px;
        font-weight: 900;
        color: #6D4AFF;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    /* Línea indicadora de tiempo actual */
    .fc-now-indicator-line {
        border-color: #6D4AFF !important;
        border-width: 1.5px !important;
        opacity: 0.6;
    }
    /* Ocultar indicator en vista de lista */
    .fc-list-view .fc-now-indicator-line {
        display: none !important;
    }

    .fc-now-indicator-arrow {
        border-color: #6D4AFF !important;
        background-color: #6D4AFF !important;
    }

    /* Celdas más visibles */
    .fc-timegrid-slot {
        height: 3.5rem !important;
        border-bottom: 1px solid #cbd5e1 !important;
    }
    .fc-timegrid-slot:hover {
        background-color: rgba(109, 74, 255, 0.05) !important;
        cursor: pointer;
    }
    .fc-timegrid-col {
        border-left: 1px solid #cbd5e1 !important;
        border-right: 1px solid #cbd5e1 !important;
    }
    .fc .fc-timegrid-slot-label-cushion { 
        font-size: 13px !important; 
        font-weight: 900 !important; 
        color: #191c1e !important; 
        text-transform: uppercase !important;
    }
    
    /* Current Time Line */
    .fc .fc-timegrid-now-indicator-line { border-color: #ff4d4f !important; border-width: 2px !important; }
    .fc .fc-timegrid-now-indicator-arrow { border-color: #ff4d4f !important; border-width: 5px !important; top: -5px !important; }

    /* Events Styling */
    .fc-v-event { 
        background-color: transparent !important; 
        border: none !important; 
        padding: 0 !important;
    }
    .fc-event-main { padding: 0 !important; }
    
    .event-card {
        border-radius: 12px;
        padding: 8px 12px;
        height: 100%;
        border-left: 4px solid;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .event-card.agendada { background: #eff6ff; border-color: #3b82f6; color: #1d4ed8; }
    .event-card.confirmada { background: #f3e8ff; border-color: #a855f7; color: #7e22ce; }
    .event-card.completada { background: #f0fdf4; border-color: #22c55e; color: #15803d; }
    .event-card.cancelada { background: #fef2f2; border-color: #f87171; color: #b91c1c; }
    
    .event-card .patient-name { font-weight: 900; font-size: 11px; margin-bottom: 2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .event-card .event-notes { font-size: 9px; opacity: 0.7; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-bottom: 2px; line-height: 1.1; }
    .event-card .event-time { font-weight: 700; font-size: 10px; opacity: 0.8; margin-top: auto; }

    /* List View Customizations */
    .fc-list-event-dot { display: none !important; }
    .fc-list-event-title { padding: 4px 8px !important; }
    .fc-list-table { border-collapse: separate !important; border-spacing: 0 4px !important; }
    .fc-list-day-cushion { background-color: #f8fafc !important; }

    /* Tags/Chips Status Colors (Active) */
    .status-chip[data-status="all"].active { background-color: #6D4AFF !important; color: white !important; border-color: #6D4AFF !important; }
    .status-chip[data-status="agendada"].active { background-color: #2563eb !important; color: white !important; border-color: #2563eb !important; }
    .status-chip[data-status="confirmada"].active { background-color: #9333ea !important; color: white !important; border-color: #9333ea !important; }
    .status-chip[data-status="completada"].active { background-color: #16a34a !important; color: white !important; border-color: #16a34a !important; }
    .status-chip[data-status="cancelada"].active { background-color: #dc2626 !important; color: white !important; border-color: #dc2626 !important; }
    
    .status-chip i { font-size: 8px; }
    .status-chip.active i { color: white !important; }

    /* Hide Original Header */
    .fc-header-toolbar { display: none !important; }
    
    .content-fade {
        animation: fadeIn 0.8s ease-out;
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    /* Select2 Container Fix for Modals */
    .select2-container { width: 100% !important; }
    .select2-container--default .select2-selection--single { height: 48px !important; background-color: #f2f4f6 !important; border-radius: 1rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 48px !important; padding-left: 1.25rem !important; font-weight: 700 !important; color: #191c1e !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { 
        display: none !important; 
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        display: none !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow::after {
        content: '\f078';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: 10px;
        color: #64748b;
        margin-top: -2px;
    }
    .select2-container--default .select2-selection--single .select2-selection__clear { display: none !important; }
    
    /* Select2 Dropdown / Search Field Styling */
    .select2-dropdown { border: 1px solid #e2e8f0 !important; border-radius: 1rem !important; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1) !important; overflow: hidden; }
    .select2-search--dropdown { padding: 12px !important; }
    .select2-container--default .select2-search--dropdown .select2-search__field { 
        border: none !important; 
        background-color: #f2f4f6 !important; 
        border-radius: 0.75rem !important; 
        padding: 8px 12px !important;
        outline: none !important;
    }
</style>

<script>
    function initSelect2() {
        // Init select2 for elements INSIDE the modal
        $('#eventModal .select2-basic').select2({
            dropdownParent: $('#eventModal')
        });

        $('#patient_select').select2({
            dropdownParent: $('#eventModal'),
            placeholder: 'Buscar paciente por nombre o cédula...',
            minimumInputLength: 2,
            ajax: {
                url: "{{ route('pacientes.search') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data.results };
                },
                cache: true
            }
        });
    }

    let currentSelectedDate = null;

    function openModal(info, event = null) {
        const modal = document.getElementById('eventModal');
        const content = document.getElementById('modalContent');
        const btnDelete = document.getElementById('btnDeleteAppointment');
        const titleEl = document.getElementById('modalTitle');
        const form = document.getElementById('appointmentForm');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            initSelect2();
        }, 10);

        if (event) {
            titleEl.innerText = 'Editar Cita';
            btnDelete.style.display = 'flex';
            document.getElementById('appointment_id').value = event.id;
            
            // Set patient
            if (event.title) {
                const option = new Option(event.title, event.extendedProps.patient_id, true, true);
                $('#patient_select').append(option).trigger('change');
            }
            
            $('#consultorio_select').val(event.extendedProps.consultorio_id).trigger('change');
            $('select[name="status"]').val(event.extendedProps.status).trigger('change');
            $('textarea[name="reason"]').val(event.extendedProps.notes);
            
            currentSelectedDate = event.start;
            document.getElementById('start_time').value = event.start.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('end_time').value = event.end.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
        } else {
            titleEl.innerText = 'Nueva Cita';
            btnDelete.style.display = 'none';
            form.reset();
            document.getElementById('appointment_id').value = '';
            $('#patient_select').val(null).trigger('change');
            
            currentSelectedDate = info.start;
            document.getElementById('start_time').value = info.start.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('end_time').value = info.end.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
            
            const filterVal = $('#consultorio-filter').val();
            if (filterVal !== 'all') {
                $('#consultorio_select').val(filterVal).trigger('change');
            }
        }
    }

    function closeModal() {
        const modal = document.getElementById('eventModal');
        const content = document.getElementById('modalContent');
        
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'es',
            firstDay: 0,
            selectable: true,
            editable: true,
            allDaySlot: false,
            slotMinTime: '08:00:00',
            slotMaxTime: '20:00:00',
            slotDuration: '00:30:00',
            slotLabelInterval: '00:30:00',
            nowIndicator: true,
            noEventsText: 'No hay eventos para mostrar',
            dayHeaderFormat: { weekday: 'long', day: 'numeric', omitCommas: true },
            slotLabelFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short', hour12: true },
            dayHeaderContent: function(arg) {
                let dayName = arg.date.toLocaleDateString('es', { weekday: 'long' });
                let dayNumber = arg.date.getDate();
                let isToday = arg.isToday;
                
                let html = '<div class="flex flex-col items-center justify-center py-2">';
                html += '<span class="text-[10px] font-black text-outline uppercase tracking-wider leading-tight mb-1">' + dayName + '</span>';
                html += '<span class="w-8 h-8 flex items-center justify-center rounded-full text-base font-black ' + (isToday ? 'bg-primary text-white shadow-md' : 'text-primary') + '">' + dayNumber + '</span>';
                html += '</div>';

                return { html: html };
            },
            events: {
                url: '{{ route("appointments.index") }}',
                extraParams: function() {
                    return {
                        consultorio_id: $('#consultorio-filter').val(),
                        status: $('.status-chip.active').data('status')
                    };
                }
            },
            select: function(info) {
                openModal(info);
            },
            eventClick: function(info) {
                openModal(null, info.event);
            },
            eventDrop: function(info) {
                updateEventTime(info.event);
            },
            eventResize: function(info) {
                updateEventTime(info.event);
            },
            eventContent: function(arg) {
                let status = arg.event.extendedProps.status || 'agendada';
                let notes = arg.event.extendedProps.notes || '';
                let time = arg.timeText;
                return {
                    html: `
                         <div class="event-card ${status}">
                            <div class="patient-name">
                                <i class="fa-solid fa-user mr-1 opacity-50"></i>
                                ${arg.event.title}
                            </div>
                            ${notes ? `<div class="event-notes">${notes}</div>` : ''}
                            <div class="event-time">${time}</div>
                        </div>
                    `
                };
            },
            datesSet: function(info) {
                document.getElementById('cal-date-range').innerText = info.view.title;
            }
        });
        calendar.render();

        // Controladores de Vista
        document.querySelectorAll('.calendar-view-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // UI Update
                document.querySelectorAll('.calendar-view-tab').forEach(t => {
                    t.classList.remove('active', 'text-primary');
                    t.classList.add('text-outline');
                    const indicator = t.querySelector('div');
                    if (indicator) indicator.remove();
                });
                this.classList.add('active', 'text-primary');
                this.classList.remove('text-outline');
                this.innerHTML += '<div class="absolute bottom-0 left-0 w-full h-0.5 bg-primary rounded-t-full"></div>';

                // Calendar Update
                calendar.changeView(this.dataset.view);
            });
        });

        // Controles de Navegación
        document.getElementById('cal-prev').addEventListener('click', () => calendar.prev());
        document.getElementById('cal-next').addEventListener('click', () => calendar.next());
        document.getElementById('cal-today').addEventListener('click', () => calendar.today());

        // Manejo del Formulario
        $('#appointmentForm').on('submit', function(e) {
            e.preventDefault();
            
            const appointmentId = $('#appointment_id').val();
            const isUpdate = !!appointmentId;

            // Construir fecha completa
            let year = currentSelectedDate.getFullYear();
            let month = String(currentSelectedDate.getMonth() + 1).padStart(2, '0');
            let day = String(currentSelectedDate.getDate()).padStart(2, '0');
            const dateStr = `${year}-${month}-${day}`;
            
            const startFull = dateStr + ' ' + $('#start_time').val();
            const endFull = dateStr + ' ' + $('#end_time').val();

            const formData = {
                _token: '{{ csrf_token() }}',
                patient_id: $('#patient_select').val(),
                consultorio_id: $('#consultorio_select').val(),
                status: $('#appointmentForm select[name="status"]').val(),
                start_time: startFull,
                end_time: endFull,
                notes: $('#appointmentForm textarea[name="reason"]').val()
            };

            const url = isUpdate 
                ? `{{ url('admin/appointments') }}/${appointmentId}`
                : '{{ route("appointments.store") }}';
            
            const method = isUpdate ? 'PUT' : 'POST';

            if (!formData.patient_id || !formData.consultorio_id) {
                Swal.fire('Error', 'Debe seleccionar un paciente y un consultorio', 'error');
                return;
            }

            $.ajax({
                url: url,
                method: method,
                data: formData,
                success: function(response) {
                    if (response.success) {
                        calendar.refetchEvents();
                        closeModal();
                        Swal.fire({
                            icon: 'success',
                            title: isUpdate ? 'Cita Actualizada' : 'Cita Agendada',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        if(!isUpdate) $('#appointmentForm')[0].reset();
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'Error al procesar cita';
                    Swal.fire('Error', error, 'error');
                }
            });
        });

        // Eliminar Cita
        $('#btnDeleteAppointment').on('click', function() {
            const appointmentId = $('#appointment_id').val();
            if(!appointmentId) return;

            Swal.fire({
                title: '¿Eliminar cita?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff4d4f',
                cancelButtonColor: '#6d4aff',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('admin/appointments') }}/${appointmentId}`,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            calendar.refetchEvents();
                            closeModal();
                            Swal.fire('Eliminado', 'La cita ha sido eliminada.', 'success');
                        }
                    });
                }
            });
        });

        function updateEventTime(event) {
            const startStr = event.start.getFullYear() + '-' + 
                           String(event.start.getMonth() + 1).padStart(2, '0') + '-' + 
                           String(event.start.getDate()).padStart(2, '0') + ' ' + 
                           event.start.toTimeString().split(' ')[0];
            
            const endStr = event.end.getFullYear() + '-' + 
                         String(event.end.getMonth() + 1).padStart(2, '0') + '-' + 
                         String(event.end.getDate()).padStart(2, '0') + ' ' + 
                         event.end.toTimeString().split(' ')[0];

            $.ajax({
                url: `{{ url('admin/appointments') }}/${event.id}`,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    start_time: startStr,
                    end_time: endStr
                },
                success: function() {
                    console.log('Cita movida exitosamente');
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo mover la cita', 'error');
                    calendar.refetchEvents();
                }
            });
        }

        $('#consultorio-filter').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const interval = selectedOption.data('interval') || 30;
            const duration = `00:${interval.toString().padStart(2, '0')}:00`;
            
            calendar.setOption('slotDuration', duration);
            calendar.setOption('slotLabelInterval', duration);
            calendar.refetchEvents();
        });

        // Manejo de Filtros de Estado
        $('.status-chip').on('click', function() {
            $('.status-chip').removeClass('active');
            $(this).addClass('active');
            calendar.refetchEvents();
        });

        // Initialize Select2 for the filter (ONCE)
        $('.select2-basic:not(#eventModal *)').select2();
    });
</script>
@endsection
