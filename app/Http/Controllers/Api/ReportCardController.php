<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Period;
use App\Models\Group;
use App\Services\ReportCardPdfService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportCardController extends Controller
{
    public function __construct(
        private ReportCardPdfService $pdfService
    ) {}

    public function show(Student $student, Period $period): JsonResponse
    {
        $student->load(['user', 'group.grade', 'group.director.user']);

        $gradeRecords = $student->gradeRecords()
            ->where('period_id', $period->id)
            ->with(['subject.area'])
            ->get();

        return response()->json([
            'student' => $student,
            'period' => $period,
            'grades' => $gradeRecords,
        ]);
    }

    public function pdf(Student $student, Period $period)
    {
        $pdf = $this->pdfService->generate($student, $period);

        $filename = sprintf(
            'boletin_%s_%s_%s.pdf',
            str_replace(' ', '_', $student->user->name),
            $period->name,
            now()->format('Ymd')
        );

        return $pdf->download($filename);
    }

    public function bulkPdf(Group $group, Period $period)
    {
        $studentIds = $group->students()
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        $pdf = $this->pdfService->generateBulk($studentIds, $period);

        $filename = sprintf(
            'boletines_%s_%s_%s.pdf',
            $group->full_name,
            $period->name,
            now()->format('Ymd')
        );

        return $pdf->download($filename);
    }
}
