<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AreaController extends Controller
{
    public function index(): JsonResponse
    {
        // BelongsToTenant trait automatically filters by current institution
        $areas = Area::with('subjects')
            ->orderBy('order')
            ->get();

        return response()->json($areas);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        // BelongsToTenant trait automatically sets institution_id
        $area = Area::create($request->all());

        return response()->json($area, 201);
    }

    public function show(Area $area): JsonResponse
    {
        $area->load('subjects');

        return response()->json($area);
    }

    public function update(Request $request, Area $area): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        $area->update($request->all());

        return response()->json($area);
    }

    public function destroy(Area $area): JsonResponse
    {
        $area->delete();

        return response()->json(['message' => 'Área eliminada']);
    }
}
