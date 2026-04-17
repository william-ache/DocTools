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

        $systemPrompt = "Eres 'DocIA', la Mente Maestra con LIBERTAD TOTAL sobre el SaaS Médico Consultia.\n" .
                        "Tu objetivo es entender profundamente lo que el doctor te pide por voz, inferir sus intenciones creativamente y comunicarte con la Base de Datos.\n" .
                        "Modelos y sus esquemas disponibles:\n" .
                        "- Patient: name, id_number, phone, email, gender, address, antecedentes\n" .
                        "- Consultorio: name, location, phone, is_active\n" .
                        "- Servicio: name, description, price, is_active\n" .
                        "- MetodoPago: name, type, color, icon, details, is_active\n" .
                        "- Setting: app_name\n\n" .

                        "Tienes libertad absoluta. Respeta HEX e íconos FontAwesome. IMPORTANTE: Si la voz exige múltiples cosas complejas (Ej: borrar duplicados y/o editar a la vez), puedes generar MÚLTIPLES BLOQUES uno detrás del otro.\n" .
                        "Responde ÚNICAMENTE con los bloques mágicos:\n\n" .

                        "[OMNI_ACTION]\n" .
                        "action: {CREATE|UPDATE|DELETE|NAVIGATE}\n" .
                        "model: {Patient|Consultorio|Servicio|MetodoPago|Setting}\n" .
                        "target: {Nombre exacto buscado si es update/delete/navigate, sino null}\n" .
                        "{cualquier_campo_db_en_minuscula}: {tu deduccion}\n" .
                        "[/OMNI_ACTION]";

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

            // --- NUEVO REACTOR CORE: OMNI-ACTION MULTI-HILO DINÁMICO ---
            // Utilizamos preg_match_all para permitir que la IA escupa MÚLTIPLES acciones en cadena
            if (preg_match_all('/\[OMNI_ACTION\](.*?)\[\/OMNI_ACTION\]/is', $content, $matches)) {
                $lastResult = ['content' => 'Error, acción declinada.'];
                $actionCount = 0;
                $responses = [];

                foreach ($matches[1] as $block) {
                    $lastResult = $this->handleOmniAction(trim($block));
                    $responses[] = str_replace(['✅', '🗑️', '✏️', '⚠️'], '', strip_tags($lastResult['content']));
                    $actionCount++;
                }

                // Consolidar informe visual si hay hiper-operaciones
                if ($actionCount > 1) {
                    $lastResult['content'] = "⚡ **Multi-Ejecución ({$actionCount} acciones):**\n- " . implode("\n- ", $responses);
                }

                return $lastResult;
            }

            return ['content' => $content ?: 'No recibí respuesta de la IA.'];

        } catch (\Exception $e) {
            Log::error('DocIaService Exception: ' . $e->getMessage());
            return ['content' => "Error al procesar la IA: " . $e->getMessage()];
        }
    }

    /**
     * El Motor Verdaderamente Omnipotente
     * Interpreta cualquier modelo, cualquier acción y lo ejecuta dinámicamente usando Reflexión y Eloquent.
     */
    protected function handleOmniAction(string $block)
    {
        $lines = explode("\n", $block);
        $payload = [];

        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                [$key, $val] = explode(':', $line, 2);
                $key = strtolower(trim($key)); // Forzar minúsculas para que Eloquent no ignore campos por Capitalización
                $val = trim($val);
                // Ignorar literales null
                $payload[$key] = (strtolower($val) !== 'null' && $val !== '') ? $val : null;
            }
        }

        $action = strtoupper($payload['action'] ?? '');
        // Aseguramos que el Modelo comience con mayúscula como lo pide Laravel (MetodoPago)
        $modelName = '';
        foreach(['Patient', 'Consultorio', 'Servicio', 'MetodoPago', 'Setting'] as $m) {
            if (strtolower($payload['model'] ?? '') === strtolower($m)) $modelName = $m;
        }
        $target = $payload['target'] ?? null;

        unset($payload['action'], $payload['model'], $payload['target']); // El resto son campos limpios para DB

        // Seguridad: Limitar modelos permitidos
        $allowedModels = ['Patient', 'Consultorio', 'Servicio', 'MetodoPago', 'Setting'];
        
        if ($action === 'NAVIGATE') {
            return $this->handleNavigate($target ?? $modelName);
        }

        if (!in_array($modelName, $allowedModels)) {
            return ['content' => "⚠️ Modelo o Módulo Desconocido: {$modelName}"];
        }

        $modelClass = "App\\Models\\" . $modelName;

        try {
            // Acción: CREAR
            if ($action === 'CREATE') {
                $payload['id'] = (string) Str::uuid();
                if (in_array('tenant_id', app($modelClass)->getFillable())) {
                    $payload['tenant_id'] = auth()->user()->tenant_id ?? (string) Str::uuid();
                }
                
                $instance = $modelClass::create($payload);
                $routeName = $this->getRouteNameForModel($modelName);
                
                return [
                    'content' => "✅ Omnipotencia Ejecutada: He creado exitosamente un nuevo registro en el módulo **{$modelName}**.\nAbriendo...",
                    'action' => 'redirect',
                    'url' => route($routeName)
                ];
            }

            // Acción: ELIMINAR / ACTUALIZAR (Requiere hacer una búsqueda primero)
            if (in_array($action, ['DELETE', 'UPDATE'])) {
                if (!$target) return ['content' => "⚠️ Para editar o eliminar, debes mencionar el nombre del registro buscado."];
                
                // Construcción de Query Dinámica Resiliente
                $query = $modelClass::where('name', 'LIKE', "%{$target}%");
                
                // Solo si el modelo actual soporta el campo "id_number", agregamos el filtro condicional
                if (in_array('id_number', app($modelClass)->getFillable())) {
                    $query->orWhere('id_number', 'LIKE', "%{$target}%");
                }
                
                // HACK DE PRIORIDAD ABSOLUTA: 
                // Si la persona dice "banesco" y existen "Banesco" y "Transferencia Banesco",
                // ordenamos matemáticamente por la longitud del texto. Así el más corto (y por consiguiente la coincidencia más exacta) gana.
                $instance = $query->orderByRaw('LENGTH(name) ASC')->first();

                if (!$instance) {
                    return ['content' => "⚠️ Registros Clínicos Consultados: No localicé ningún {$modelName} coincidente con '**{$target}**'."];
                }

                if ($action === 'DELETE') {
                    $instance->delete();
                    $routeName = $this->getRouteNameForModel($modelName);
                    return [
                        'content' => "🗑️ **Purga Ejecutada:** Hemos erradicado a {$instance->name} de la tabla {$modelName}.",
                        'action' => 'redirect',
                        'url' => route($routeName)
                    ];
                }

                if ($action === 'UPDATE') {
                    $instance->update(array_filter($payload)); // Actualiza solo con lo que no es null
                    $routeName = $this->getRouteNameForModel($modelName);
                    return [
                        'content' => "✏️ **Mutación Exitosa:** Registo de {$instance->name} actualizado masivamente.",
                        'action' => 'redirect',
                        'url' => route($routeName)
                    ];
                }
            }

        } catch (\Exception $e) {
            return ['content' => "❌ El Cerebro de Datos denegó la operación Universal. Detalle: " . $e->getMessage()];
        }

        return ['content' => "Comando OMNI interceptado pero sin acción ejecutable."];
    }

    /**
     * Mapea correctamente el modelo de base de datos a su ruta visual en la plataforma.
     */
    protected function getRouteNameForModel(string $modelName): string
    {
        $map = [
            'Patient' => 'pacientes.index',
            'Consultorio' => 'consultorios.index',
            'Servicio' => 'servicios.index',
            'MetodoPago' => 'metodos.index',
            'Setting' => 'settings.index',
        ];
        return $map[$modelName] ?? 'admin.dashboard';
    }

    /**
     * Lógica Dinámica para Moverse por todo el Sistema
     */
    protected function handleNavigate(string $destination)
    {
        $destination = strtolower($destination);
        $url = url('/'); // Default

        if (str_contains($destination, 'paciente')) {
            $url = route('pacientes.index');
        } elseif (str_contains($destination, 'dashboard') || str_contains($destination, 'panel')) {
            $url = route('admin.dashboard');
        } elseif (str_contains($destination, 'configuracion') || str_contains($destination, 'ajuste') || str_contains($destination, 'setting')) {
            $url = route('settings.index');
        }

        return [
            'content' => "🚀 Permiso concedido. Llevándote a hipervelocidad hacia la central de **{$destination}**.",
            'action' => 'redirect',
            'url' => $url
        ];
    }
}
