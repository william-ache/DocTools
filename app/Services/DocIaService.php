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

        $assistantName = config('services.assistant.name', 'DocIA');
        $systemPrompt = "Eres '{$assistantName}', el Omnipotente del SaaS Médico Consultia.\n" .
                        "Si el usuario pide varias cosas, DEBES repetir el bloque [OMNI_ACTION] para cada una.\n\n" .
                        "EJEMPLO PARE 2 PACIENTES:\n" .
                        "[OMNI_ACTION]\naction: CREATE\nmodel: Patient\nname: Juan\n[/OMNI_ACTION]\n" .
                        "[OMNI_ACTION]\naction: CREATE\nmodel: Patient\nname: Pedro\n[/OMNI_ACTION]\n\n" .
                        "CAMPOS:\n" .
                        "- Patient: name, id_number, phone, email, gender\n" .
                        "- Servicio: name, description, price\n" .
                        "- MetodoPago: name, type, color\n" .
                        "FORMATO:\n" .
                        "[OMNI_ACTION]\naction: {CREATE|UPDATE|DELETE|NAVIGATE}\nmodel: {Patient|Consultorio|Servicio|MetodoPago|Setting}\ntarget: {Nombre o ALL}\n[/OMNI_ACTION]";

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(120)
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
                $key = strtolower(trim($key)); 
                $val = trim($val);
                // Limpiar comillas extras que modelos como Qwen suelen poner
                $val = trim($val, "\"'");
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
        $target = $payload['target'] ?? $payload['name'] ?? $payload['nombre'] ?? $payload['paciente'] ?? null;

        // MAPEO DE SINÓNIMOS PARA MODELOS PEQUEÑOS (Qwen/Phi3)
        $synonyms = [
            'nombre' => 'name',
            'paciente' => 'name',
            'apellido' => 'name',
            'telefono' => 'phone',
            'celular' => 'phone',
            'correo' => 'email',
            'cedula' => 'id_number',
            'dni' => 'id_number',
            'direccion' => 'address',
            'ubicacion' => 'location',
            'precio' => 'price',
        ];

        foreach ($synonyms as $bad => $good) {
            if (isset($payload[$bad])) {
                $payload[$good] = $payload[$bad];
                if ($bad !== $good) unset($payload[$bad]);
            }
        }

        // Si es una creación y no tenemos 'name', pero tenemos 'target', lo usamos.
        if (!isset($payload['name']) && $target) {
            $payload['name'] = $target;
        }

        unset($payload['action'], $payload['model'], $payload['target']); 

        // Seguridad: Limitar modelos permitidos
        $allowedModels = ['Patient', 'Consultorio', 'Servicio', 'MetodoPago', 'Setting', 'Finanzas', 'Calendario', 'Personal', 'Plantilla', 'Dashboard'];
        
        if ($action === 'NAVIGATE') {
            return $this->handleNavigate($target ?? $payload['model'] ?? $modelName);
        }

        if (!in_array($modelName, $allowedModels)) {
            return ['content' => "⚠️ Modelo o Módulo Desconocido: {$modelName}"];
        }

        $modelClass = "App\\Models\\" . $modelName;

        try {
            // Acción: CREAR
            if ($action === 'CREATE') {
                $payload['id'] = (string) Str::uuid();
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
                
                // --- NUEVO: SOPORTE PARA OPERACIONES MASIVAS ---
                if (in_array(strtoupper($target), ['ALL', 'TODOS', 'EVERYTHING'])) {
                    $count = $modelClass::count();
                    if ($action === 'DELETE') {
                        $modelClass::query()->delete();
                        $routeName = $this->getRouteNameForModel($modelName);
                        return [
                            'content' => "🗑️ **Purificación Completa:** He eliminado los {$count} registros del módulo **{$modelName}**.",
                            'action' => 'redirect',
                            'url' => route($routeName)
                        ];
                    }
                    if ($action === 'UPDATE') {
                        $modelClass::query()->update(array_filter($payload));
                        $routeName = $this->getRouteNameForModel($modelName);
                        return [
                            'content' => "✏️ **Actualización Masiva:** Se han modificado {$count} registros de **{$modelName}**.",
                            'action' => 'redirect',
                            'url' => route($routeName)
                        ];
                    }
                }

                // Construcción de Query Dinámica Resiliente (Búsqueda Individual)
                $query = $modelClass::whereRaw('name LIKE ? COLLATE NOCASE', ["%" . $target . "%"]);
                
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
                    $instance->update($payload); 
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
        $d = strtolower(trim($destination));
        Log::info("DocIA Navigating to: [" . $d . "]");
        
        $url = route('admin.dashboard'); 

        if (str_contains($d, 'pacient') || str_contains($d, 'patient')) {
            $url = route('pacientes.index');
        } elseif (str_contains($d, 'servici') || str_contains($d, 'service')) {
            $url = route('servicios.index');
        } elseif (str_contains($d, 'consultorio') || str_contains($d, 'clinic')) {
            $url = route('consultorios.index');
        } elseif (str_contains($d, 'pago') || str_contains($d, 'metodo') || str_contains($d, 'finanza') || str_contains($d, 'cobro')) {
            $url = route('finanzas.index');
        } elseif (str_contains($d, 'config') || str_contains($d, 'ajuste') || str_contains($d, 'setting')) {
            $url = route('settings.index');
        } elseif (str_contains($d, 'personal') || str_contains($d, 'empleado') || str_contains($d, 'employee')) {
            $url = route('employees.index');
        } elseif (str_contains($d, 'calendar') || str_contains($d, 'cita') || str_contains($d, 'horario')) {
            $url = route('admin.calendario');
        } elseif (str_contains($d, 'plantilla') || str_contains($d, 'template')) {
            $url = route('templates.index');
        }

        return [
            'content' => "🚀 Llevándote a **" . ucfirst($destination) . "**.",
            'action' => 'redirect',
            'url' => $url
        ];
    }
}
