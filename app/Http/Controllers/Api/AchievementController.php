<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\AchievementIndicator;
use App\Models\StudentAchievement;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AchievementController extends Controller
{
    /**
     * List achievements for a subject/period
     */
    public function index(Request $request): JsonResponse
    {
        // Return empty array if required params are missing
        if (!$request->subject_id || !$request->period_id) {
            return response()->json([]);
        }

        $request->validate([
            'subject_id' => 'exists:subjects,id',
            'period_id' => 'exists:periods,id',
        ]);

        $achievements = Achievement::with('indicators')
            ->where('subject_id', $request->subject_id)
            ->where('period_id', $request->period_id)
            ->orderBy('order')
            ->get();

        return response()->json($achievements);
    }

    /**
     * Create a new achievement
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'period_id' => 'required|exists:periods,id',
            'code' => 'nullable|string|max:10',
            'description' => 'required|string',
            'type' => 'required|in:cognitive,procedural,attitudinal',
            'order' => 'nullable|integer|min:1',
            'indicators' => 'nullable|array',
            'indicators.*.description' => 'required_with:indicators|string',
        ]);

        $achievement = Achievement::create($request->only([
            'subject_id', 'period_id', 'code', 'description', 'type', 'order'
        ]));

        // Create indicators if provided
        if ($request->indicators) {
            foreach ($request->indicators as $index => $indicator) {
                AchievementIndicator::create([
                    'achievement_id' => $achievement->id,
                    'code' => $indicator['code'] ?? 'I' . ($index + 1),
                    'description' => $indicator['description'],
                    'order' => $index + 1,
                ]);
            }
        }

        return response()->json($achievement->load('indicators'), 201);
    }

    /**
     * Update an achievement
     */
    public function update(Request $request, Achievement $achievement): JsonResponse
    {
        $request->validate([
            'code' => 'nullable|string|max:10',
            'description' => 'sometimes|string',
            'type' => 'sometimes|in:cognitive,procedural,attitudinal',
            'order' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $achievement->update($request->only([
            'code', 'description', 'type', 'order', 'is_active'
        ]));

        return response()->json($achievement->load('indicators'));
    }

    /**
     * Delete an achievement
     */
    public function destroy(Achievement $achievement): JsonResponse
    {
        $achievement->delete();

        return response()->json(['message' => 'Logro eliminado']);
    }

    /**
     * Add indicator to achievement
     */
    public function addIndicator(Request $request, Achievement $achievement): JsonResponse
    {
        $request->validate([
            'code' => 'nullable|string|max:10',
            'description' => 'required|string',
        ]);

        $indicator = $achievement->indicators()->create([
            'code' => $request->code,
            'description' => $request->description,
            'order' => $achievement->indicators()->count() + 1,
        ]);

        return response()->json($indicator, 201);
    }

    /**
     * Record student achievement status
     */
    public function recordStudentAchievement(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'achievement_id' => 'required|exists:achievements,id',
            'status' => 'required|in:pending,in_progress,achieved,not_achieved',
            'observations' => 'nullable|string',
        ]);

        $record = StudentAchievement::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'achievement_id' => $request->achievement_id,
            ],
            [
                'status' => $request->status,
                'observations' => $request->observations,
                'evaluated_by' => auth()->id(),
                'evaluated_at' => now(),
            ]
        );

        return response()->json($record->load(['student.user', 'achievement']));
    }

    /**
     * Bulk record achievements for multiple students
     */
    public function bulkRecordAchievements(Request $request): JsonResponse
    {
        $request->validate([
            'achievement_id' => 'required|exists:achievements,id',
            'records' => 'required|array',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.status' => 'required|in:pending,in_progress,achieved,not_achieved',
            'records.*.observations' => 'nullable|string',
        ]);

        $results = [];
        foreach ($request->records as $record) {
            $results[] = StudentAchievement::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'achievement_id' => $request->achievement_id,
                ],
                [
                    'status' => $record['status'],
                    'observations' => $record['observations'] ?? null,
                    'evaluated_by' => auth()->id(),
                    'evaluated_at' => now(),
                ]
            );
        }

        return response()->json([
            'message' => 'Logros registrados correctamente',
            'count' => count($results),
        ]);
    }

    /**
     * Get student achievements for a period
     */
    public function studentAchievements(Student $student, Request $request): JsonResponse
    {
        $request->validate([
            'period_id' => 'required|exists:periods,id',
        ]);

        $achievements = StudentAchievement::with(['achievement.subject', 'achievement.indicators'])
            ->where('student_id', $student->id)
            ->whereHas('achievement', function ($q) use ($request) {
                $q->where('period_id', $request->period_id);
            })
            ->get();

        return response()->json($achievements);
    }

    /**
     * Copy achievements from one period to another
     */
    public function copyAchievements(Request $request): JsonResponse
    {
        $request->validate([
            'source_subject_id' => 'required|exists:subjects,id',
            'source_period_id' => 'required|exists:periods,id',
            'target_period_id' => 'required|exists:periods,id|different:source_period_id',
        ]);

        $sourceAchievements = Achievement::with('indicators')
            ->where('subject_id', $request->source_subject_id)
            ->where('period_id', $request->source_period_id)
            ->get();

        $copied = 0;
        foreach ($sourceAchievements as $source) {
            $newAchievement = Achievement::create([
                'subject_id' => $request->source_subject_id,
                'period_id' => $request->target_period_id,
                'code' => $source->code,
                'description' => $source->description,
                'type' => $source->type,
                'order' => $source->order,
            ]);

            foreach ($source->indicators as $indicator) {
                AchievementIndicator::create([
                    'achievement_id' => $newAchievement->id,
                    'code' => $indicator->code,
                    'description' => $indicator->description,
                    'order' => $indicator->order,
                ]);
            }
            $copied++;
        }

        return response()->json([
            'message' => "Se copiaron $copied logros al nuevo período",
            'count' => $copied,
        ]);
    }

    /**
     * Import achievements from CSV
     */
    public function importFromCsv(Request $request): JsonResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'period_id' => 'required|exists:periods,id',
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');

        // Skip header row
        $header = fgetcsv($handle, 0, ',');

        $imported = 0;
        $errors = [];
        $lineNumber = 1;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $lineNumber++;

            // Expected format: code, description, type (cognitive/procedural/attitudinal)
            if (count($row) < 2) {
                $errors[] = "Línea $lineNumber: formato inválido";
                continue;
            }

            $code = trim($row[0] ?? '');
            $description = trim($row[1] ?? '');
            $type = strtolower(trim($row[2] ?? 'cognitive'));

            if (empty($description)) {
                $errors[] = "Línea $lineNumber: descripción vacía";
                continue;
            }

            // Validate type
            if (!in_array($type, ['cognitive', 'procedural', 'attitudinal'])) {
                $type = 'cognitive';
            }

            try {
                Achievement::create([
                    'subject_id' => $request->subject_id,
                    'period_id' => $request->period_id,
                    'code' => $code ?: 'L' . ($imported + 1),
                    'description' => $description,
                    'type' => $type,
                    'order' => $imported + 1,
                    'is_active' => true,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Línea $lineNumber: " . $e->getMessage();
            }
        }

        fclose($handle);

        return response()->json([
            'message' => "Se importaron $imported logros",
            'count' => $imported,
            'errors' => $errors,
        ]);
    }
}
