<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstitutionController extends Controller
{
    public function show(): JsonResponse
    {
        $institution = TenantService::getInstitution();

        if (! $institution) {
            return response()->json(['message' => 'Institución no configurada'], 404);
        }

        return response()->json($institution);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nit' => 'nullable|string|max:50',
            'dane_code' => 'nullable|string|size:12',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'rector_name' => 'nullable|string|max:255',
            'grading_scale' => 'nullable|array',
        ]);

        $institution = TenantService::getInstitution();

        if (! $institution) {
            return response()->json(['message' => 'Institución no configurada'], 404);
        }

        $institution->update($request->all());

        return response()->json($institution);
    }

    public function uploadLogo(Request $request): JsonResponse
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $institution = TenantService::getInstitution();

        if (! $institution) {
            return response()->json(['message' => 'Institución no configurada'], 404);
        }

        // Delete old logo if exists
        if ($institution->logo) {
            Storage::disk('public')->delete($institution->logo);
        }

        $path = $request->file('logo')->store('logos', 'public');
        $institution->update(['logo' => $path]);

        return response()->json([
            'message' => 'Logo actualizado correctamente',
            'logo' => $path,
        ]);
    }
}
