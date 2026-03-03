<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\GradeRecord;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\Period;
use App\Models\Subject;
use App\Enums\AttendanceStatus;
use App\Services\GradeCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function __construct(
        private GradeCalculatorService $calculator
    ) {}

    public function consolidation(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'period_id' => 'required|exists:periods,id',
        ]);

        $group = Group::with('grade')->findOrFail($request->group_id);
        $period = Period::findOrFail($request->period_id);

        $students = Student::where('group_id', $request->group_id)
            ->where('status', 'active')
            ->with('user')
            ->get();

        $subjects = Subject::where('grade_id', $group->grade_id)
            ->with('area')
            ->orderBy('area_id')
            ->orderBy('order')
            ->get();

        $consolidation = $students->map(function ($student) use ($subjects, $period) {
            $grades = [];
            $totalGrade = 0;
            $gradeCount = 0;

            foreach ($subjects as $subject) {
                $record = GradeRecord::where('student_id', $student->id)
                    ->where('subject_id', $subject->id)
                    ->where('period_id', $period->id)
                    ->first();

                $grade = $record?->grade;
                $grades[$subject->id] = $grade;

                if ($grade !== null) {
                    $totalGrade += $grade;
                    $gradeCount++;
                }
            }

            $average = $gradeCount > 0 ? round($totalGrade / $gradeCount, 1) : null;

            return [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'grades' => $grades,
                'average' => $average,
                'performance' => $average ? $this->calculator->getPerformanceLevel($average)->label() : null,
                'failing_count' => collect($grades)->filter(fn($g) => $g !== null && $g < 3.0)->count(),
            ];
        })->sortByDesc('average')->values();

        // Add ranking
        $consolidation = $consolidation->map(function ($item, $index) {
            $item['ranking'] = $item['average'] !== null ? $index + 1 : null;
            return $item;
        });

        return response()->json([
            'group' => $group,
            'period' => $period,
            'subjects' => $subjects,
            'consolidation' => $consolidation->sortBy('student_name')->values(),
            'summary' => [
                'total_students' => $students->count(),
                'students_with_failing' => $consolidation->filter(fn($s) => $s['failing_count'] > 0)->count(),
                'average_grade' => round($consolidation->avg('average') ?? 0, 1),
            ],
        ]);
    }

    public function failingStudents(Request $request): JsonResponse
    {
        $request->validate([
            'period_id' => 'required|exists:periods,id',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $query = GradeRecord::where('period_id', $request->period_id)
            ->where('grade', '<', 3.0)
            ->with(['student.user', 'student.group.grade', 'subject.area']);

        if ($request->group_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('group_id', $request->group_id);
            });
        }

        $failingRecords = $query->get();

        $grouped = $failingRecords->groupBy('student_id')->map(function ($records) {
            $student = $records->first()->student;
            return [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->user->name,
                    'group' => $student->group->full_name,
                ],
                'failing_subjects' => $records->map(fn($r) => [
                    'subject' => $r->subject->name,
                    'area' => $r->subject->area->name,
                    'grade' => $r->grade,
                ])->values(),
                'failing_count' => $records->count(),
            ];
        })->sortByDesc('failing_count')->values();

        return response()->json([
            'students' => $grouped,
            'summary' => [
                'total_students_failing' => $grouped->count(),
                'total_failing_records' => $failingRecords->count(),
            ],
        ]);
    }

    public function attendanceSummary(Request $request): JsonResponse
    {
        $request->validate([
            'period_id' => 'required|exists:periods,id',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $query = Attendance::where('period_id', $request->period_id);

        if ($request->group_id) {
            $query->where('group_id', $request->group_id);
        }

        $attendances = $query->get();

        $byStudent = $attendances->groupBy('student_id')->map(function ($records) {
            $student = Student::with(['user', 'group.grade'])->find($records->first()->student_id);

            $total = $records->count();
            $present = $records->where('status', AttendanceStatus::PRESENT)->count();
            $absent = $records->where('status', AttendanceStatus::ABSENT)->count();
            $late = $records->where('status', AttendanceStatus::LATE)->count();
            $excused = $records->where('status', AttendanceStatus::EXCUSED)->count();

            return [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->user->name,
                    'group' => $student->group->full_name,
                ],
                'total_days' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
                'percentage' => $total > 0 ? round(($present + $excused) / $total * 100, 1) : 100,
            ];
        })->sortBy('percentage')->values();

        return response()->json([
            'students' => $byStudent,
            'summary' => [
                'total_students' => $byStudent->count(),
                'average_attendance' => round($byStudent->avg('percentage') ?? 0, 1),
                'students_below_80' => $byStudent->filter(fn($s) => $s['percentage'] < 80)->count(),
            ],
        ]);
    }
}
