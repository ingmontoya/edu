<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Period;
use App\Models\Student;
use App\Services\CertificatePdfService;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CertificateController extends Controller
{
    public function __construct(private CertificatePdfService $service) {}

    public function enrollmentPdf(Request $request, Student $student): Response
    {
        $academicYearId = $request->academic_year_id;

        if ($academicYearId) {
            $academicYear = AcademicYear::findOrFail($academicYearId);
        } else {
            $institution = TenantService::getInstitution();
            $academicYear = $institution->activeYear();

            if (! $academicYear) {
                $academicYear = AcademicYear::orderByDesc('year')->firstOrFail();
            }
        }

        $pdf = $this->service->generateEnrollment($student, $academicYear);
        $filename = 'constancia_matricula_'.$student->id.'_'.now()->format('Ymd').'.pdf';

        return $pdf->download($filename);
    }

    public function gradesPdf(Request $request, Student $student): Response
    {
        $period = null;

        if ($request->period_id) {
            $period = Period::findOrFail($request->period_id);
        }

        if ($request->academic_year_id) {
            $academicYear = AcademicYear::findOrFail($request->academic_year_id);
        } else {
            $institution = TenantService::getInstitution();
            $academicYear = $institution->activeYear();

            if (! $academicYear) {
                $academicYear = AcademicYear::orderByDesc('year')->firstOrFail();
            }
        }

        $pdf = $this->service->generateGrades($student, $academicYear, $period);
        $filename = 'constancia_notas_'.$student->id.'_'.now()->format('Ymd').'.pdf';

        return $pdf->download($filename);
    }
}
