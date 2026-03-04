<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityScore;
use App\Models\GradeActivity;
use App\Models\GradeRecord;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeActivityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'period_id' => 'required|exists:periods,id',
        ]);

        $activities = GradeActivity::where('subject_id', $request->subject_id)
            ->where('period_id', $request->period_id)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return response()->json($activities);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'period_id' => 'required|exists:periods,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:quiz,tarea,participacion,sustentacion,examen,proyecto,otro',
            'date' => 'nullable|date',
        ]);

        $teacher = auth()->user()->teacher;

        $activity = GradeActivity::create([
            'subject_id' => $request->subject_id,
            'period_id' => $request->period_id,
            'teacher_id' => $teacher?->id ?? 1,
            'name' => $request->name,
            'type' => $request->type,
            'weight' => 100, // will be recalculated
            'date' => $request->date,
            'order' => GradeActivity::where('subject_id', $request->subject_id)
                ->where('period_id', $request->period_id)
                ->max('order') + 1,
        ]);

        $this->redistributeWeights($request->subject_id, $request->period_id);

        return response()->json($activity->fresh(), 201);
    }

    public function update(Request $request, GradeActivity $gradeActivity): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:quiz,tarea,participacion,sustentacion,examen,proyecto,otro',
            'date' => 'nullable|date',
        ]);

        $gradeActivity->update($request->only('name', 'type', 'date'));

        return response()->json($gradeActivity);
    }

    public function destroy(GradeActivity $gradeActivity): JsonResponse
    {
        $subjectId = $gradeActivity->subject_id;
        $periodId = $gradeActivity->period_id;

        $gradeActivity->delete();

        $this->redistributeWeights($subjectId, $periodId);
        $this->recalculateFinalGrades($subjectId, $periodId);

        return response()->json(['message' => 'Actividad eliminada']);
    }

    public function scores(Request $request, GradeActivity $gradeActivity): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $students = Student::where('group_id', $request->group_id)
            ->where('status', 'active')
            ->with(['user:id,name,document_number'])
            ->orderBy('id')
            ->get();

        $existingScores = ActivityScore::where('activity_id', $gradeActivity->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        $data = $students->map(function ($student) use ($existingScores) {
            $score = $existingScores->get($student->id);

            return [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'document_number' => $student->user->document_number,
                'score' => $score ? (float) $score->score : null,
            ];
        });

        return response()->json([
            'activity' => $gradeActivity,
            'scores' => $data,
        ]);
    }

    public function bulkScores(Request $request, GradeActivity $gradeActivity): JsonResponse
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*.student_id' => 'required|exists:students,id',
            'scores.*.score' => 'nullable|numeric|min:1|max:5',
            'group_id' => 'required|exists:groups,id',
        ]);

        $studentIds = [];

        foreach ($request->scores as $item) {
            if ($item['score'] !== null && $item['score'] !== '') {
                ActivityScore::updateOrCreate(
                    ['activity_id' => $gradeActivity->id, 'student_id' => $item['student_id']],
                    ['score' => round((float) $item['score'], 1)]
                );
                $studentIds[] = $item['student_id'];
            } else {
                // Remove score if null was explicitly sent
                ActivityScore::where('activity_id', $gradeActivity->id)
                    ->where('student_id', $item['student_id'])
                    ->delete();
                $studentIds[] = $item['student_id'];
            }
        }

        $this->recalculateFinalGrades(
            $gradeActivity->subject_id,
            $gradeActivity->period_id,
            $studentIds
        );

        return response()->json(['message' => 'Scores guardados correctamente']);
    }

    // ── Private helpers ─────────────────────────────────────────────────────

    private function redistributeWeights(int $subjectId, int $periodId): void
    {
        $activities = GradeActivity::where('subject_id', $subjectId)
            ->where('period_id', $periodId)
            ->get();

        $count = $activities->count();
        if ($count === 0) {
            return;
        }

        $weight = round(100 / $count, 2);
        foreach ($activities as $activity) {
            $activity->update(['weight' => $weight]);
        }
    }

    private function recalculateFinalGrades(
        int $subjectId,
        int $periodId,
        array $studentIds = []
    ): void {
        $activities = GradeActivity::where('subject_id', $subjectId)
            ->where('period_id', $periodId)
            ->get();

        if ($activities->isEmpty()) {
            return;
        }

        $teacher = auth()->user()->teacher;
        $totalWeight = $activities->sum('weight');

        if ($studentIds) {
            $query = Student::whereIn('id', $studentIds);
        } else {
            // Recalculate for all students that have any score
            $scoredStudentIds = ActivityScore::whereIn('activity_id', $activities->pluck('id'))
                ->pluck('student_id')
                ->unique()
                ->values()
                ->all();
            $query = Student::whereIn('id', $scoredStudentIds);
        }

        $students = $query->get();

        foreach ($students as $student) {
            $scores = ActivityScore::whereIn('activity_id', $activities->pluck('id'))
                ->where('student_id', $student->id)
                ->get()
                ->keyBy('activity_id');

            $weightedSum = 0;
            $usedWeight = 0;

            foreach ($activities as $activity) {
                $score = $scores->get($activity->id);
                if ($score !== null && $score->score !== null) {
                    $weightedSum += (float) $score->score * (float) $activity->weight;
                    $usedWeight += (float) $activity->weight;
                }
            }

            if ($usedWeight > 0) {
                $finalGrade = round($weightedSum / $totalWeight, 1);

                GradeRecord::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'subject_id' => $subjectId,
                        'period_id' => $periodId,
                    ],
                    [
                        'teacher_id' => $teacher?->id ?? 1,
                        'grade' => $finalGrade,
                    ]
                );
            }
        }
    }
}
