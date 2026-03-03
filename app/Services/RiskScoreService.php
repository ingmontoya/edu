<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\DisciplinaryRecord;
use App\Models\GradeRecord;
use App\Models\Period;
use App\Models\Student;
use App\Models\StudentRemedial;
use Illuminate\Support\Collection;

class RiskScoreService
{
    /**
     * Calculate risk scores for all active students in a group (or all groups).
     *
     * Formula (0–100 scale):
     *   35% – Failing subjects in current period
     *   25% – Attendance percentage < 80%
     *   20% – Disciplinary records type2/type3 in last 30 days
     *   15% – Pending remedial activities
     *    5% – Negative grade trend vs previous period
     */
    public function calculate(Period $period, ?int $groupId = null): Collection
    {
        $query = Student::where('status', 'active')
            ->with(['user', 'group.grade']);

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        $students = $query->get();

        if ($students->isEmpty()) {
            return collect();
        }

        $studentIds = $students->pluck('id');

        // Preload all data to avoid N+1
        $gradeRecords = GradeRecord::whereIn('student_id', $studentIds)
            ->where('period_id', $period->id)
            ->get()
            ->groupBy('student_id');

        $attendances = Attendance::whereIn('student_id', $studentIds)
            ->where('period_id', $period->id)
            ->get()
            ->groupBy('student_id');

        $disciplinary = DisciplinaryRecord::whereIn('student_id', $studentIds)
            ->whereIn('type', ['type2', 'type3'])
            ->where('date', '>=', now()->subDays(30))
            ->get()
            ->groupBy('student_id');

        $pendingRemedials = StudentRemedial::whereIn('student_id', $studentIds)
            ->whereNull('grade')
            ->get()
            ->groupBy('student_id');

        // Previous period for trend
        $previousPeriod = Period::where('academic_year_id', $period->academic_year_id)
            ->where('number', '<', $period->number)
            ->orderByDesc('number')
            ->first();

        $previousGrades = collect();
        if ($previousPeriod) {
            $previousGrades = GradeRecord::whereIn('student_id', $studentIds)
                ->where('period_id', $previousPeriod->id)
                ->get()
                ->groupBy('student_id');
        }

        return $students->map(function (Student $student) use (
            $gradeRecords, $attendances, $disciplinary, $pendingRemedials,
            $previousGrades
        ) {
            $studentGrades = $gradeRecords->get($student->id, collect());
            $studentAttendance = $attendances->get($student->id, collect());
            $studentDisciplinary = $disciplinary->get($student->id, collect());
            $studentRemedials = $pendingRemedials->get($student->id, collect());
            $studentPrevGrades = $previousGrades->get($student->id, collect());

            // Signal 1 – Failing subjects (35%)
            $totalSubjects = $studentGrades->count();
            $failingSubjects = $studentGrades->filter(fn ($r) => $r->grade !== null && $r->grade < 3.0)->count();
            $failingRatio = $totalSubjects > 0 ? $failingSubjects / $totalSubjects : 0;
            $signal1 = $failingRatio * 100 * 0.35;

            // Signal 2 – Low attendance (25%)
            $totalDays = $studentAttendance->count();
            if ($totalDays > 0) {
                $presentDays = $studentAttendance->filter(
                    fn ($a) => $a->status === AttendanceStatus::PRESENT || $a->status === AttendanceStatus::EXCUSED
                )->count();
                $attendancePct = ($presentDays / $totalDays) * 100;
            } else {
                $attendancePct = 100; // No data → assume present
            }
            // Score increases linearly from 0 at 80% to 100 at 0%
            $signal2 = $attendancePct < 80 ? ((80 - $attendancePct) / 80) * 100 * 0.25 : 0;

            // Signal 3 – Serious disciplinary incidents last 30 days (20%)
            $disciplinaryCount = $studentDisciplinary->count();
            // Cap at 3 incidents for max score
            $signal3 = min($disciplinaryCount / 3, 1) * 100 * 0.20;

            // Signal 4 – Pending remedials (15%)
            $pendingCount = $studentRemedials->count();
            // Cap at 4 for max score
            $signal4 = min($pendingCount / 4, 1) * 100 * 0.15;

            // Signal 5 – Negative trend vs previous period (5%)
            $signal5 = 0;
            if ($studentPrevGrades->isNotEmpty() && $studentGrades->isNotEmpty()) {
                $currentAvg = $studentGrades->whereNotNull('grade')->avg('grade') ?? 0;
                $prevAvg = $studentPrevGrades->whereNotNull('grade')->avg('grade') ?? 0;
                if ($prevAvg > 0 && $currentAvg < $prevAvg) {
                    // Normalize drop: 1.0 point drop = 50 score
                    $drop = $prevAvg - $currentAvg;
                    $signal5 = min($drop / 2, 1) * 100 * 0.05;
                }
            }

            $score = (int) round($signal1 + $signal2 + $signal3 + $signal4 + $signal5);
            $score = max(0, min(100, $score));

            return [
                'student_id' => $student->id,
                'student' => [
                    'id' => $student->id,
                    'name' => $student->user->name,
                    'group' => $student->group?->full_name ?? '-',
                ],
                'score' => $score,
                'level' => $this->level($score),
                'signals' => [
                    'failing_subjects' => $failingSubjects,
                    'total_subjects' => $totalSubjects,
                    'attendance_pct' => round($attendancePct, 1),
                    'disciplinary_incidents' => $disciplinaryCount,
                    'pending_remedials' => $pendingCount,
                    'grade_trend' => $studentPrevGrades->isNotEmpty() && $studentGrades->isNotEmpty()
                        ? round(
                            ($studentGrades->whereNotNull('grade')->avg('grade') ?? 0)
                            - ($studentPrevGrades->whereNotNull('grade')->avg('grade') ?? 0),
                            2
                        )
                        : null,
                ],
            ];
        })->sortByDesc('score')->values();
    }

    private function level(int $score): string
    {
        if ($score >= 61) {
            return 'high';
        }
        if ($score >= 31) {
            return 'medium';
        }

        return 'low';
    }
}
