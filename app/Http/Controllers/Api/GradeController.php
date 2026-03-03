<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GradeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // BelongsToTenant trait automatically filters by current institution
        $query = Grade::with('subjects')->withCount('groups');

        if ($request->level) {
            $query->where('level', $request->level);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $query->orderBy('order');

        $perPage = $request->per_page ?? 15;
        $grades = $query->paginate($perPage);

        return response()->json([
            'data' => $grades->items(),
            'meta' => [
                'current_page' => $grades->currentPage(),
                'last_page' => $grades->lastPage(),
                'per_page' => $grades->perPage(),
                'total' => $grades->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10',
            'order' => 'required|integer|min:0',
            'level' => 'required|string|in:preescolar,primaria,secundaria,media',
        ]);

        // BelongsToTenant trait automatically sets institution_id
        $grade = Grade::create($request->all());

        return response()->json($grade, 201);
    }

    public function show(Grade $grade): JsonResponse
    {
        $grade->load(['subjects.area', 'groups']);

        return response()->json($grade);
    }

    public function update(Request $request, Grade $grade): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'short_name' => 'sometimes|string|max:10',
            'order' => 'sometimes|integer|min:0',
            'level' => 'sometimes|string|in:preescolar,primaria,secundaria,media',
        ]);

        $grade->update($request->all());

        return response()->json($grade);
    }

    public function destroy(Grade $grade): JsonResponse
    {
        $grade->delete();

        return response()->json(['message' => 'Grado eliminado']);
    }
}
