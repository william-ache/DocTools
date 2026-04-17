<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\DocIaInteraction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DocIaService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY', 'local-key');
        $this->model = env('OPENAI_MODEL', 'gpt-4o');
        
        // Soporte Dinámico para Ollama / LM Studio u OpenAI Oficial
        $this->baseUrl = rtrim(env('OPENAI_BASE_URL', 'https://api.openai.com/v1'), '/');
    }

    /**
     * Procesa una consulta mediante la IA y ejecuta funciones si es necesario.
     */
    public function askDocIa(string $prompt, $patientId = null)
    {
        if (!$this->apiKey) {
            return ['content' => "❌ Error: No se ha configurado la OPENAI_API_KEY o URL Local en el archivo .env"];
        }

        $systemPrompt = "Eres 'DocIA', el asistente inteligente de Consultia. Tienes permiso para controlar el sistema.\n" .
                        "Cuando el médico pida crear un paciente, extrae ABSOLUTAMENTE TODOS los datos que escuches.\n" .
                        "IMPORTANTE: Como eres el núcleo de la IA, debes responder OBLIGATORIAMENTE con este bloque de texto exacto cuando vayas a crear un paciente (usa 'null' si un dato no lo mencionó):\n\n" .
                        "[CREATE_PATIENT]\n" .
                        "Name: {nombre}\n" .
                        "IdNumber: {cedula o identificacion}\n" .
                        "Phone: {telefono}\n" .
                        "Email: {correo}\n" .
                        "Gender: {Masculino/Femenino/Otro}\n" .
                        "Address: {direccion}\n" .
                        "Notes: {antecedentes u observaciones medicas}\n" .
                        "[/CREATE_PATIENT]\n\n" .
                        "Si no te piden crear paciente, responde como un doctor colega en Markdown limpio.";

        try {
            $response = Http::withToken($this->apiKey)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    // Eliminamos el array de functions forzado para que el LLM siga nuestras reglas en texto puro
                ]);

            if ($response->failed()) {
                $errorMsg = $response->json('error.message') ?? 'Desconocido';
                return ['content' => "❌ Error de Modelos de Lenguaje (Cero Saldo o Bloqueado): " . $errorMsg];
            }

            $message = $response->json('choices.0.message');
            $content = $message['content'] ?? '';

            // --- ESCANER ESTRUCTURAL OMNIPOTENTE PARA IA LOCAL ---
            // Le dimos instrucciones exactas al modelo para que responda con un tag personalizado
            if (preg_match('/\[CREATE_PATIENT\](.*?)\[\/CREATE_PATIENT\]/is', $content, $match)) {
                $block = $match[1];
                $data = [];
                
                if (preg_match('/Name:\s*(.+)/i', $block, $m)) $data['name'] = trim($m[1]) !== 'null' ? trim($m[1]) : null;
                if (preg_match('/IdNumber:\s*(.+)/i', $block, $m)) $data['id_number'] = trim($m[1]) !== 'null' ? trim($m[1]) : null;
                if (preg_match('/Phone:\s*(.+)/i', $block, $m)) $data['phone'] = trim($m[1]) !== 'null' ? trim($m[1]) : null;
                if (preg_match('/Email:\s*(.+)/i', $block, $m)) $data['email'] = trim($m[1]) !== 'null' ? trim($m[1]) : null;
                if (preg_match('/Gender:\s*(.+)/i', $block, $m)) $data['gender'] = trim($m[1]) !== 'null' ? trim($m[1]) : null;
                if (preg_match('/Address:\s*(.+)/i', $block, $m)) $data['address'] = trim($m[1]) !== 'null' ? trim($m[1]) : null;
                if (preg_match('/Notes:\s*(.+)/i', $block, $m)) $data['antecedentes'] = trim($m[1]) !== 'null' ? trim($m[1]) : null;

                if (!empty($data['name'])) {
                    return $this->handleCreatePatient($data);
                }
            }

            return ['content' => $content ?: 'No recibí respuesta de la IA.'];

        } catch (\Exception $e) {
            Log::error('DocIaService Exception: ' . $e->getMessage());
            return ['content' => "Error al procesar la IA: " . $e->getMessage()];
        }
    }

    /**
     * Lógica para crear un paciente desde la IA.
     */
    protected function handleCreatePatient(array $args)
    {
        try {
            $patient = Patient::create([
                'id' => (string) Str::uuid(),
                'tenant_id' => auth()->user()->tenant_id ?? (string) Str::uuid(), // Fallback en caso null temporal
                'name' => $args['name'],
                'id_number' => $args['id_number'] ?? null,
                'phone' => $args['phone'] ?? null,
                'email' => $args['email'] ?? null,
                'gender' => $args['gender'] ?? 'No especificado',
                'address' => $args['address'] ?? null,
                'antecedentes' => $args['antecedentes'] ?? null,
            ]);

            return [
                'content' => "✅ Paciente **{$patient->name}** creado con todo éxito y control omnipotente.\nGenerando el expediente...",
                'action' => 'redirect',
                'url' => route('pacientes.index') . "?view_patient=" . $patient->id
            ];
        } catch (\Exception $e) {
            return ['content' => "❌ No pude crear el paciente masivo. Error: " . $e->getMessage()];
        }
    }
}
