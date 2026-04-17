import React, { useState, useRef, useEffect, useCallback } from 'react';
import axios from 'axios';
import { App } from '@capacitor/app';
import useMicrophonePermissions from '../hooks/useMicrophonePermissions.jsx';

// El componente principal, sin necesidad de parámetros de Picovoice Key
const DocIaMicButton = () => {
    const { isGranted, requestPermissions } = useMicrophonePermissions();
    const [isRecording, setIsRecording] = useState(false);
    const [recordingTime, setRecordingTime] = useState(0);
    const [wakeWordDetected, setWakeWordDetected] = useState(false);
    const [isListening, setIsListening] = useState(false);
    const [lastResponse, setLastResponse] = useState(null);
    const [isProcessing, setIsProcessing] = useState(false);
    const [speechError, setSpeechError] = useState(null);
    const [debugTranscript, setDebugTranscript] = useState("");
    
    const mediaRecorderRef = useRef(null); // Ya no se usa para enviar backend, solo cosmético si se quiere
    const timerRef = useRef(null);
    const autoStopRef = useRef(null);
    const recognitionRef = useRef(null);
    const ignoreRestartRef = useRef(false);
    
    // Referencias mutables para evitar re-compilaciones del hook useEffect (Closure Traps)
    const isRecordingRef = useRef(false);
    const isTriggeringRef = useRef(false);
    const commandTextRef = useRef(""); // Guardará la transcripción local de la orden

    // Initial setup: request microphone immediately
    useEffect(() => {
        requestPermissions();
    }, [requestPermissions]);

    // Configurar API de Reconocimiento de Voz Nativa del Navegador
    const setupSpeechRecognition = useCallback(() => {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
            setSpeechError("Tu navegador no soporta detección de voz nativa.");
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'es-ES'; // Aseguramos detección en Español

        recognition.onstart = () => {
            console.log("[WEB SPEECH START] El motor de reconocimiento nativo ha arrancado a escuchar.");
            setIsListening(true);
            setSpeechError(null);
            ignoreRestartRef.current = false;
        };

        recognition.onresult = (event) => {
            let finalTranscript = '';
            for (let i = event.resultIndex; i < event.results.length; ++i) {
                finalTranscript += event.results[i][0].transcript.toLowerCase();
            }

            // Si estamos en modo de grabación (escuchando el comando tras la palabra clave)
            if (isRecordingRef.current) {
                console.log("[NATIVE STT] Capturando orden médica: ", finalTranscript);
                commandTextRef.current = finalTranscript; // Almacenamos el texto de la orden
                setDebugTranscript("Oído: " + commandTextRef.current);
                
                // EXTENSIÓN DE TIEMPO INTELIGENTE: Si el usuario sigue hablando, le damos más tiempo antes de cortar la orden.
                // Cancelamos el corte inminente y le damos 5 segundos de gracia adicionales para respirar y pensar.
                if (autoStopRef.current) clearTimeout(autoStopRef.current);
                autoStopRef.current = setTimeout(() => stopRecordingMode(), 5000);
                
                return;
            }

            if (isTriggeringRef.current) return;
            
            console.log("[SPEECH RECOGNIZED] Capturado: ", finalTranscript);
            setDebugTranscript(finalTranscript); 
            
            // Analizar fonéticamente (Palabras de prueba)
            const wakeWords = ["asistente", "oye doctor", "hola asistente", "despierta"];
            const detected = wakeWords.some(word => finalTranscript.includes(word));

            if (detected) {
                console.log("[WAKE WORD] Detección confirmada, bloqueando reingresos.");
                isTriggeringRef.current = true;
                setDebugTranscript("¡Dime!");
                triggerWakeWord();
                ignoreRestartRef.current = true;
                return;
            }
        };

        recognition.onerror = (event) => {
            console.log("[WEB SPEECH ERROR]:", event.error);
            if (event.error === 'not-allowed') {
                ignoreRestartRef.current = true;
                setSpeechError('Permisos de micrófono denegados por el navegador.');
            }
        };

        recognition.onend = () => {
            console.log("[WEB SPEECH END] El motor se ha detenido.");
            setIsListening(false);
            // Auto re-arrancar para lectura continua a menos que estemos grabando el audio real, o haya un error de permisos
            if (!isRecordingRef.current && !ignoreRestartRef.current && !isTriggeringRef.current) {
                try {
                    console.log("[WEB SPEECH RESTART] Reiniciando escucha continua...");
                    recognition.start();
                } catch(e) {
                    console.log("[WEB SPEECH RESTART ERROR]", e);
                }
            }
        };

        recognitionRef.current = recognition;
        
        if (isGranted) {
            try {
                console.log("[WEB SPEECH INIT] isGranted es TRUE, arrancando...");
                recognition.start();
            } catch(e) {
                console.log("[WEB SPEECH INIT ERROR]", e);
            }
        } else {
            console.log("[WEB SPEECH INIT] isGranted es FALSE, no se arranca.");
        }
    }, [isGranted]); // Quitamos isRecording de las dependencias MUY IMPORTANTE

    // Arrancar el "listening" automático en cuanto tengamos permiso
    useEffect(() => {
        if (isGranted) {
            setupSpeechRecognition();
        }
        return () => {
            if (recognitionRef.current) {
                ignoreRestartRef.current = true;
                recognitionRef.current.stop();
            }
        };
    }, [isGranted, setupSpeechRecognition]);

    const triggerWakeWord = () => {
        console.log("[WAKE WORD] Palabra clave detectada. Escuchando instrucción...");
        setWakeWordDetected(true);
        setTimeout(() => setWakeWordDetected(false), 2000);
        commandTextRef.current = ""; // Limpiar comandos previos
        startRecordingMode();
        // Inicializamos con 7 segundos iniciales para permitir arrancar a hablar, pero esto se retroalimentará (reseteará) cada vez que hable.
        autoStopRef.current = setTimeout(() => stopRecordingMode(), 7000); 
    };

    const startRecordingMode = useCallback(() => {
        if (isRecordingRef.current) return;
        
        console.log("[RECORDER] Modo asimilación de orden activado. Transcribiendo localmente...");
        isRecordingRef.current = true;
        setIsRecording(true);
        setLastResponse(null);
        timerRef.current = setInterval(() => setRecordingTime(prev => prev + 1), 1000);
    }, []);

    const stopRecordingMode = useCallback(async () => {
        if (isRecordingRef.current) {
            console.log("[RECORDER] Tiempo finalizado. Procesando texto capturado...");
            isRecordingRef.current = false;
            setIsRecording(false);
            clearInterval(timerRef.current);
            setRecordingTime(0);
            if (autoStopRef.current) clearTimeout(autoStopRef.current);
            
            setDebugTranscript("");
            
            // Reiniciar el listener permitiendo nuevas activaciones
            isTriggeringRef.current = false;
            ignoreRestartRef.current = false;

            if (commandTextRef.current.trim().length > 3) {
                await sendTextToBackend(commandTextRef.current);
            } else {
                console.log("[VALIDATION] El comando de voz fue muy corto o no se entendió, se ignoró.");
            }
        }
    }, []);

    const sendTextToBackend = async (transcribedText) => {
        console.log("[BACKEND] Enviando texto directamente a IA local/remota:", transcribedText);
        setIsProcessing(true);
        try { 
            const res = await axios.post('/api/doc-ia/voice', { text: transcribedText });
            console.log("[BACKEND RESPONSE] IA Respondió exitosamente:", res.data);
            setLastResponse(res.data);
            
            // Reaccionar a intenciones de la IA (Ej: Redireccionar al usuario a editar un paciente)
            if (res.data.action === 'redirect' && res.data.url) {
                console.log("[INTENT] La IA solicitó una redirección a:", res.data.url);
                setTimeout(() => {
                    window.location.href = res.data.url;
                }, 2000); // 2 segundos de pausa para dejarle ver el mensaje de éxito
            }
        } catch (e) {
            console.error("[BACKEND ERROR] Falla en motor IA:", e);
            setLastResponse({ error: 'No se pudo procesar la orden. Revisa el backend de LM Studio/Ollama.' });
        } finally {
            setIsProcessing(false);
        }
    };

    return (
        <div className="flex flex-col items-center relative">
            {/* Modal de Respuesta IA */}
            {lastResponse && (
                <div className="absolute bottom-20 right-0 w-80 bg-white rounded-3xl shadow-2xl border border-indigo-100 p-6 animate-in slide-in-from-bottom-5 duration-300 z-[70]">
                    <div className="flex justify-between items-start mb-4">
                        <div className="flex items-center gap-2">
                            <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white text-xs">
                                <i className="fa-solid fa-brain"></i>
                            </div>
                            <span className="text-xs font-black uppercase tracking-tighter text-primary">DocIA Responde</span>
                        </div>
                        <button onClick={() => setLastResponse(null)} className="text-gray-400 hover:text-red-500">
                            <i className="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    {lastResponse.error ? (
                        <p className="text-xs text-red-500 font-bold">{lastResponse.error}</p>
                    ) : (
                        <div className="space-y-3">
                            <p className="text-[10px] text-gray-400 font-bold uppercase italic">" {lastResponse.transcription} "</p>
                            <div className="text-sm text-gray-700 leading-relaxed max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                {lastResponse.response}
                            </div>
                        </div>
                    )}
                </div>
            )}

            {isProcessing && (
                <div className="absolute bottom-20 right-0 bg-indigo-600 text-white px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest animate-pulse flex items-center gap-2">
                    <i className="fa-solid fa-circle-notch animate-spin"></i>
                    IA Analizando...
                </div>
            )}

            {/* Error Web Speech API */}
            {speechError && !isRecording && (
                <div className="absolute right-full top-1/2 -translate-y-1/2 mr-4 w-max bg-red-50 text-red-500 px-4 py-2 rounded-2xl text-[9px] font-black uppercase tracking-widest shadow-2xl z-50 border border-red-100 opacity-80 hover:opacity-100 transition-opacity whitespace-pre-line text-right">
                    <i className="fa-solid fa-microphone-slash mr-1"></i> 
                    Micrófono inactivo:<br/> {speechError}
                </div>
            )}

            {/* Debugging de lo que el navegador escucha */}
            {debugTranscript && !isRecording && !speechError && (
                <div className="absolute bottom-full mb-3 right-0 bg-gray-900/80 backdrop-blur-sm text-white px-3 py-1.5 rounded-xl text-[10px] font-mono shadow-lg border border-gray-700/50 max-w-[200px] truncate">
                    🎤 {debugTranscript}
                </div>
            )}

            <div className="relative">
                <button
                    onMouseDown={startRecordingMode}
                    onMouseUp={stopRecordingMode}
                    className={`relative z-10 w-14 h-14 rounded-2xl flex flex-col items-center justify-center transition-all shadow-xl active:scale-90 cursor-pointer ${
                        !isGranted ? 'bg-gray-100 text-gray-400 border-2 border-dashed border-gray-300' :
                        isRecording ? 'bg-red-500 text-white shadow-red-500/30' : 
                        isListening ? 'bg-green-500 text-white shadow-green-500/30 hover:bg-green-600' :
                        'bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-600/20'
                    }`}
                >
                    {wakeWordDetected ? (
                        <i className="fa-solid fa-check text-xl animate-bounce"></i>
                    ) : (
                        <i className={`fa-solid ${!isGranted ? 'fa-microphone-slash' : isRecording ? 'fa-microphone-lines' : 'fa-brain'} text-xl`}></i>
                    )}
                </button>
            </div>
        </div>
    );
};

export default DocIaMicButton;
