<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GroupController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Group::with(['grade', 'academicYear', 'director', 'activeStudents.user']);

        if ($request->grade_id) {
            $query->where('grade_id', $request->grade_id);
        }

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Si es docente, filtrar solo los grupos asignados
        if ($user->isTeacher() && $user->teacher) {
            $assignedGroupIds = $user->teacher->assignments()
                ->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))
                ->pluck('group_id')
                ->unique();
            $query->whereIn('id', $assignedGroupIds);
        }

        $query->orderBy('name');

        $perPage = $request->per_page ?? 15;
        $paginated = $query->paginate($perPage);

        $groups = collect($paginated->items())->map(function ($group) {
            $group->students = $group->activeStudents;
            unset($group->activeStudents);
            return $group;
        });

        return response()->json([
            'data' => $groups,
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:50',
            'capacity' => 'nullable|integer|min:1|max:100',
            'director_id' => 'nullable|exists:users,id',
        ]);

        $group = Group::create($request->all());

        return response()->json($group->load(['grade', 'academicYear']), 201);
    }

    public function show(Group $group): JsonResponse
    {
        $group->load(['grade', 'academicYear', 'director', 'activeStudents.user']);

        return response()->json($group);
    }

    public function update(Request $request, Group $group): JsonResponse
    {
        $request->validate([
            'grade_id' => 'sometimes|exists:grades,id',
            'academic_year_id' => 'sometimes|exists:academic_years,id',
            'name' => 'sometimes|string|max:50',
            'capacity' => 'nullable|integer|min:1|max:100',
            'director_id' => 'nullable|exists:users,id',
        ]);

        $group->update($request->all());

        return response()->json($group->load(['grade', 'academicYear']));
    }

    public function destroy(Group $group): JsonResponse
    {
        $group->delete();

        return response()->json(['message' => 'Grupo eliminado']);
    }

    public function students(Group $group): JsonResponse
    {
        $students = $group->students()
            ->with('user')
            ->where('status', 'active')
            ->get();

        return response()->json($students);
    }

    public function assignDirector(Request $request, Group $group): JsonResponse
    {
        $request->validate([
            'director_id' => 'required|exists:users,id',
        ]);

        $group->update(['director_id' => $request->director_id]);

        return response()->json([
            'message' => 'Director asignado',
            'group' => $group->load('director'),
        ]);
    }
}
