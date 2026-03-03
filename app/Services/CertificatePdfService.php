<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Period;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificatePdfService
{
    public function generateEnrollment(Student $student, AcademicYear $academicYear): \Barryvdh\DomPDF\PDF
    {
        $student->load(['user', 'group.grade']);
        $institution = TenantService::getInstitution();

        return Pdf::loadView('pdf.certificate-enrollment', [
            'institution' => $institution,
            'student' => $student,
            'academicYear' => $academicYear,
            'generatedAt' => now()->format('d/m/Y'),
            'folio' => 'CE-'.str_pad($student->id, 6, '0', STR_PAD_LEFT).'-'.now()->format('Y'),
        ])->setPaper('letter', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);
    }

    public function generateGrades(Student $student, AcademicYear $academicYear, ?Period $period = null): \Barryvdh\DomPDF\PDF
    {
        $student->load(['user', 'group.grade']);
        $institution = TenantService::getInstitution();

        $gradeRecordsQuery = $student->gradeRecords()->with(['subject.area', 'period']);

        if ($period) {
            $gradeRecordsQuery->where('period_id', $period->id);
        } else {
            $periodIds = $academicYear->periods()->pluck('id');
            $gradeRecordsQuery->whereIn('period_id', $periodIds);
        }

        $gradeRecords = $gradeRecordsQuery->get();

        // Group by period then subject
        $byPeriod = $gradeRecords->groupBy('period.name');

        // Overall average
        $grades = $gradeRecords->whereNotNull('grade')->pluck('grade');
        $overallAverage = $grades->isNotEmpty() ? round($grades->avg(), 1) : null;

        return Pdf::loadView('pdf.certificate-grades', [
            'institution' => $institution,
            'student' => $student,
            'academicYear' => $academicYear,
            'period' => $period,
            'gradeRecords' => $gradeRecords,
            'byPeriod' => $byPeriod,
            'overallAverage' => $overallAverage,
            'generatedAt' => now()->format('d/m/Y'),
            'folio' => 'CN-'.str_pad($student->id, 6, '0', STR_PAD_LEFT).'-'.now()->format('Y'),
        ])->setPaper('letter', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);
    }
}
