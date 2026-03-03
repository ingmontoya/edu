<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Period;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Services\ReportCardPdfService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PortalController extends Controller
{
    public function __construct(
        private ReportCardPdfService $pdfService
    ) {}

    public function students(): JsonResponse
    {
        $guardian = auth()->user()->guardian;

        if (!$guardian) {
            return response()->json(['message' => 'No es un acudiente registrado'], 403);
        }

        $students = $guardian->students()
            ->with(['user', 'group.grade'])
            ->get();

        return response()->json($students);
    }

    /**
     * Get student detail with periods for the guardian portal
     */
    public function studentDetail(Student $student): JsonResponse
    {
        $guardian = auth()->user()->guardian;

        if (!$guardian || !$guardian->students->contains($student)) {
            return response()->json(['message' => 'No tiene acceso a este estudiante'], 403);
        }

        $student->load(['user', 'group.grade']);

        // Get active academic year and its periods
        $activeYear = AcademicYear::where('is_active', true)->first();
        $periods = $activeYear ? $activeYear->periods()->orderBy('start_date')->get() : collect();
        // Active period is the first one that is not closed
        $activePeriod = $periods->where('is_closed', false)->first();

        return response()->json([
            'student' => $student,
            'periods' => $periods,
            'active_period' => $activePeriod
        ]);
    }

    /**
     * Get student grades for a specific period
     */
    public function grades(Request $request, Student $student): JsonResponse
    {
        $guardian = auth()->user()->guardian;

        if (!$guardian || !$guardian->students->contains($student)) {
            return response()->json(['message' => 'No tiene acceso a este estudiante'], 403);
        }

        $periodId = $request->query('period_id');

        $query = $student->gradeRecords()
            ->with(['subject.area']);

        if ($periodId) {
            $query->where('period_id', $periodId);
        }

        $gradeRecords = $query->get();

        // Format for the frontend
        $grades = $gradeRecords->map(function ($record) {
            return [
                'subject_id' => $record->subject_id,
                'subject_name' => $record->subject->name,
                'area_name' => $record->subject->area?->name,
                'grade' => $record->grade,
                'observations' => $record->observations
            ];
        });

        return response()->json($grades);
    }

    /**
     * Get student attendance summary for a specific period
     */
    public function attendance(Request $request, Student $student): JsonResponse
    {
        $guardian = auth()->user()->guardian;

        if (!$guardian || !$guardian->students->contains($student)) {
            return response()->json(['message' => 'No tiene acceso a este estudiante'], 403);
        }

        $periodId = $request->query('period_id');

        $query = $student->attendances();

        if ($periodId) {
            $query->where('period_id', $periodId);
        }

        $attendances = $query->get();

        // Calculate summary
        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'excused' => $attendances->where('status', 'excused')->count(),
            'total' => $attendances->count()
        ];

        return response()->json($summary);
    }

    public function reportCardPdf(Student $student, Period $period)
    {
        // Verify guardian has access to this student
        $guardian = auth()->user()->guardian;

        if (!$guardian || !$guardian->students->contains($student)) {
            return response()->json(['message' => 'No tiene acceso a este estudiante'], 403);
        }

        $pdf = $this->pdfService->generate($student, $period);

        $filename = sprintf(
            'boletin_%s_%s_%s.pdf',
            str_replace(' ', '_', $student->user->name),
            $period->name,
            now()->format('Ymd')
        );

        return $pdf->download($filename);
    }

    public function announcements(): JsonResponse
    {
        // BelongsToTenant trait automatically filters by current institution
        $announcements = Announcement::published()
            ->with('author')
            ->orderByDesc('published_at')
            ->limit(20)
            ->get();

        return response()->json($announcements);
    }
}
