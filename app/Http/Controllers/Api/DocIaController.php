<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DocIaService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocIaController extends Controller
{
    protected DocIaService $docIaService;

    public function __construct(DocIaService $docIaService)
    {
        $this->docIaService = $docIaService;
    }

    /**
     * Procesa una grabación de voz, la transcribe y genera una respuesta médica o acción de sistema.
     */
    public function processVoice(Request $request)
    {
        // Verificar API Key o configuración base rápidamente
        if (!config('services.openai.key') && !env('OPENAI_API_KEY')) {
            return response()->json([
                'transcription' => '[Sistema]',
                'response' => '❌ **Error de Configuración**: No se encontró la `OPENAI_API_KEY` o URL de modelo local en el archivo .env.'
            ]);
        }

        try {
            $transcription = "";

            // Opción A: Frontend nativo envió texto directamente
            if ($request->has('text')) {
                $transcription = $request->input('text');
            } 
            // Opción B: Frontend antiguo/alterno mandó audio
            elseif ($request->hasFile('audio')) {
                $audio = $request->file('audio');
                
                // Asegurar que el directorio existe
                if (!Storage::disk('local')->exists('temp_audio')) {
                    Storage::disk('local')->makeDirectory('temp_audio');
                }

                $path = $audio->store('temp_audio', 'local');
                $fullPath = Storage::disk('local')->path($path);

                // 1. Transcribir con OpenAI Whisper
                $transcription = $this->transcribeAudio($fullPath);
                
                Storage::delete($path); // Limpiar audio

                if (!$transcription) {
                    return response()->json(['error' => 'La IA no pudo entender el audio o la API Key es inválida o está bloqueada por región.'], 500);
                }
            } else {
                return response()->json(['error' => 'No se recibió texto ni audio de la orden de voz.'], 400);
            }

            // 2. Procesar con DocIA Service LLM
            $aiData = $this->docIaService->askDocIa($transcription);

            return response()->json([
                'transcription' => $transcription,
                'response' => $aiData['content'] ?? 'Error desconocido',
                'action' => $aiData['action'] ?? null,
                'url' => $aiData['url'] ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error('DocIaController Error: ' . $e->getMessage());
            return response()->json(['error' => 'Error crítico en el motor DocIA: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Usa la API de Whisper para convertir audio en texto.
     */
    protected function transcribeAudio(string $filePath): ?string
    {
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');

        try {
            $response = Http::withToken($apiKey)
                ->attach('file', file_get_contents($filePath), basename($filePath))
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model' => 'whisper-1',
                    'language' => 'es',
                ]);

            if ($response->failed()) {
                Log::error('Whisper API Error: ' . $response->body());
                return null;
            }

            return $response->json('text');
        } catch (\Exception $e) {
            Log::error('Whisper Exception: ' . $e->getMessage());
            return null;
        }
    }
}
