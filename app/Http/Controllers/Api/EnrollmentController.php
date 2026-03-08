<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\GradeRecord;
use App\Models\Period;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Enrollment::with(['student.user', 'subject', 'academicYear']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('semester_number')) {
            $query->where('semester_number', $request->semester_number);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['data' => $enrollments]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'semester_number' => 'sometimes|integer|min:1|max:2',
            'status' => ['sometimes', Rule::in(['enrolled', 'withdrawn', 'completed', 'failed'])],
        ]);

        $semesterNumber = $validated['semester_number'] ?? 1;

        $exists = Enrollment::where('student_id', $validated['student_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('semester_number', $semesterNumber)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'El estudiante ya está matriculado en esta asignatura para el semestre y año académico indicados.',
            ], 422);
        }

        $enrollment = Enrollment::create([
            'student_id' => $validated['student_id'],
            'subject_id' => $validated['subject_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'semester_number' => $semesterNumber,
            'status' => $validated['status'] ?? 'enrolled',
        ]);

        return response()->json(
            $enrollment->load(['student.user', 'subject', 'academicYear']),
            201
        );
    }

    public function update(Request $request, Enrollment $enrollment): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['sometimes', Rule::in(['enrolled', 'withdrawn', 'completed', 'failed'])],
            'final_grade' => 'sometimes|nullable|numeric|min:1.0|max:5.0',
        ]);

        $enrollment->update($validated);

        return response()->json($enrollment->load(['student.user', 'subject', 'academicYear']));
    }

    public function destroy(Enrollment $enrollment): JsonResponse
    {
        if ($enrollment->status !== 'enrolled') {
            return response()->json([
                'message' => 'Solo se pueden eliminar matrículas con estado "enrolled". Esta matrícula tiene estado "'.$enrollment->status.'".',
            ], 422);
        }

        $enrollment->delete();

        return response()->json(['message' => 'Matrícula eliminada correctamente.']);
    }

    /**
     * Bulk-create enrollments for a student in one request.
     * Silently skips duplicates and returns both created and skipped counts.
     *
     * POST /api/enrollments/bulk
     */
    public function bulkStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'semester_number' => 'required|integer|min:1|max:10',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'integer|exists:subjects,id',
        ]);

        $created = [];
        $skipped = [];

        foreach ($validated['subject_ids'] as $subjectId) {
            $exists = Enrollment::where('student_id', $validated['student_id'])
                ->where('subject_id', $subjectId)
                ->where('academic_year_id', $validated['academic_year_id'])
                ->where('semester_number', $validated['semester_number'])
                ->exists();

            if ($exists) {
                $skipped[] = $subjectId;

                continue;
            }

            $created[] = Enrollment::create([
                'student_id' => $validated['student_id'],
                'subject_id' => $subjectId,
                'academic_year_id' => $validated['academic_year_id'],
                'semester_number' => $validated['semester_number'],
                'status' => 'enrolled',
            ])->load(['student.user', 'subject', 'academicYear']);
        }

        return response()->json([
            'created' => $created,
            'created_count' => count($created),
            'skipped_count' => count($skipped),
        ], 201);
    }

    /**
     * Calculate and persist final grades for enrolled subjects based on
     * weighted GradeRecords (period weights).
     * Updates enrollment.final_grade and sets status to 'completed' or 'failed'.
     *
     * POST /api/enrollments/calculate-finals
     */
    public function calculateFinals(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'student_id' => 'nullable|integer|exists:students,id',
        ]);

        // Load periods with weights for this academic year
        $periods = Period::where('academic_year_id', $validated['academic_year_id'])
            ->orderBy('number')
            ->get()
            ->keyBy('id');

        $totalWeight = $periods->sum('weight');

        if ($totalWeight === 0) {
            return response()->json(['message' => 'Los períodos no tienen pesos configurados.'], 422);
        }

        $query = Enrollment::where('academic_year_id', $validated['academic_year_id'])
            ->where('status', 'enrolled');

        if (! empty($validated['student_id'])) {
            $query->where('student_id', $validated['student_id']);
        }

        $enrollments = $query->get();
        $updated = 0;
        $skipped = 0;

        foreach ($enrollments as $enrollment) {
            $records = GradeRecord::where('student_id', $enrollment->student_id)
                ->where('subject_id', $enrollment->subject_id)
                ->whereIn('period_id', $periods->keys())
                ->get();

            if ($records->isEmpty()) {
                $skipped++;

                continue;
            }

            // Weighted average: Σ(grade × period.weight) / Σ(covered period weights)
            $coveredWeight = 0;
            $weightedSum = 0;

            foreach ($records as $record) {
                if ($record->grade === null) {
                    continue;
                }
                $periodWeight = $periods->get($record->period_id)?->weight ?? 0;
                $weightedSum += $record->grade * $periodWeight;
                $coveredWeight += $periodWeight;
            }

            if ($coveredWeight === 0) {
                $skipped++;

                continue;
            }

            $finalGrade = round($weightedSum / $coveredWeight, 2);
            $status = $finalGrade >= 3.0 ? 'completed' : 'failed';

            $enrollment->update([
                'final_grade' => $finalGrade,
                'status' => $status,
            ]);

            $updated++;
        }

        return response()->json([
            'updated' => $updated,
            'skipped' => $skipped,
            'message' => "Se calcularon {$updated} notas finales. {$skipped} matrículas sin calificaciones registradas.",
        ]);
    }
}
