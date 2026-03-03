<?php

namespace App\Http\Controllers\Api;

use App\Enums\AttendanceStatus;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\DisciplinaryRecord;
use App\Models\GradeRecord;
use App\Models\Group;
use App\Models\Period;
use App\Models\Student;
use App\Models\StudentAchievement;
use App\Models\StudentAiAnalysis;
use App\Models\StudentRemedial;
use App\Models\Subject;
use App\Services\ClaudeInsightService;
use App\Services\GradeCalculatorService;
use App\Services\RiskScoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private GradeCalculatorService $calculator,
        private RiskScoreService $riskScorer
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
                'failing_count' => collect($grades)->filter(fn ($g) => $g !== null && $g < 3.0)->count(),
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
                'students_with_failing' => $consolidation->filter(fn ($s) => $s['failing_count'] > 0)->count(),
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
                'failing_subjects' => $records->map(fn ($r) => [
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

    public function riskScores(Request $request): JsonResponse
    {
        $request->validate([
            'period_id' => 'required|exists:periods,id',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $period = Period::findOrFail($request->period_id);
        $scores = $this->riskScorer->calculate($period, $request->group_id ? (int) $request->group_id : null);

        $high = $scores->filter(fn ($s) => $s['level'] === 'high');
        $medium = $scores->filter(fn ($s) => $s['level'] === 'medium');
        $low = $scores->filter(fn ($s) => $s['level'] === 'low');

        return response()->json([
            'students' => $scores,
            'summary' => [
                'total' => $scores->count(),
                'high_risk' => $high->count(),
                'medium_risk' => $medium->count(),
                'low_risk' => $low->count(),
                'average_score' => $scores->isEmpty() ? 0 : round($scores->avg('score'), 1),
            ],
        ]);
    }

    public function aiStudentAnalysis(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'period_id' => 'required|exists:periods,id',
        ]);

        $student = Student::with(['user', 'group.grade'])->findOrFail($request->student_id);
        $period = Period::findOrFail($request->period_id);

        // --- Risk score signals ---
        $scores = $this->riskScorer->calculate($period);
        $riskData = $scores->firstWhere('student_id', $student->id);

        // --- All grade records for this student/period, with teacher observations ---
        $gradeRecords = GradeRecord::where('student_id', $student->id)
            ->where('period_id', $period->id)
            ->with('subject.area')
            ->get()
            ->map(fn ($r) => [
                'subject' => $r->subject->name,
                'area' => $r->subject->area->name,
                'grade' => $r->grade !== null ? (float) $r->grade : null,
                'performance' => $r->performance_label,
                'observations' => $r->observations,
                'recommendations' => $r->recommendations,
            ])
            ->values()
            ->toArray();

        // --- Achievements: group by subject, report status counts ---
        $achievements = StudentAchievement::where('student_id', $student->id)
            ->whereHas('achievement', fn ($q) => $q->where('period_id', $period->id))
            ->with('achievement.subject')
            ->get()
            ->groupBy(fn ($sa) => $sa->achievement->subject->name ?? 'General')
            ->map(fn ($records, $subject) => [
                'subject' => $subject,
                'achieved' => $records->where('status', 'achieved')->count(),
                'not_achieved' => $records->where('status', 'not_achieved')->count(),
                'in_progress' => $records->where('status', 'in_progress')->count(),
                'pending' => $records->where('status', 'pending')->count(),
                'total' => $records->count(),
                'observations' => $records->whereNotNull('observations')->pluck('observations')->filter()->first(),
            ])
            ->values()
            ->toArray();

        // --- Attendance detail ---
        $attendances = Attendance::where('student_id', $student->id)
            ->where('period_id', $period->id)
            ->get();

        $totalDays = $attendances->count();
        $presentDays = $attendances->whereIn('status', [AttendanceStatus::PRESENT, AttendanceStatus::EXCUSED])->count();
        $absentDays = $attendances->where('status', AttendanceStatus::ABSENT)->count();
        $lateDays = $attendances->where('status', AttendanceStatus::LATE)->count();

        // --- Pending remedials with subject info ---
        $pendingRemedials = StudentRemedial::where('student_id', $student->id)
            ->whereNull('grade')
            ->with('remedialActivity.subject')
            ->get()
            ->map(fn ($sr) => [
                'subject' => $sr->remedialActivity->subject->name ?? 'N/A',
                'title' => $sr->remedialActivity->title,
                'due_date' => $sr->remedialActivity->due_date,
            ])
            ->values()
            ->toArray();

        // --- Recent disciplinary incidents ---
        $disciplinary = DisciplinaryRecord::where('student_id', $student->id)
            ->whereIn('type', ['type2', 'type3'])
            ->where('date', '>=', now()->subDays(30))
            ->get()
            ->map(fn ($d) => [
                'type' => $d->type,
                'category' => $d->category,
                'status' => $d->status,
                'date' => $d->date,
            ])
            ->values()
            ->toArray();

        $payload = [
            'student' => [
                'name' => $student->user->name,
                'group' => $student->group?->full_name ?? '-',
            ],
            'period' => $period->name,
            'risk' => $riskData ? [
                'score' => $riskData['score'],
                'level' => $riskData['level'],
            ] : null,
            'grades' => $gradeRecords,
            'achievements' => $achievements,
            'attendance' => [
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'late_days' => $lateDays,
                'percentage' => $totalDays > 0 ? round($presentDays / $totalDays * 100, 1) : null,
            ],
            'pending_remedials' => $pendingRemedials,
            'disciplinary_incidents' => $disciplinary,
        ];

        try {
            $service = new ClaudeInsightService;
            $result = $service->studentAnalysis($payload);

            StudentAiAnalysis::create([
                'student_id' => $student->id,
                'period_id' => $period->id,
                'risk_level' => $riskData['level'] ?? 'low',
                'risk_score' => $riskData['score'] ?? 0,
                'narrative' => $result['narrative'],
                'recommendations' => $result['recommendations'],
                'generated_by' => auth()->id(),
            ]);

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al contactar el servicio de IA: '.$e->getMessage()], 502);
        }
    }

    public function studentAiAnalyses(Student $student): JsonResponse
    {
        $analyses = StudentAiAnalysis::where('student_id', $student->id)
            ->with('period:id,name')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'period' => ['id' => $a->period->id, 'name' => $a->period->name],
                'risk_level' => $a->risk_level,
                'risk_score' => (float) $a->risk_score,
                'narrative' => $a->narrative,
                'recommendations' => $a->recommendations,
                'created_at' => $a->created_at->toIso8601String(),
            ]);

        $evolution = null;
        if ($analyses->count() >= 2) {
            $current = $analyses->first();
            $previous = $analyses->skip(1)->first();
            $evolution = [
                'score_delta' => round($current['risk_score'] - $previous['risk_score'], 1),
                'level_changed' => $current['risk_level'] !== $previous['risk_level'],
                'previous_level' => $previous['risk_level'],
                'current_level' => $current['risk_level'],
            ];
        }

        return response()->json([
            'analyses' => $analyses->values(),
            'evolution' => $evolution,
        ]);
    }

    public function aiWeeklySummary(Request $request): JsonResponse
    {
        $request->validate([
            'period_id' => 'required|exists:periods,id',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $period = Period::findOrFail($request->period_id);
        $scores = $this->riskScorer->calculate($period, $request->group_id ? (int) $request->group_id : null);

        if ($scores->isEmpty()) {
            return response()->json(['message' => 'No hay datos de estudiantes para este período'], 404);
        }

        // Aggregate data for the summary prompt
        $topRiskGroups = $scores
            ->filter(fn ($s) => $s['level'] === 'high')
            ->groupBy(fn ($s) => $s['student']['group'])
            ->sortByDesc(fn ($g) => $g->count())
            ->keys()
            ->take(3)
            ->toArray();

        // Top failing subjects from period
        $topFailingSubjects = GradeRecord::where('period_id', $period->id)
            ->where('grade', '<', 3.0)
            ->with('subject')
            ->get()
            ->groupBy('subject_id')
            ->map(fn ($records, $subjectId) => [
                'subject' => $records->first()->subject->name,
                'count' => $records->count(),
            ])
            ->sortByDesc('count')
            ->take(3)
            ->pluck('subject')
            ->toArray();

        $schoolData = [
            'total_students' => $scores->count(),
            'high_risk' => $scores->filter(fn ($s) => $s['level'] === 'high')->count(),
            'medium_risk' => $scores->filter(fn ($s) => $s['level'] === 'medium')->count(),
            'average_score' => round($scores->avg('score'), 1),
            'top_risk_groups' => $topRiskGroups,
            'top_failing_subjects' => $topFailingSubjects,
        ];

        try {
            $service = new ClaudeInsightService;
            $summary = $service->weeklyExecutiveSummary($schoolData);

            return response()->json(['summary' => $summary]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al contactar el servicio de IA: '.$e->getMessage()], 502);
        }
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
                'students_below_80' => $byStudent->filter(fn ($s) => $s['percentage'] < 80)->count(),
            ],
        ]);
    }
}
