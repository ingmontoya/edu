<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RemedialActivity;
use App\Models\StudentRemedial;
use App\Models\Student;
use App\Models\GradeRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RemedialController extends Controller
{
    /**
     * List remedial activities
     */
    public function index(Request $request): JsonResponse
    {
        $query = RemedialActivity::with(['subject', 'period', 'teacher.user']);

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->period_id) {
            $query->where('period_id', $request->period_id);
        }

        if ($request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $activities = $query->orderByDesc('assigned_date')->get();

        return response()->json($activities);
    }

    /**
     * Create a remedial activity
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'period_id' => 'required|exists:periods,id',
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'type' => 'required|in:recovery,reinforcement,leveling',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
            'max_grade' => 'nullable|numeric|min:1|max:5',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $activity = RemedialActivity::create($request->only([
            'subject_id', 'period_id', 'teacher_id', 'title', 'description',
            'instructions', 'type', 'assigned_date', 'due_date', 'max_grade'
        ]));

        // Assign to specific students if provided
        if ($request->student_ids) {
            foreach ($request->student_ids as $studentId) {
                StudentRemedial::create([
                    'student_id' => $studentId,
                    'remedial_activity_id' => $activity->id,
                    'status' => 'pending',
                ]);
            }
        }

        return response()->json($activity->load(['subject', 'studentRemedials.student.user']), 201);
    }

    /**
     * Show remedial activity details
     */
    public function show(RemedialActivity $remedialActivity): JsonResponse
    {
        $remedialActivity->load([
            'subject',
            'period',
            'teacher.user',
            'studentRemedials.student.user'
        ]);

        return response()->json($remedialActivity);
    }

    /**
     * Update a remedial activity
     */
    public function update(Request $request, RemedialActivity $remedialActivity): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'instructions' => 'nullable|string',
            'type' => 'sometimes|in:recovery,reinforcement,leveling',
            'due_date' => 'sometimes|date',
            'max_grade' => 'nullable|numeric|min:1|max:5',
            'is_active' => 'sometimes|boolean',
        ]);

        $remedialActivity->update($request->only([
            'title', 'description', 'instructions', 'type', 'due_date', 'max_grade', 'is_active'
        ]));

        return response()->json($remedialActivity);
    }

    /**
     * Delete a remedial activity
     */
    public function destroy(RemedialActivity $remedialActivity): JsonResponse
    {
        $remedialActivity->delete();

        return response()->json(['message' => 'Actividad de nivelación eliminada']);
    }

    /**
     * Assign students to a remedial activity
     */
    public function assignStudents(Request $request, RemedialActivity $remedialActivity): JsonResponse
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        foreach ($request->student_ids as $studentId) {
            StudentRemedial::firstOrCreate([
                'student_id' => $studentId,
                'remedial_activity_id' => $remedialActivity->id,
            ], [
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'message' => 'Estudiantes asignados correctamente',
            'activity' => $remedialActivity->load('studentRemedials.student.user'),
        ]);
    }

    /**
     * Auto-assign students with failing grades
     */
    public function autoAssignFailingStudents(Request $request, RemedialActivity $remedialActivity): JsonResponse
    {
        // Find students with failing grades in this subject/period
        $failingStudents = GradeRecord::where('subject_id', $remedialActivity->subject_id)
            ->where('period_id', $remedialActivity->period_id)
            ->where('grade', '<', 3.0)
            ->pluck('student_id')
            ->unique();

        $assigned = 0;
        foreach ($failingStudents as $studentId) {
            $created = StudentRemedial::firstOrCreate([
                'student_id' => $studentId,
                'remedial_activity_id' => $remedialActivity->id,
            ], [
                'status' => 'pending',
            ]);

            if ($created->wasRecentlyCreated) {
                $assigned++;
            }
        }

        return response()->json([
            'message' => "Se asignaron $assigned estudiantes con desempeño bajo",
            'assigned_count' => $assigned,
            'total_failing' => $failingStudents->count(),
        ]);
    }

    /**
     * Grade a student's remedial activity
     */
    public function gradeStudent(Request $request, StudentRemedial $studentRemedial): JsonResponse
    {
        $request->validate([
            'grade' => 'required|numeric|min:1|max:5',
            'teacher_feedback' => 'nullable|string',
        ]);

        $maxGrade = $studentRemedial->remedialActivity->max_grade ?? 3.0;

        // Cap grade at max allowed
        $grade = min($request->grade, $maxGrade);

        $studentRemedial->update([
            'status' => 'graded',
            'grade' => $grade,
            'teacher_feedback' => $request->teacher_feedback,
            'graded_at' => now(),
            'graded_by' => auth()->id(),
        ]);

        // Optionally update the original grade record
        if ($request->update_grade_record) {
            $this->updateGradeRecord($studentRemedial, $grade);
        }

        return response()->json([
            'message' => 'Actividad calificada correctamente',
            'student_remedial' => $studentRemedial->load(['student.user', 'remedialActivity']),
        ]);
    }

    /**
     * Bulk grade remedial activities
     */
    public function bulkGrade(Request $request, RemedialActivity $remedialActivity): JsonResponse
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_remedial_id' => 'required|exists:student_remedials,id',
            'grades.*.grade' => 'required|numeric|min:1|max:5',
            'grades.*.teacher_feedback' => 'nullable|string',
            'update_grade_records' => 'nullable|boolean',
        ]);

        $maxGrade = $remedialActivity->max_grade ?? 3.0;
        $graded = 0;

        DB::transaction(function () use ($request, $maxGrade, &$graded) {
            foreach ($request->grades as $gradeData) {
                $studentRemedial = StudentRemedial::find($gradeData['student_remedial_id']);
                $grade = min($gradeData['grade'], $maxGrade);

                $studentRemedial->update([
                    'status' => 'graded',
                    'grade' => $grade,
                    'teacher_feedback' => $gradeData['teacher_feedback'] ?? null,
                    'graded_at' => now(),
                    'graded_by' => auth()->id(),
                ]);

                if ($request->update_grade_records) {
                    $this->updateGradeRecord($studentRemedial, $grade);
                }

                $graded++;
            }
        });

        return response()->json([
            'message' => "Se calificaron $graded actividades",
            'graded_count' => $graded,
        ]);
    }

    /**
     * Get students needing remedial for a subject/period
     */
    public function studentsNeedingRemedial(Request $request): JsonResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'period_id' => 'required|exists:periods,id',
        ]);

        $failingRecords = GradeRecord::with(['student.user', 'student.group.grade'])
            ->where('subject_id', $request->subject_id)
            ->where('period_id', $request->period_id)
            ->where('grade', '<', 3.0)
            ->get();

        return response()->json([
            'students' => $failingRecords->map(fn($r) => [
                'student' => $r->student,
                'current_grade' => $r->grade,
                'performance_level' => 'low',
            ]),
            'count' => $failingRecords->count(),
        ]);
    }

    /**
     * Get student's remedial activities
     */
    public function studentRemedials(Student $student, Request $request): JsonResponse
    {
        $query = $student->studentRemedials()
            ->with(['remedialActivity.subject', 'remedialActivity.period']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->period_id) {
            $query->whereHas('remedialActivity', fn($q) => $q->where('period_id', $request->period_id));
        }

        $remedials = $query->get();

        return response()->json($remedials);
    }

    /**
     * Update grade record with remedial grade
     */
    private function updateGradeRecord(StudentRemedial $studentRemedial, float $grade): void
    {
        $activity = $studentRemedial->remedialActivity;

        GradeRecord::where('student_id', $studentRemedial->student_id)
            ->where('subject_id', $activity->subject_id)
            ->where('period_id', $activity->period_id)
            ->update([
                'grade' => $grade,
                'observations' => 'Nota actualizada por actividad de nivelación',
            ]);
    }
}
