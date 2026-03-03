<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Period;
use App\Models\Attendance;
use App\Models\Achievement;
use App\Enums\AttendanceStatus;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardPdfService
{
    public function __construct(
        private GradeCalculatorService $calculator
    ) {}

    public function generate(Student $student, Period $period): \Barryvdh\DomPDF\PDF
    {
        $data = $this->buildReportCardData($student, $period);

        return Pdf::loadView('pdf.report-card', $data)
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);
    }

    public function generateBulk(array $studentIds, Period $period): \Barryvdh\DomPDF\PDF
    {
        $students = Student::whereIn('id', $studentIds)->get();

        $allData = $students->map(fn($student) => $this->buildReportCardData($student, $period));

        return Pdf::loadView('pdf.report-card-bulk', ['reports' => $allData])
            ->setPaper('letter', 'portrait');
    }

    private function buildReportCardData(Student $student, Period $period): array
    {
        $student->load(['user', 'group.grade', 'group.director.user']);
        $institution = TenantService::getInstitution();

        // Notas con asignaciones de docentes
        $allRecords = $student->gradeRecords()
            ->where('period_id', $period->id)
            ->with(['subject.area', 'subject.teacherAssignments.teacher.user'])
            ->get();

        // Notas agrupadas por área
        $gradeRecords = $allRecords
            ->filter(fn($r) => $r->subject->area_id !== null)
            ->groupBy('subject.area.name');

        // Asignaturas sin área (standalone)
        $standaloneSubjects = $allRecords
            ->filter(fn($r) => $r->subject->area_id === null);

        // Total de asignaturas
        $totalSubjects = $allRecords->count();

        // Calcular promedios
        $grades = $allRecords->whereNotNull('grade')->pluck('grade');
        $periodAverage = $grades->isNotEmpty() ? round($grades->avg(), 1) : null;

        // Puesto
        $ranking = $this->calculator->calculateRanking($student, $period);
        $totalStudents = Student::where('group_id', $student->group_id)
            ->where('status', 'active')
            ->count();

        // Asistencia
        $attendanceData = $this->getAttendanceData($student, $period);

        // Asignaturas perdidas
        $failingSubjects = $allRecords->filter(fn($r) => $r->grade < 3.0);

        // Logros por asignatura (para mostrar como "Tema")
        $subjectIds = $allRecords->pluck('subject_id')->unique();
        $achievements = Achievement::whereIn('subject_id', $subjectIds)
            ->where('period_id', $period->id)
            ->get()
            ->groupBy('subject_id');

        return [
            'institution' => $institution,
            'student' => $student,
            'period' => $period,
            'group' => $student->group,
            'grade' => $student->group->grade,
            'director' => $student->group->director,
            'gradesByArea' => $gradeRecords,
            'standaloneSubjects' => $standaloneSubjects,
            'totalSubjects' => $totalSubjects,
            'periodAverage' => $periodAverage,
            'performanceLevel' => $periodAverage
                ? $this->calculator->getPerformanceLevel($periodAverage)->label()
                : null,
            'ranking' => $ranking,
            'totalStudents' => $totalStudents,
            'attendance' => $attendanceData,
            'failingSubjects' => $failingSubjects,
            'achievements' => $achievements,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ];
    }

    private function getAttendanceData(Student $student, Period $period): array
    {
        $attendances = Attendance::where('student_id', $student->id)
            ->where('period_id', $period->id)
            ->get();

        $total = $attendances->count();
        $present = $attendances->where('status', AttendanceStatus::PRESENT)->count();
        $absent = $attendances->where('status', AttendanceStatus::ABSENT)->count();
        $late = $attendances->where('status', AttendanceStatus::LATE)->count();
        $excused = $attendances->where('status', AttendanceStatus::EXCUSED)->count();

        return [
            'total_days' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'percentage' => $total > 0 ? round(($present + $excused) / $total * 100, 1) : 100,
        ];
    }
}
