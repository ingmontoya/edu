<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Subject::with(['area', 'grade']);

        if ($request->area_id) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->grade_id) {
            $query->where('grade_id', $request->grade_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Si es docente y se especifica un grupo, filtrar solo las asignaturas asignadas
        if ($user->isTeacher() && $user->teacher && $request->group_id) {
            $assignedSubjectIds = $user->teacher->assignments()
                ->where('group_id', $request->group_id)
                ->pluck('subject_id');
            $query->whereIn('id', $assignedSubjectIds);
        }

        $query->orderBy('area_id')->orderBy('order');

        $perPage = $request->per_page ?? 15;
        $subjects = $query->paginate($perPage);

        return response()->json([
            'data' => $subjects->items(),
            'meta' => [
                'current_page' => $subjects->currentPage(),
                'last_page' => $subjects->lastPage(),
                'per_page' => $subjects->perPage(),
                'total' => $subjects->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string|max:255',
            'weekly_hours' => 'nullable|integer|min:1|max:20',
            'order' => 'nullable|integer|min:0',
        ]);

        $subject = Subject::create($request->all());

        return response()->json($subject->load(['area', 'grade']), 201);
    }

    public function show(Subject $subject): JsonResponse
    {
        $subject->load(['area', 'grade']);

        return response()->json($subject);
    }

    public function update(Request $request, Subject $subject): JsonResponse
    {
        $request->validate([
            'area_id' => 'sometimes|exists:areas,id',
            'grade_id' => 'sometimes|exists:grades,id',
            'name' => 'sometimes|string|max:255',
            'weekly_hours' => 'nullable|integer|min:1|max:20',
            'order' => 'nullable|integer|min:0',
        ]);

        $subject->update($request->all());

        return response()->json($subject->load(['area', 'grade']));
    }

    public function destroy(Subject $subject): JsonResponse
    {
        $subject->delete();

        return response()->json(['message' => 'Asignatura eliminada']);
    }
}
