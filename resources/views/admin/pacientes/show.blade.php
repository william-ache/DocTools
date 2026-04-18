@extends('layouts.admin')

@section('noPadding', 'true')
@section('sidebarWidth', 'w-[340px]')

@section('sidebar')
<!-- SIDEBAR IZQUIERDA: Perfil Moderno (Imagen 2) -->
<div class="h-full flex flex-col bg-white overflow-hidden">
    <!-- Header Perfil Compacto -->
    <div class="p-5 pb-4 border-b border-surface-container-low/50">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center text-lg font-black shrink-0 shadow-md">
                {{ strtoupper(substr($paciente->name, 0, 1)) }}{{ strtoupper(substr(strrchr($paciente->name, " "), 1, 1)) ?: '' }}
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-sm font-black text-primary leading-tight tracking-tight">{{ $paciente->name }}</h2>
            </div>
            <button class="w-8 h-8 rounded-lg bg-surface-container-low text-outline hover:text-primary transition-all flex items-center justify-center shrink-0">
                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
            </button>
        </div>

        <div class="grid grid-cols-2 gap-y-4 gap-x-3">
            <div>
                <p class="text-[8px] font-black text-outline uppercase tracking-widest leading-none mb-1.5 opacity-60">Cédula</p>
                <p class="text-[11px] font-extrabold text-primary">{{ $paciente->id_number ?: 'N/A' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-black text-outline uppercase tracking-widest leading-none mb-1.5 opacity-60">Nacimiento</p>
                <p class="text-[11px] font-extrabold text-primary">{{ $paciente->birth_date ? \Carbon\Carbon::parse($paciente->birth_date)->format('d/m/Y') : 'N/D' }}</p>
            </div>
            <div class="col-span-2 relative group">
                <p class="text-[8px] font-black text-outline uppercase tracking-widest leading-none mb-2 opacity-60">Representante</p>
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold text-outline italic">Sin asignar</p>
                    <button class="text-primary text-[9px] font-black uppercase tracking-widest hover:underline">+ Asignar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Secciones Colapsables -->
    <div class="flex-1 px-3 space-y-0.5 mt-4 overflow-y-auto custom-scrollbar">
        @foreach([
            ['label' => 'Últimos Diagnósticos', 'icon' => 'fa-stethoscope'],
            ['label' => 'Último Tratamiento', 'icon' => 'fa-pills', 'extra' => '<span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>'],
            ['label' => 'Alergias', 'icon' => 'fa-hand-dots'],
            ['label' => 'Antecedentes', 'icon' => 'fa-clipboard-user'],
            ['label' => 'Notas', 'icon' => 'fa-note-sticky']
        ] as $section)
        <div class="group border-b border-surface-container-low/30 last:border-none">
            <button class="w-full px-4 py-3 flex items-center justify-between text-on-surface-variant hover:bg-primary/5 transition-all rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="w-5 flex items-center justify-center text-primary/40 group-hover:text-primary transition-colors">
                        <i class="fa-solid {{ $section['icon'] }} text-sm"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-[0.1em] text-outline group-hover:text-primary transition-colors">{{ $section['label'] }}</span>
                    {!! $section['extra'] ?? '' !!}
                </div>
                <i class="fa-solid fa-chevron-right text-[8px] opacity-20 group-hover:opacity-100 transition-all"></i>
            </button>
        </div>
        @endforeach
    </div>

    <!-- Navegación Base -->
    <div class="p-4 bg-surface-container-low/20 border-t border-surface-container-low">
        <a href="{{ route('pacientes.index') }}" class="w-full py-4 px-4 bg-white border border-surface-container text-primary text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-surface-container-low transition-all flex items-center justify-center gap-3 shadow-sm">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver a la lista
        </a>
        <div class="mt-4 flex flex-col items-center gap-2">
            <button class="text-[9px] font-black text-error/30 hover:text-error uppercase tracking-widest transition-colors">Eliminar paciente</button>
            <div class="flex items-center gap-2 opacity-15">
                <i class="fa-solid fa-microchip text-[12px]"></i>
                <span class="text-[9px] font-black uppercase tracking-widest leading-none">Docguía clinical Engine</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('header')
<header class="h-16 md:h-20 bg-white border-b border-surface-container flex items-center justify-between px-8 shrink-0">
    <div class="flex items-center gap-6">
        <div>
            <h1 class="text-lg font-black text-primary tracking-tighter leading-tight">Editar Consulta</h1>
            <p class="text-[10px] text-outline font-bold uppercase tracking-widest">Esta consulta se guarda automáticamente</p>
        </div>
    </div>
    <div class="flex items-center gap-4">
        <button class="p-3 rounded-xl bg-surface-container-low text-outline hover:text-primary transition-all">
            <i class="fa-solid fa-expand"></i>
        </button>
        <button class="px-6 py-3.5 rounded-xl bg-primary/10 text-primary font-black text-[11px] uppercase tracking-widest hover:bg-primary/20 transition-all flex items-center gap-3">
            Imprimir
            <i class="fa-solid fa-print"></i>
        </button>
    </div>
</header>
@endsection

@section('content')
<!-- Contenedor Principal Full Screen -->
<div class="flex h-[calc(100vh-80px)] overflow-hidden bg-surface-container-low/10">
    
    <!-- ÁREA DE EDICIÓN CENTRAL -->
    <div class="flex-1 flex flex-col p-8 overflow-y-auto custom-scrollbar">
        <div class="max-w-5xl mx-auto w-full space-y-8">
            
            <!-- Contexto Consulta -->
            <div class="space-y-6">
                <h3 class="text-sm font-black text-primary uppercase tracking-widest">Notas de la consulta</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative group">
                        <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-primary opacity-40"></i>
                        <select class="w-full pl-12 pr-10 py-4 bg-white rounded-2xl border border-surface-container font-extrabold text-sm text-primary appearance-none focus:ring-4 focus:ring-primary/10 transition-all">
                            <option>Centro Médico Maracay</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-primary opacity-30"></i>
                    </div>
                    <div class="relative group">
                        <i class="fa-solid fa-file-invoice absolute left-4 top-1/2 -translate-y-1/2 text-primary opacity-40"></i>
                        <select id="template-selector" class="w-full pl-12 pr-10 py-4 bg-white rounded-2xl border border-surface-container font-extrabold text-sm text-primary appearance-none focus:ring-4 focus:ring-primary/10 transition-all">
                            <option value="">Ninguna plantilla</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" data-content="{{ $template->content }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-primary opacity-30"></i>
                    </div>
                </div>
                <div class="relative">
                    <input type="text" placeholder="Buscar diagnóstico CIE-10..." class="w-full px-6 py-4 bg-white border border-surface-container rounded-2xl font-bold text-sm text-primary placeholder:text-outline/40 focus:ring-4 focus:ring-primary/10 transition-all shadow-sm">
                </div>
            </div>

            <!-- Tabs y Editor Moderno -->
            <div class="space-y-6">
                <div class="flex items-center gap-8 border-b border-surface-container">
                    <button class="pb-4 text-[11px] font-black uppercase tracking-widest text-primary border-b-2 border-primary">Notas de consulta</button>
                    <button class="pb-4 text-[11px] font-black uppercase tracking-widest text-outline hover:text-primary transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        Crear documento
                    </button>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-surface-container shadow-sm overflow-hidden flex flex-col min-h-[500px] relative group">
                    <div id="editor" class="flex-1 p-8 text-primary font-medium text-lg leading-relaxed"></div>
                    
                    <!-- Botón Flotante Dictado (Imagen 2 Style) -->
                    <div class="absolute bottom-6 right-6 z-50">
                        <button id="main-ia-dictate-btn" class="bg-primary text-white px-8 py-4 rounded-full font-black text-xs uppercase tracking-widest shadow-2xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3">
                            <i class="fa-solid fa-microphone text-sm"></i>
                            Dictado Inteligente
                        </button>
                    </div>

                    <!-- Botón IA Quick Actions -->
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="bg-[#f3e8ff] text-[#9333ea] px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center gap-2 hover:bg-[#e9d5ff] transition-all">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            Corregir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SIDEBAR DERECHA: Acciones (Imagen 2 Style) -->
    <div class="w-[300px] bg-white border-l border-surface-container p-8 h-full overflow-y-auto custom-scrollbar">
        <h3 class="text-[11px] font-black text-primary uppercase tracking-widest mb-8">Acciones Rápidas</h3>
        
        <div class="space-y-3">
            @foreach([
                ['label' => 'Crear Recipe', 'icon' => 'fa-link'],
                ['label' => 'Solicitar Paraclínicos', 'icon' => 'fa-microscope'],
                ['label' => 'Programar Seguimiento', 'icon' => 'fa-bell'],
                ['label' => 'Agendar Cita', 'icon' => 'fa-calendar-days']
            ] as $action)
            <button class="w-full p-4 rounded-xl bg-white border border-surface-container hover:border-primary/30 hover:bg-primary/5 transition-all group flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-5 flex items-center justify-center text-outline group-hover:text-primary transition-colors">
                        <i class="fa-solid {{ $action['icon'] }} text-xs"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-outline group-hover:text-primary transition-colors">{{ $action['label'] }}</span>
                </div>
                <i class="fa-solid fa-plus text-[10px] text-outline group-hover:text-primary"></i>
            </button>
            @endforeach
        </div>
    </div>
</div>

<!-- SCRIPTS & STYLES -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<style>
    .ql-toolbar.ql-snow { border: none !important; padding: 12px 24px !important; border-bottom: 1px solid #f1f5f9 !important; background: #fdfdfd !important; }
    .ql-container.ql-snow { border: none !important; font-family: inherit !important; min-height: 400px; }
    .ql-editor { font-size: 1.25rem !important; line-height: 1.8 !important; color: #001d3d !important; padding: 30px !important; font-weight: 500 !important; }
    .ql-editor.ql-blank::before { font-style: normal !important; color: #94a3b8 !important; left: 30px !important; font-weight: 500 !important; }
</style>

<script>
    $(document).ready(function() {
        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Escribe los hallazgos que has hecho de tu paciente, por ejemplo: síntomas, exploración física, diagnóstico y plan de tratamiento...',
            modules: { toolbar: [ ['bold', 'italic', 'underline'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['clean'] ] }
        });

        $('#template-selector').on('change', function() {
            const content = $(this).find(':selected').data('content');
            if (content) quill.root.innerHTML = content;
        });

        // Speech Logic
        let recognition;
        let isRecording = false;
        let silenceTimer;

        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRec();
            recognition.continuous = true;
            recognition.interimResults = true;
            recognition.lang = 'es-ES';

            recognition.onresult = (event) => {
                for (let i = event.resultIndex; i < event.results.length; ++i) {
                    if (event.results[i].isFinal) {
                        const text = event.results[i][0].transcript;
                        const range = quill.getSelection(true);
                        quill.insertText(range.index, (range.index > 0 ? ' ' : '') + text);
                        quill.setSelection(range.index + text.length + 1);
                    }
                }
                if (silenceTimer) clearTimeout(silenceTimer);
                silenceTimer = setTimeout(() => { if (isRecording) stopRecording(); }, 5000);
            };

            recognition.onerror = (e) => { if (e.error === 'aborted' && isRecording) setTimeout(() => recognition.start(), 600); };
            recognition.onend = () => { if (isRecording) setTimeout(() => recognition.start(), 600); else stopRecording(); };
        }

        $('#main-ia-dictate-btn').on('click', function() { !isRecording ? startRecording() : stopRecording(); });

        function startRecording() {
            if (!recognition) return;
            isRecording = true;
            window.isModalMicActive = true;
            document.documentElement.dataset.modalMicActive = 'true';
            window.dispatchEvent(new CustomEvent('modalMicStateChanged', { detail: { active: true } }));
            $('#main-ia-dictate-btn').addClass('bg-red-500 animate-pulse').html('<i class="fa-solid fa-stop"></i> Detener');
            try { recognition.start(); quill.focus(); } catch(e) {}
        }

        function stopRecording() {
            isRecording = false;
            window.isModalMicActive = false;
            document.documentElement.dataset.modalMicActive = 'false';
            window.dispatchEvent(new CustomEvent('modalMicStateChanged', { detail: { active: false } }));
            if (silenceTimer) clearTimeout(silenceTimer);
            if (recognition) try { recognition.stop(); } catch(e) {}
            $('#main-ia-dictate-btn').removeClass('bg-red-500 animate-pulse').html('<i class="fa-solid fa-microphone"></i> Dictado Inteligente');
        }
    });
</script>
@endsection
