<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GradeRecord;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Period;
use App\Services\GradeCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GradeRecordController extends Controller
{
    public function __construct(
        private GradeCalculatorService $calculator
    ) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'period_id' => 'required|exists:periods,id',
        ]);

        $students = Student::where('group_id', $request->group_id)
            ->where('status', 'active')
            ->with(['user:id,name,document_number'])
            ->orderBy('id')
            ->get();

        $existingRecords = GradeRecord::where('subject_id', $request->subject_id)
            ->where('period_id', $request->period_id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        $data = $students->map(function ($student) use ($existingRecords) {
            $record = $existingRecords->get($student->id);
            return [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'document_number' => $student->user->document_number,
                'record_id' => $record?->id,
                'grade' => $record?->grade,
                'performance_level' => $record?->performance_level,
                'performance_label' => $record?->performance_label,
                'observations' => $record?->observations,
                'recommendations' => $record?->recommendations,
            ];
        });

        return response()->json($data);
    }

    public function bulkStore(Request $request): JsonResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'period_id' => 'required|exists:periods,id',
            'records' => 'required|array',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.grade' => 'nullable|numeric|min:1|max:5',
            'records.*.observations' => 'nullable|string|max:1000',
            'records.*.recommendations' => 'nullable|string|max:1000',
        ]);

        $teacher = auth()->user()->teacher;

        if (!$teacher && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $saved = [];

        foreach ($request->records as $record) {
            if ($record['grade'] !== null && $record['grade'] !== '') {
                $gradeRecord = GradeRecord::updateOrCreate(
                    [
                        'student_id' => $record['student_id'],
                        'subject_id' => $request->subject_id,
                        'period_id' => $request->period_id,
                    ],
                    [
                        'teacher_id' => $teacher?->id ?? 1,
                        'grade' => round($record['grade'], 1),
                        'observations' => $record['observations'] ?? null,
                        'recommendations' => $record['recommendations'] ?? null,
                    ]
                );
                $saved[] = $gradeRecord;
            }
        }

        return response()->json([
            'message' => 'Notas guardadas correctamente',
            'count' => count($saved),
        ]);
    }

    public function update(Request $request, GradeRecord $gradeRecord): JsonResponse
    {
        $request->validate([
            'grade' => 'nullable|numeric|min:1|max:5',
            'observations' => 'nullable|string|max:1000',
            'recommendations' => 'nullable|string|max:1000',
        ]);

        $gradeRecord->update([
            'grade' => $request->grade ? round($request->grade, 1) : null,
            'observations' => $request->observations,
            'recommendations' => $request->recommendations,
        ]);

        return response()->json($gradeRecord);
    }

    public function worksheet(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'period_id' => 'required|exists:periods,id',
        ]);

        $group = Group::with('grade')->findOrFail($request->group_id);
        $period = Period::findOrFail($request->period_id);

        $students = Student::where('group_id', $request->group_id)
            ->where('status', 'active')
            ->with(['user:id,name'])
            ->get();

        $subjects = Subject::where('grade_id', $group->grade_id)
            ->with('area')
            ->orderBy('area_id')
            ->orderBy('order')
            ->get();

        $allRecords = GradeRecord::where('period_id', $request->period_id)
            ->whereIn('student_id', $students->pluck('id'))
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get()
            ->groupBy('student_id');

        $worksheet = $students->map(function ($student) use ($subjects, $allRecords) {
            $studentRecords = $allRecords->get($student->id, collect())->keyBy('subject_id');

            // Grades como objeto keyed por subject_id
            $grades = [];
            $validGrades = [];
            foreach ($subjects as $subject) {
                $grade = $studentRecords->get($subject->id)?->grade;
                $grades[$subject->id] = $grade;
                if ($grade !== null) {
                    $validGrades[] = $grade;
                }
            }

            $average = count($validGrades) > 0 ? round(array_sum($validGrades) / count($validGrades), 1) : null;

            return [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'grades' => $grades,
                'average' => $average,
                'performance' => $average ? $this->calculator->getPerformanceLevel($average)->label() : null,
            ];
        })->sortByDesc('average')->values();

        // Calcular ranking
        $worksheet = $worksheet->map(function ($item, $index) {
            $item['ranking'] = $item['average'] !== null ? $index + 1 : null;
            return $item;
        });

        return response()->json([
            'group' => $group,
            'period' => $period,
            'subjects' => $subjects,
            'worksheet' => $worksheet->sortBy('student_name')->values(),
        ]);
    }

    public function byStudent(Student $student, Request $request): JsonResponse
    {
        $query = $student->gradeRecords()->with(['subject.area', 'period']);

        if ($request->period_id) {
            $query->where('period_id', $request->period_id);
        }

        $records = $query->get()->groupBy('period_id');

        return response()->json($records);
    }
}
