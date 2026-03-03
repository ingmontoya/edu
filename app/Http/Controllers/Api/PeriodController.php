<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PeriodController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Period::with('academicYear');

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $query->orderBy('number');

        $perPage = $request->per_page ?? 15;
        $periods = $query->paginate($perPage);

        return response()->json([
            'data' => $periods->items(),
            'meta' => [
                'current_page' => $periods->currentPage(),
                'last_page' => $periods->lastPage(),
                'per_page' => $periods->perPage(),
                'total' => $periods->total(),
            ],
        ]);
    }

    public function store(Request $request, AcademicYear $academicYear): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|integer|min:1|max:10',
            'weight' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $period = $academicYear->periods()->create($request->all());

        return response()->json($period, 201);
    }

    public function show(Period $period): JsonResponse
    {
        $period->load('academicYear');

        return response()->json($period);
    }

    public function update(Request $request, Period $period): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'number' => 'sometimes|integer|min:1|max:10',
            'weight' => 'sometimes|numeric|min:0|max:100',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
        ]);

        $period->update($request->all());

        return response()->json($period);
    }

    public function destroy(Period $period): JsonResponse
    {
        $period->delete();

        return response()->json(['message' => 'Período eliminado']);
    }

    public function close(Period $period): JsonResponse
    {
        $period->update(['is_closed' => true]);

        return response()->json([
            'message' => 'Período cerrado',
            'period' => $period,
        ]);
    }

    public function open(Period $period): JsonResponse
    {
        $period->update(['is_closed' => false]);

        return response()->json([
            'message' => 'Período abierto',
            'period' => $period,
        ]);
    }
}
