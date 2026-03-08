<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Schedule::with([
            'assignment.teacher.user',
            'assignment.subject',
            'assignment.group.grade',
        ]);

        if ($request->group_id) {
            $query->whereHas('assignment', fn ($q) => $q->where('group_id', $request->group_id));
        }

        if ($request->teacher_id) {
            $query->whereHas('assignment', fn ($q) => $q->where('teacher_id', $request->teacher_id));
        }

        if ($request->academic_year_id) {
            $query->whereHas('assignment', fn ($q) => $q->where('academic_year_id', $request->academic_year_id));
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();

        return response()->json(['data' => $schedules]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'teacher_assignment_id' => 'required|exists:teacher_assignments,id',
            'day_of_week' => 'required|integer|min:1|max:5',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'classroom' => 'nullable|string|max:100',
        ]);

        $assignment = TeacherAssignment::findOrFail($data['teacher_assignment_id']);

        if ($conflict = $this->findTeacherConflict($assignment->teacher_id, $data['day_of_week'], $data['start_time'], $data['end_time'])) {
            return response()->json([
                'message' => 'El docente ya tiene una clase asignada en ese horario.',
                'errors' => ['time' => ['Conflicto de horario con docente']],
            ], 422);
        }

        if ($conflict = $this->findGroupConflict($assignment->group_id, $data['day_of_week'], $data['start_time'], $data['end_time'])) {
            return response()->json([
                'message' => 'El grupo ya tiene una clase asignada en ese horario.',
                'errors' => ['time' => ['Conflicto de horario con grupo']],
            ], 422);
        }

        $schedule = Schedule::create($data);

        return response()->json(
            $schedule->load(['assignment.teacher.user', 'assignment.subject', 'assignment.group.grade']),
            201
        );
    }

    public function update(Request $request, Schedule $schedule): JsonResponse
    {
        $data = $request->validate([
            'teacher_assignment_id' => 'sometimes|exists:teacher_assignments,id',
            'day_of_week' => 'sometimes|integer|min:1|max:5',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'classroom' => 'nullable|string|max:100',
        ]);

        $assignmentId = $data['teacher_assignment_id'] ?? $schedule->teacher_assignment_id;
        $assignment = TeacherAssignment::findOrFail($assignmentId);
        $day = $data['day_of_week'] ?? $schedule->day_of_week;
        $start = $data['start_time'] ?? $schedule->start_time;
        $end = $data['end_time'] ?? $schedule->end_time;

        if ($this->findTeacherConflict($assignment->teacher_id, $day, $start, $end, $schedule->id)) {
            return response()->json([
                'message' => 'El docente ya tiene una clase asignada en ese horario.',
                'errors' => ['time' => ['Conflicto de horario con docente']],
            ], 422);
        }

        if ($this->findGroupConflict($assignment->group_id, $day, $start, $end, $schedule->id)) {
            return response()->json([
                'message' => 'El grupo ya tiene una clase asignada en ese horario.',
                'errors' => ['time' => ['Conflicto de horario con grupo']],
            ], 422);
        }

        $schedule->update($data);

        return response()->json(
            $schedule->load(['assignment.teacher.user', 'assignment.subject', 'assignment.group.grade'])
        );
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $schedule->delete();

        return response()->json(['message' => 'Bloque de horario eliminado']);
    }

    public function groupAssignments(Request $request, Group $group): JsonResponse
    {
        $query = TeacherAssignment::with(['teacher.user', 'subject'])
            ->where('group_id', $group->id);

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        return response()->json(['data' => $query->orderBy('id')->get()]);
    }

    public function groupSchedule(Request $request, Group $group): JsonResponse
    {
        $query = Schedule::with([
            'assignment.teacher.user',
            'assignment.subject',
            'assignment.group.grade',
        ])->whereHas('assignment', fn ($q) => $q->where('group_id', $group->id));

        if ($request->academic_year_id) {
            $query->whereHas('assignment', fn ($q) => $q->where('academic_year_id', $request->academic_year_id));
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();

        return response()->json(['data' => $schedules]);
    }

    public function teacherSchedule(Request $request, Teacher $teacher): JsonResponse
    {
        $query = Schedule::with([
            'assignment.teacher.user',
            'assignment.subject',
            'assignment.group.grade',
        ])->whereHas('assignment', fn ($q) => $q->where('teacher_id', $teacher->id));

        if ($request->academic_year_id) {
            $query->whereHas('assignment', fn ($q) => $q->where('academic_year_id', $request->academic_year_id));
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();

        return response()->json(['data' => $schedules]);
    }

    private function findTeacherConflict(int $teacherId, int $day, string $start, string $end, ?int $excludeId = null): bool
    {
        return Schedule::whereHas('assignment', fn ($q) => $q->where('teacher_id', $teacherId))
            ->where('day_of_week', $day)
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    private function findGroupConflict(int $groupId, int $day, string $start, string $end, ?int $excludeId = null): bool
    {
        return Schedule::whereHas('assignment', fn ($q) => $q->where('group_id', $groupId))
            ->where('day_of_week', $day)
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }
}
