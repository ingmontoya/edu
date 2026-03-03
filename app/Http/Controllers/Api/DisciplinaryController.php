<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DisciplinaryRecord;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DisciplinaryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DisciplinaryRecord::with(['student.user', 'reporter', 'period']);

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->period_id) {
            $query->where('period_id', $request->period_id);
        }

        if ($request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $query->orderByDesc('date');

        $perPage = $request->per_page ?? 15;
        $records = $query->paginate($perPage);

        return response()->json([
            'data' => $records->items(),
            'meta' => [
                'current_page' => $records->currentPage(),
                'last_page' => $records->lastPage(),
                'per_page' => $records->perPage(),
                'total' => $records->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'period_id' => 'nullable|exists:periods,id',
            'type' => 'required|in:type1,type2,type3',
            'category' => 'required|in:verbal,physical,psychological,cyberbullying,other',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'witnesses' => 'nullable|string',
            'action_taken' => 'nullable|string',
            'notify_guardian' => 'nullable|boolean',
            'commitment' => 'nullable|string',
        ]);

        $record = DisciplinaryRecord::create([
            ...$request->only([
                'student_id', 'period_id', 'type', 'category', 'description',
                'date', 'location', 'witnesses', 'action_taken', 'notify_guardian', 'commitment',
            ]),
            'reporter_id' => $request->user()->id,
            'status' => 'open',
        ]);

        return response()->json($record->load(['student.user', 'reporter', 'period']), 201);
    }

    public function show(DisciplinaryRecord $disciplinary): JsonResponse
    {
        $disciplinary->load(['student.user', 'reporter', 'period']);

        // Include student's full disciplinary history count
        $studentHistory = DisciplinaryRecord::where('student_id', $disciplinary->student_id)
            ->orderByDesc('date')
            ->get(['id', 'type', 'category', 'date', 'status']);

        return response()->json([
            'record' => $disciplinary,
            'student_history' => $studentHistory,
        ]);
    }

    public function update(Request $request, DisciplinaryRecord $disciplinary): JsonResponse
    {
        $request->validate([
            'type' => 'sometimes|in:type1,type2,type3',
            'category' => 'sometimes|in:verbal,physical,psychological,cyberbullying,other',
            'description' => 'sometimes|string',
            'date' => 'sometimes|date',
            'location' => 'nullable|string|max:255',
            'witnesses' => 'nullable|string',
            'action_taken' => 'nullable|string',
            'status' => 'sometimes|in:open,in_process,resolved,escalated',
            'resolution' => 'nullable|string',
            'notify_guardian' => 'nullable|boolean',
            'commitment' => 'nullable|string',
        ]);

        $data = $request->only([
            'type', 'category', 'description', 'date', 'location',
            'witnesses', 'action_taken', 'status', 'resolution',
            'notify_guardian', 'commitment',
        ]);

        if (isset($data['status']) && $data['status'] === 'resolved' && ! $disciplinary->resolved_at) {
            $data['resolved_at'] = now();
        }

        $disciplinary->update($data);

        return response()->json($disciplinary->load(['student.user', 'reporter', 'period']));
    }

    public function destroy(DisciplinaryRecord $disciplinary): JsonResponse
    {
        $disciplinary->delete();

        return response()->json(['message' => 'Registro eliminado']);
    }

    public function studentHistory(Student $student): JsonResponse
    {
        $records = DisciplinaryRecord::where('student_id', $student->id)
            ->with(['reporter', 'period'])
            ->orderByDesc('date')
            ->get();

        return response()->json($records);
    }
}
