<?php

namespace App\Services;

use Anthropic\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeInsightService
{
    private string $systemPrompt = 'Eres un coordinador académico experto en el sistema educativo colombiano. '
        .'Escribes en español formal pero accesible, enfocado en acciones concretas. '
        .'Nunca repitas números literalmente — interprétales. '
        .'Sé empático y propositivo.';

    /**
     * Full student analysis: narrative + recommendations in one call.
     * Receives enriched data: grades, achievements, attendance, remedials, disciplinary.
     *
     * @return array{narrative: string, recommendations: array}
     */
    public function studentAnalysis(array $data): array
    {
        $prompt = $this->buildStudentAnalysisPrompt($data);
        $text = $this->complete($prompt, 900);

        return $this->parseAnalysisResponse($text);
    }

    /**
     * Generate an executive summary for the school/group.
     */
    public function weeklyExecutiveSummary(array $schoolData): string
    {
        $prompt = $this->buildExecutiveSummaryPrompt($schoolData);

        return $this->complete($prompt, 400);
    }

    /**
     * Call Claude first; fall back to Ollama if Claude is unavailable or fails.
     */
    private function complete(string $prompt, int $maxTokens): string
    {
        $apiKey = config('anthropic.api_key');

        if (! empty($apiKey)) {
            try {
                return $this->callClaude($prompt, $maxTokens, $apiKey);
            } catch (\Throwable $e) {
                Log::warning('ClaudeInsightService: Claude failed, falling back to Ollama', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->callOllama($prompt, $maxTokens);
    }

    private function callClaude(string $prompt, int $maxTokens, string $apiKey): string
    {
        $client = new Client(apiKey: $apiKey);
        $model = config('anthropic.model', 'claude-haiku-4-5-20251001');

        $message = $client->messages->create(
            maxTokens: $maxTokens,
            messages: [['role' => 'user', 'content' => $prompt]],
            model: $model,
            system: $this->systemPrompt,
        );

        return trim($message->content[0]->text ?? '');
    }

    private function callOllama(string $prompt, int $maxTokens): string
    {
        $ollamaUrl = config('anthropic.ollama_url', 'http://localhost:11434');
        $ollamaModel = config('anthropic.ollama_model', 'qwen2.5:7b');

        $response = Http::timeout(120)
            ->post("{$ollamaUrl}/api/chat", [
                'model' => $ollamaModel,
                'stream' => false,
                'options' => ['num_predict' => $maxTokens],
                'messages' => [
                    ['role' => 'system', 'content' => $this->systemPrompt],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Ollama request failed: '.$response->status().' '.$response->body());
        }

        return trim($response->json('message.content') ?? '');
    }

    private function buildStudentAnalysisPrompt(array $data): string
    {
        $name = $data['student']['name'];
        $group = $data['student']['group'];
        $period = $data['period'];

        // Risk level
        $riskLabel = match ($data['risk']['level'] ?? 'low') {
            'high' => 'riesgo alto',
            'medium' => 'riesgo moderado',
            default => 'sin riesgo significativo',
        };

        // Grades summary
        $gradesLines = collect($data['grades'])->map(function ($g) {
            $note = $g['grade'] !== null ? "nota {$g['grade']} ({$g['performance']})" : 'sin nota';
            $obs = ! empty($g['observations']) ? " — observación docente: \"{$g['observations']}\"" : '';
            $rec = ! empty($g['recommendations']) ? " — recomendación docente: \"{$g['recommendations']}\"" : '';

            return "  • {$g['subject']} ({$g['area']}): {$note}{$obs}{$rec}";
        })->join("\n");

        // Achievements summary
        $achievementsSection = '';
        if (! empty($data['achievements'])) {
            $lines = collect($data['achievements'])->map(function ($a) {
                $obs = ! empty($a['observations']) ? " Observación: \"{$a['observations']}\"" : '';

                return "  • {$a['subject']}: {$a['achieved']} alcanzados, {$a['not_achieved']} no alcanzados, {$a['in_progress']} en progreso de {$a['total']} logros.{$obs}";
            })->join("\n");
            $achievementsSection = "\nLOGROS POR ASIGNATURA (período {$period}):\n{$lines}";
        }

        // Attendance
        $att = $data['attendance'];
        $attText = $att['total_days'] > 0
            ? "{$att['percentage']}% de asistencia ({$att['present_days']} presentes, {$att['absent_days']} ausencias, {$att['late_days']} tardanzas de {$att['total_days']} días)"
            : 'sin registro de asistencia';

        // Pending remedials
        $remedialsText = 'ninguna';
        if (! empty($data['pending_remedials'])) {
            $remedialsText = collect($data['pending_remedials'])
                ->map(fn ($r) => "{$r['subject']} — \"{$r['title']}\" (vence {$r['due_date']})")
                ->join('; ');
        }

        // Disciplinary
        $disciplinaryText = 'ninguno';
        if (! empty($data['disciplinary_incidents'])) {
            $disciplinaryText = count($data['disciplinary_incidents']).' incidente(s) reciente(s) (tipo '.
                collect($data['disciplinary_incidents'])->pluck('type')->unique()->join(', ').')';
        }

        // Failing subjects for recommendations
        $failingSubjects = collect($data['grades'])
            ->filter(fn ($g) => $g['grade'] !== null && $g['grade'] < 3.0)
            ->values();

        $failingForRecs = $failingSubjects->map(fn ($g) => $g['subject'])->join(', ');

        return <<<PROMPT
Analiza de forma integral la situación académica del estudiante {$name} del grupo {$group} en el período {$period} ({$riskLabel}).

NOTAS OBTENIDAS:
{$gradesLines}
{$achievementsSection}

ASISTENCIA: {$attText}
NIVELACIONES PENDIENTES: {$remedialsText}
INCIDENTES DE CONVIVENCIA: {$disciplinaryText}

Basándote en TODA la información anterior (notas, logros alcanzados/no alcanzados, observaciones de docentes, asistencia, nivelaciones y convivencia), genera tu análisis en el siguiente formato JSON exacto sin ningún texto adicional fuera del JSON:

{
  "narrative": "Párrafo de 4-5 oraciones que interprete la situación integral del estudiante. Menciona fortalezas observadas en logros o materias aprobadas, señala patrones preocupantes, relaciona la asistencia con el rendimiento si aplica, y cierra con la acción inmediata más urgente. No repitas números literalmente.",
  "recommendations": [
    {"subject": "nombre asignatura", "strategy": "estrategia pedagógica concreta basada en los logros no alcanzados y la nota (1 oración)", "activity": "actividad específica de refuerzo alineada con los logros pendientes (1 oración)"}
  ]
}

Las recomendaciones deben cubrir ÚNICAMENTE las asignaturas reprobadas: {$failingForRecs}.
Si no hay asignaturas reprobadas, usa "recommendations": [].
PROMPT;
    }

    private function parseAnalysisResponse(string $text): array
    {
        // Extract JSON object from response
        if (preg_match('/\{.*\}/s', $text, $matches)) {
            $decoded = json_decode($matches[0], true);
            if (is_array($decoded) && isset($decoded['narrative'])) {
                return [
                    'narrative' => trim($decoded['narrative']),
                    'recommendations' => $decoded['recommendations'] ?? [],
                ];
            }
        }

        Log::warning('ClaudeInsightService: could not parse analysis JSON', ['text' => $text]);

        // Fallback: treat entire response as narrative
        return [
            'narrative' => $text,
            'recommendations' => [],
        ];
    }

    private function buildExecutiveSummaryPrompt(array $data): string
    {
        $total = $data['total_students'] ?? 0;
        $highRisk = $data['high_risk'] ?? 0;
        $mediumRisk = $data['medium_risk'] ?? 0;
        $avgScore = $data['average_score'] ?? 0;
        $topGroups = $data['top_risk_groups'] ?? [];
        $topSubjects = $data['top_failing_subjects'] ?? [];

        $groupsText = empty($topGroups)
            ? ''
            : 'Grupos con mayor riesgo: '.implode(', ', $topGroups).'. ';

        $subjectsText = empty($topSubjects)
            ? ''
            : 'Asignaturas con más reprobados: '.implode(', ', $topSubjects).'. ';

        return 'Redacta un resumen ejecutivo (máximo 150 palabras) para el rector sobre la situación académica actual. '
            ."Datos del período: {$total} estudiantes analizados, "
            ."{$highRisk} en riesgo alto, {$mediumRisk} en riesgo moderado, "
            ."índice promedio {$avgScore}/100. "
            .$groupsText
            .$subjectsText
            .'Incluye: situación general, grupos o patrones prioritarios identificados, '
            .'y exactamente 2 recomendaciones institucionales concretas. '
            .'Tono formal ejecutivo.';
    }
}
