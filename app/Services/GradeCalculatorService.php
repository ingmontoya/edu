<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Period;
use App\Enums\PerformanceLevel;

class GradeCalculatorService
{
    /**
     * Calcula el promedio de un estudiante en un período
     */
    public function calculatePeriodAverage(Student $student, Period $period): ?float
    {
        $grades = $student->gradeRecords()
            ->where('period_id', $period->id)
            ->whereNotNull('grade')
            ->pluck('grade');

        if ($grades->isEmpty()) {
            return null;
        }

        return round($grades->avg(), 1);
    }

    /**
     * Calcula la nota final ponderada de una asignatura
     */
    public function calculateFinalGrade(Student $student, int $subjectId, int $academicYearId): ?float
    {
        $periods = Period::where('academic_year_id', $academicYearId)->get();

        $weightedSum = 0;
        $totalWeight = 0;

        foreach ($periods as $period) {
            $gradeRecord = $student->gradeRecords()
                ->where('subject_id', $subjectId)
                ->where('period_id', $period->id)
                ->first();

            if ($gradeRecord && $gradeRecord->grade !== null) {
                $weightedSum += $gradeRecord->grade * $period->weight;
                $totalWeight += $period->weight;
            }
        }

        if ($totalWeight === 0) {
            return null;
        }

        return round($weightedSum / $totalWeight, 1);
    }

    /**
     * Calcula el puesto del estudiante en el grupo
     */
    public function calculateRanking(Student $student, Period $period): int
    {
        $groupStudents = Student::where('group_id', $student->group_id)
            ->where('status', 'active')
            ->get();

        $averages = $groupStudents->map(function ($s) use ($period) {
            return [
                'student_id' => $s->id,
                'average' => $this->calculatePeriodAverage($s, $period) ?? 0,
            ];
        })->sortByDesc('average')->values();

        $position = $averages->search(fn($item) => $item['student_id'] === $student->id);

        return $position !== false ? $position + 1 : 0;
    }

    /**
     * Obtiene el nivel de desempeño
     */
    public function getPerformanceLevel(float $grade): PerformanceLevel
    {
        return PerformanceLevel::fromGrade($grade);
    }

    /**
     * Verifica si un estudiante aprueba una asignatura
     */
    public function isPassingGrade(float $grade, float $passingGrade = 3.0): bool
    {
        return $grade >= $passingGrade;
    }
}
