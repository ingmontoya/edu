<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Group;
use App\Enums\AttendanceStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'date' => 'required|date',
        ]);

        $students = Student::where('group_id', $request->group_id)
            ->where('status', 'active')
            ->with(['user:id,name,document_number'])
            ->orderBy('id')
            ->get();

        $attendances = Attendance::where('group_id', $request->group_id)
            ->where('date', $request->date)
            ->get()
            ->keyBy('student_id');

        $data = $students->map(function ($student) use ($attendances) {
            $attendance = $attendances->get($student->id);
            return [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'document_number' => $student->user->document_number,
                'attendance_id' => $attendance?->id,
                'status' => $attendance?->status?->value ?? null,
                'observation' => $attendance?->observation,
            ];
        });

        return response()->json($data);
    }

    public function bulkStore(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'period_id' => 'required|exists:periods,id',
            'date' => 'required|date',
            'records' => 'required|array',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.status' => 'required|in:present,absent,late,excused',
            'records.*.observation' => 'nullable|string|max:500',
        ]);

        $saved = [];

        foreach ($request->records as $record) {
            $attendance = Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'date' => $request->date,
                ],
                [
                    'group_id' => $request->group_id,
                    'period_id' => $request->period_id,
                    'status' => $record['status'],
                    'observation' => $record['observation'] ?? null,
                    'registered_by' => auth()->id(),
                ]
            );
            $saved[] = $attendance;
        }

        return response()->json([
            'message' => 'Asistencia registrada correctamente',
            'count' => count($saved),
        ]);
    }

    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'observation' => 'nullable|string|max:500',
        ]);

        $attendance->update([
            'status' => $request->status,
            'observation' => $request->observation,
            'registered_by' => auth()->id(),
        ]);

        return response()->json($attendance);
    }

    public function report(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'period_id' => 'required|exists:periods,id',
        ]);

        $attendances = Attendance::where('student_id', $request->student_id)
            ->where('period_id', $request->period_id)
            ->orderBy('date')
            ->get();

        $summary = [
            'total_days' => $attendances->count(),
            'present' => $attendances->where('status', AttendanceStatus::PRESENT)->count(),
            'absent' => $attendances->where('status', AttendanceStatus::ABSENT)->count(),
            'late' => $attendances->where('status', AttendanceStatus::LATE)->count(),
            'excused' => $attendances->where('status', AttendanceStatus::EXCUSED)->count(),
        ];

        $summary['attendance_percentage'] = $summary['total_days'] > 0
            ? round(($summary['present'] + $summary['excused']) / $summary['total_days'] * 100, 1)
            : 100;

        return response()->json([
            'summary' => $summary,
            'records' => $attendances,
        ]);
    }

    public function daily(Group $group): JsonResponse
    {
        $today = Carbon::today();

        $students = Student::where('group_id', $group->id)
            ->where('status', 'active')
            ->with(['user:id,name'])
            ->get();

        $attendances = Attendance::where('group_id', $group->id)
            ->where('date', $today)
            ->get()
            ->keyBy('student_id');

        return response()->json([
            'date' => $today->toDateString(),
            'group' => $group->load('grade'),
            'students' => $students->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->user->name,
                'status' => $attendances->get($s->id)?->status?->value,
            ]),
            'summary' => [
                'total' => $students->count(),
                'registered' => $attendances->count(),
                'pending' => $students->count() - $attendances->count(),
            ],
        ]);
    }
}
