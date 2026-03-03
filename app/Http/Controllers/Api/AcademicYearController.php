<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AcademicYearController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // BelongsToTenant trait automatically filters by current institution
        $query = AcademicYear::with('periods');

        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('year', 'like', "%{$search}%");
        }

        $query->orderByDesc('year');

        $perPage = $request->per_page ?? 15;
        $years = $query->paginate($perPage);

        return response()->json([
            'data' => $years->items(),
            'meta' => [
                'current_page' => $years->currentPage(),
                'last_page' => $years->lastPage(),
                'per_page' => $years->perPage(),
                'total' => $years->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // BelongsToTenant trait automatically sets institution_id
        $academicYear = AcademicYear::create($request->all());

        return response()->json($academicYear, 201);
    }

    public function show(AcademicYear $academicYear): JsonResponse
    {
        $academicYear->load('periods');

        return response()->json($academicYear);
    }

    public function update(Request $request, AcademicYear $academicYear): JsonResponse
    {
        $request->validate([
            'year' => 'sometimes|integer|min:2000|max:2100',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
        ]);

        $academicYear->update($request->all());

        return response()->json($academicYear);
    }

    public function destroy(AcademicYear $academicYear): JsonResponse
    {
        $academicYear->delete();

        return response()->json(['message' => 'Año académico eliminado']);
    }

    public function activate(AcademicYear $academicYear): JsonResponse
    {
        // Deactivate all other years for current institution (using global scope)
        AcademicYear::query()->update(['is_active' => false]);

        $academicYear->update(['is_active' => true]);

        return response()->json([
            'message' => 'Año académico activado',
            'academic_year' => $academicYear,
        ]);
    }
}
