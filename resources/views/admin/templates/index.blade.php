@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-20">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4 fade-element">
        <div>
            <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Plantillas Médicas</h1>
            <p class="text-on-surface-variant font-medium text-lg">Gestiona tus formatos y agiliza tus historias clínicas.</p>
        </div>
        <button onclick="openTemplateModal()" class="hero-gradient text-white px-8 py-4 rounded-2xl font-black text-sm shadow-xl hover:scale-[1.02] transition-all flex items-center gap-3">
            <i class="fa-solid fa-plus"></i>
            Nueva Plantilla
        </button>
    </div>

    <!-- Grid de Plantillas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in">
        @forelse($templates as $template)
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-surface-container flex flex-col transition-all relative {{ !$template->is_active ? 'opacity-50' : '' }}">
            <!-- Status Toggle -->
            <div class="absolute top-6 right-6">
                <button onclick="toggleTemplateStatus({{ $template->id }})" 
                    class="w-10 h-6 rounded-full transition-all relative flex items-center px-1" 
                    style="background-color: {{ $template->is_active ? ($template->color ?? 'var(--primary)') : '#d1d5db' }}">
                    <div class="w-4 h-4 rounded-full bg-white shadow-sm transition-all {{ $template->is_active ? 'translate-x-4' : 'translate-x-0' }}"></div>
                </button>
            </div>

            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all {{ !$template->is_active ? 'grayscale' : '' }}" 
                    style="background-color: {{ ($template->color ?? '#4f46e5') . '1A' }}; color: {{ $template->color ?? '#4f46e5' }}">
                    <i class="fa-solid {{ $template->icon ?? 'fa-file-lines' }} text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-black truncate pr-10" style="color: {{ $template->is_active ? ($template->color ?? 'var(--primary)') : '#9ca3af' }}">
                        {{ $template->name }}
                    </h3>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-outline/60">
                        {{ $template->is_active ? 'Activa' : 'Desactivada' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button onclick="prepareEdit({{ $template->id }})" 
                    id="edit-btn-{{ $template->id }}"
                    data-name="{{ $template->name }}"
                    data-content="{{ $template->content }}"
                    data-icon="{{ $template->icon }}"
                    data-color="{{ $template->color }}"
                    class="flex items-center justify-center gap-2 py-4 rounded-2xl font-black text-xs hover:opacity-80 transition-all"
                    style="background-color: {{ ($template->color ?? '#4f46e5') . '1A' }}; color: {{ $template->color ?? '#4f46e5' }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Editar
                </button>
                
                <form action="{{ route('templates.destroy', $template) }}" method="POST" class="confirm-delete m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full h-full flex items-center justify-center gap-2 py-4 rounded-2xl bg-error/10 text-error font-black text-xs hover:bg-error/20 transition-all">
                        <i class="fa-solid fa-trash-can"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-20 rounded-[3rem] border border-dashed border-surface-container-highest flex flex-col items-center text-center">
            <div class="w-24 h-24 rounded-full bg-surface-container-low flex items-center justify-center text-outline mb-6">
                <i class="fa-solid fa-file-circle-plus text-4xl"></i>
            </div>
            <h3 class="text-xl font-black text-primary mb-2">No hay plantillas registradas</h3>
            <p class="text-on-surface-variant max-w-sm mb-8">Comienza creando tu primera plantilla para ahorrar tiempo en tus consultas.</p>
            <button onclick="openTemplateModal()" class="text-primary font-black hover:underline uppercase tracking-widest text-xs flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                Crear Mi Primera Plantilla
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal de Plantilla -->
<div id="template-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col animate-in fade-in zoom-in duration-300">
        <div class="px-8 py-6 border-b border-surface-container flex items-center justify-between">
            <h3 id="modal-title" class="text-xl font-black text-primary tracking-tighter">Crear Nueva Plantilla</h3>
            <button onclick="closeTemplateModal()" class="text-outline hover:text-primary transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="template-form" action="{{ route('templates.store') }}" method="POST" class="flex flex-col h-[70vh]">
            @csrf
            <input type="hidden" id="form-method" name="_method" value="POST">
            
            <div class="p-8 space-y-6 flex-1 overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-outline uppercase tracking-widest mb-3">Nombre de la Plantilla <span class="text-error">*</span></label>
                        <input type="text" name="name" id="template-name" required placeholder="Ej. Historia Clínica General" 
                            class="w-full px-6 py-4 bg-surface-container-low rounded-2xl border-none font-bold text-primary placeholder:text-outline/50 focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-outline uppercase tracking-widest mb-3">Color Representativo</label>
                        <div class="flex items-center gap-4 bg-surface-container-low rounded-2xl px-6 py-3 border-none shadow-inner">
                            <input type="color" name="color" id="template-color" value="#4f46e5" class="w-10 h-10 rounded-xl border-none p-0 cursor-pointer overflow-hidden shadow-sm">
                            <span class="text-[11px] font-bold text-outline uppercase tracking-widest">Elegir color</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-outline uppercase tracking-widest mb-3">Selecciona un Ícono</label>
                    <div class="grid grid-cols-4 sm:grid-cols-8 gap-3">
                        @foreach([
                            'fa-file-lines', 'fa-stethoscope', 'fa-heart-pulse', 'fa-user-doctor', 
                            'fa-prescription', 'fa-clipboard-list', 'fa-hospital-user', 'fa-id-card'
                        ] as $iconClass)
                        <label class="cursor-pointer group">
                            <input type="radio" name="icon" value="{{ $iconClass }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                            <div class="flex items-center justify-center h-12 rounded-xl bg-surface-container-low border-2 border-transparent transition-all peer-checked:border-primary peer-checked:bg-white peer-checked:shadow-sm">
                                <i class="fa-solid {{ $iconClass }} text-xl text-outline group-hover:text-primary transition-colors"></i>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative flex flex-col h-[400px]">
                    <label class="block text-[10px] font-black text-outline uppercase tracking-widest mb-3">Contenido de la Plantilla <span class="text-error">*</span></label>
                    <div id="quill-editor" class="bg-surface-container-low rounded-2xl border-none overflow-hidden flex-1 !h-auto"></div>
                    <input type="hidden" name="content" id="template-content">
                    
                    <!-- Botón de IA Dictado -->
                    <button type="button" onclick="startDictation()" id="ia-dictate-btn" 
                        class="absolute bottom-4 right-4 w-12 h-12 rounded-full hero-gradient text-white shadow-xl hover:scale-110 transition-all flex items-center justify-center z-10 group" 
                        title="Dictar con IA">
                        <i class="fa-solid fa-microphone text-lg group-active:scale-90 transition-transform"></i>
                    </button>
                </div>
            </div>

            <div class="px-8 py-6 bg-surface-container-low flex items-center justify-end gap-3 border-t border-surface-container">
                <button type="button" onclick="closeTemplateModal()" class="px-8 py-4 rounded-xl font-bold text-sm text-outline hover:bg-surface-container-high transition-all">
                    Cancelar
                </button>
                <button type="submit" class="px-10 py-4 rounded-xl font-black text-sm text-white hero-gradient shadow-lg hover:scale-[1.02] transition-all">
                    <i class="fa-solid fa-save mr-2"></i>
                    <span id="submit-btn-text">Crear Plantilla</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Vista Previa -->
<div id="preview-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[110] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="px-8 py-6 border-b border-surface-container flex items-center justify-between">
            <h3 class="text-xl font-black text-primary tracking-tighter">Vista Previa</h3>
            <button onclick="closePreviewModal()" class="text-outline hover:text-primary transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-10 max-h-[60vh] overflow-y-auto custom-scrollbar prose prose-primary min-w-full" id="preview-content">
            <!-- Content -->
        </div>
        <div class="p-6 bg-surface-container-low flex justify-end">
            <button onclick="closePreviewModal()" class="px-8 py-3 rounded-xl font-bold bg-primary text-white hover:scale-[1.02] transition-all">Cerrar</button>
        </div>
    </div>
</div>

<!-- Quill Assets -->
<link href="{{ asset('assets/libs/quill/quill.snow.css') }}" rel="stylesheet">
<script src="{{ asset('assets/libs/quill/quill.min.js') }}"></script>

<style>
    .ql-toolbar.ql-snow {
        border: none !important;
        background: #f0f2f5;
        border-radius: 1.25rem 1.25rem 0 0;
        padding: 1rem !important;
    }
    .ql-container.ql-snow {
        border: none !important;
        background: #f7f9fb;
        border-radius: 0 0 1.25rem 1.25rem;
        font-family: 'Manrope', sans-serif !important;
        font-size: 1rem !important;
    }
    .ql-editor {
        padding: 1.5rem !important;
        min-height: 300px;
    }
    .ql-editor.ql-blank::before {
        color: rgba(66, 71, 82, 0.4) !important;
        font-style: normal !important;
        font-weight: 500 !important;
    }

    @keyframes mic-pulse {
        0% { box-shadow: 0 0 0 0 rgba(186, 26, 26, 0.7); transform: scale(1); }
        70% { box-shadow: 0 0 0 15px rgba(186, 26, 26, 0); transform: scale(1.05); }
        100% { box-shadow: 0 0 0 0 rgba(186, 26, 26, 0); transform: scale(1); }
    }
    .mic-active-blink {
        background-color: #ba1a1a !important;
        background-image: none !important;
        animation: mic-pulse 1.5s infinite cubic-bezier(0.66, 0, 0, 1);
    }
</style>

<script>
    let quill;

    $(document).ready(function() {
        quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Escribe el contenido de tu plantilla aquí...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        $('#template-form').on('submit', function() {
            $('#template-content').val(quill.root.innerHTML);
        });
    });

    function openTemplateModal() {
        $('#modal-title').text('Crear Nueva Plantilla');
        $('#submit-btn-text').text('Guardar Plantilla');
        $('#form-method').val('POST');
        $('#template-form').attr('action', '{{ route('templates.store') }}');
        $('#template-name').val('');
        $('#template-color').val('#4f46e5');
        $('input[name="icon"][value="fa-file-lines"]').prop('checked', true);
        quill.root.innerHTML = '';
        $('#template-modal').removeClass('hidden').addClass('flex');
    }

    function closeTemplateModal() {
        $('#template-modal').removeClass('flex').addClass('hidden');
    }

    function prepareEdit(id) {
        const btn = $(`#edit-btn-${id}`);
        const name = btn.data('name');
        const content = btn.data('content');
        const icon = btn.data('icon') || 'fa-file-lines';
        const color = btn.data('color') || '#4f46e5';
        editTemplate(id, name, content, icon, color);
    }

    function editTemplate(id, name, content, icon, color) {
        $('#template-name').val(name);
        $('#template-color').val(color);
        $(`input[name="icon"][value="${icon}"]`).prop('checked', true);
        $('#modal-title').text('Editar Plantilla');
        $('#submit-btn-text').text('Actualizar Plantilla');
        $('#form-method').val('PUT');
        $('#template-form').attr('action', `/admin/plantillas/${id}`);
        quill.root.innerHTML = content;
        $('#template-modal').removeClass('hidden').addClass('flex');
    }

    function previewTemplate(content) {
        $('#preview-content').html(content);
        $('#preview-modal').removeClass('hidden').addClass('flex');
    }

    function closePreviewModal() {
        $('#preview-modal').removeClass('flex').addClass('hidden');
    }

    function toggleTemplateStatus(id) {
        $.post(`/admin/plantillas/${id}/toggle-status`, {
            _token: '{{ csrf_token() }}'
        }).done(function() {
            location.reload();
        });
    }

    let recognition;
    let isRecording = false;
    let silenceTimer;
    window.isModalMicActive = false;

    function startDictation() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            Swal.fire({
                title: 'Navegador no compatible',
                text: 'El dictado por voz requiere Google Chrome o Microsoft Edge.',
                icon: 'error'
            });
            return;
        }

        // ELIMINAR CUALQUIER CONFLICTO: Marcamos como activo globalmente de inmediato
        window.isModalMicActive = true;
        document.documentElement.dataset.modalMicActive = 'true';
        window.dispatchEvent(new CustomEvent('modalMicStateChanged', { detail: { active: true } }));

        const btn = $('#ia-dictate-btn');
        const icon = btn.find('i');
        
        if (isRecording) {
            if (recognition) recognition.stop();
            return;
        }

        // Feedback naranja inmediato
        btn.css({
            'background-color': '#ff9800',
            'background-image': 'none'
        }).addClass('animate-pulse');

        try {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'es-ES';
            recognition.interimResults = true; // Habilitado para ser más responsivo
            recognition.continuous = true;

            let finalTranscript = '';

            recognition.onstart = () => {
                console.log("[DICTATION] Recognition started successfully.");
                isRecording = true;
                // Forzar visualmente el cambio de color y estado
                btn.removeClass('animate-pulse').css({
                    'background-color': '#ba1a1a',
                    'background-image': 'none'
                }).addClass('mic-active-blink');
                icon.removeClass('fa-microphone').addClass('fa-stop');
                
                resetSilenceTimer();
            };

            recognition.onresult = (event) => {
                resetSilenceTimer();
                let interimTranscript = '';
                
                for (let i = event.resultIndex; i < event.results.length; ++i) {
                    const transcript = event.results[i][0].transcript;
                    if (event.results[i].isFinal) {
                        finalTranscript += transcript + ' ';
                        
                        // Insertar texto final en Quill
                        // Insertar texto final en Quill con mayor fiabilidad
                        const cleanText = transcript.trim();
                        if (cleanText.length > 0) {
                            const length = quill.getLength();
                            const insertIndex = length - 1; // Antes del newline final
                            
                            quill.insertText(insertIndex, (insertIndex > 0 ? ' ' : '') + cleanText);
                            quill.setSelection(quill.getLength(), 0);
                            quill.focus();
                        }
                    } else {
                        interimTranscript += transcript;
                    }
                }
                
                console.log('Interim:', interimTranscript);
            };

            recognition.onerror = (event) => {
                console.error('[DICTADO ERROR]:', event.error);
                if (event.error === 'aborted') {
                    console.log('El micro fue abortado por el sistema. Re-intentando...');
                    return; 
                }
                if (event.error === 'not-allowed') {
                    Swal.fire('Sin Permiso', 'Debes permitir el uso del micrófono para usar el dictado.', 'warning');
                }
                stopRecording();
            };

            recognition.onend = () => {
                console.log('[DICTADO END] Sesión finalizada.');
                
                // Si nosotros cerramos el micro (isRecording = false), limpiar visualmente
                if (!isRecording) {
                    stopRecording();
                    return;
                }

                // Si se cerró estando isRecording = true, fue un error externo (aborted)
                if (window.isModalMicActive) {
                    console.log('[DICTADO] Reinicio automático por interrupción externa...');
                    setTimeout(() => {
                        if (window.isModalMicActive) {
                            try { recognition.start(); } catch(e) { console.error(e); stopRecording(); }
                        }
                    }, 600);
                } else {
                    stopRecording();
                }
            };

            // Retardo prolongado para asegurar que el hardware esté libre y el micro global bloqueado
            setTimeout(() => {
                if (window.isModalMicActive) {
                    try {
                        console.log('[DICTADO STARTING] Intentando capturar hardware...');
                        recognition.start();
                    } catch (e) {
                        console.error("[DICTADO FATAL]:", e);
                        stopRecording();
                    }
                }
            }, 1000);

        } catch (e) {
            console.error('Error al iniciar reconocimiento:', e);
            stopRecording();
        }

        function resetSilenceTimer() {
            if (silenceTimer) clearTimeout(silenceTimer);
            silenceTimer = setTimeout(() => {
                console.log('Silencio detectado por 5 seg, deteniendo...');
                if (recognition) recognition.stop();
            }, 5000);
        }

        function stopRecording() {
            isRecording = false;
            window.isModalMicActive = false;
            document.documentElement.dataset.modalMicActive = 'false';
            window.dispatchEvent(new CustomEvent('modalMicStateChanged', { detail: { active: false } }));
            if (silenceTimer) clearTimeout(silenceTimer);
            btn.removeClass('mic-active-blink animate-pulse').removeAttr('style').addClass('hero-gradient');
            icon.removeClass('fa-stop').addClass('fa-microphone');
        }
    }
</script>
@endsection
