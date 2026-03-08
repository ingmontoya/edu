<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\GradeRecord;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentPortalController extends Controller
{
    private function resolveStudent(): ?Student
    {
        return Student::where('user_id', auth()->id())->first();
    }

    /** GET /api/student/me — own student record with active year context */
    public function me(): JsonResponse
    {
        $student = $this->resolveStudent();

        if (! $student) {
            return response()->json(['message' => 'No se encontró el registro de estudiante para este usuario.'], 404);
        }

        $activeYear = AcademicYear::where('is_active', true)->first();

        $currentEnrollments = $activeYear
            ? Enrollment::where('student_id', $student->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('status', 'enrolled')
                ->with('subject')
                ->get()
            : collect();

        $totalCredits = $currentEnrollments->sum(fn ($e) => $e->subject?->credits ?? 0);

        return response()->json([
            'student' => $student->load('user'),
            'active_year' => $activeYear,
            'current_enrollments_count' => $currentEnrollments->count(),
            'current_credits' => $totalCredits,
        ]);
    }

    /** GET /api/student/enrollments — own enrollments with optional filters */
    public function enrollments(Request $request): JsonResponse
    {
        $student = $this->resolveStudent();

        if (! $student) {
            return response()->json(['message' => 'Registro de estudiante no encontrado.'], 404);
        }

        $query = Enrollment::where('student_id', $student->id)
            ->with(['subject', 'academicYear']);

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('semester_number')) {
            $query->where('semester_number', $request->semester_number);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->orderBy('semester_number')->orderBy('created_at')->get();

        return response()->json(['data' => $enrollments]);
    }

    /** GET /api/student/grades — own grade records, optionally filtered by period */
    public function grades(Request $request): JsonResponse
    {
        $student = $this->resolveStudent();

        if (! $student) {
            return response()->json(['message' => 'Registro de estudiante no encontrado.'], 404);
        }

        $query = GradeRecord::where('student_id', $student->id)
            ->with(['subject.area', 'period']);

        if ($request->filled('period_id')) {
            $query->where('period_id', $request->period_id);
        }

        $records = $query->get()->map(fn ($r) => [
            'subject' => $r->subject->name,
            'area' => $r->subject->area?->name,
            'period' => $r->period->name,
            'grade' => $r->grade,
        ]);

        return response()->json(['data' => $records]);
    }

    /** GET /api/student/kardex — grouped by academic year */
    public function kardex(): JsonResponse
    {
        $student = $this->resolveStudent();

        if (! $student) {
            return response()->json(['message' => 'Registro de estudiante no encontrado.'], 404);
        }

        $enrollments = Enrollment::where('student_id', $student->id)
            ->with(['subject', 'academicYear'])
            ->orderBy('academic_year_id')
            ->orderBy('semester_number')
            ->get();

        $byYear = $enrollments->groupBy('academic_year_id')->map(function ($yearEnrollments) {
            $year = $yearEnrollments->first()->academicYear;
            $graded = $yearEnrollments->filter(fn ($e) => $e->final_grade !== null);
            $totalGradedCredits = $graded->sum(fn ($e) => $e->subject?->credits ?? 1);
            $papa = $totalGradedCredits > 0
                ? round(
                    $graded->sum(fn ($e) => ($e->final_grade ?? 0) * ($e->subject?->credits ?? 1)) / $totalGradedCredits,
                    2
                )
                : null;

            return [
                'academic_year' => ['id' => $year->id, 'name' => $year->name],
                'enrollments' => $yearEnrollments->values(),
                'total_credits' => $yearEnrollments->sum(fn ($e) => $e->subject?->credits ?? 0),
                'approved_credits' => $yearEnrollments->where('status', 'completed')->sum(fn ($e) => $e->subject?->credits ?? 0),
                'papa' => $papa,
            ];
        })->values();

        return response()->json(['data' => $byYear]);
    }
}
