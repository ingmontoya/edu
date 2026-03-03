<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GuardianController extends Controller
{
    public function index(): JsonResponse
    {
        // BelongsToTenant trait automatically filters by current institution
        $guardians = Guardian::with('user')->get();

        return response()->json($guardians);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'document_type' => 'required|string|max:10',
            'document_number' => 'required|string|max:50',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:255',
        ]);

        $institutionId = TenantService::getInstitutionId();

        $guardian = DB::transaction(function () use ($request, $institutionId) {
            $user = User::create([
                'institution_id' => $institutionId,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('password'),
                'role' => 'guardian',
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // BelongsToTenant trait automatically sets institution_id
            return Guardian::create([
                'user_id' => $user->id,
                'relationship' => $request->relationship,
                'occupation' => $request->occupation,
            ]);
        });

        return response()->json($guardian->load('user'), 201);
    }

    public function show(Guardian $guardian): JsonResponse
    {
        $guardian->load(['user', 'students.user', 'students.group.grade']);

        return response()->json($guardian);
    }

    public function update(Request $request, Guardian $guardian): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $guardian->user_id,
            'document_type' => 'sometimes|string|max:10',
            'document_number' => 'sometimes|string|max:50',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'relationship' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $guardian) {
            $guardian->user->update($request->only([
                'name', 'email', 'document_type', 'document_number', 'phone', 'address'
            ]));

            $guardian->update($request->only(['relationship', 'occupation']));
        });

        return response()->json($guardian->load('user'));
    }

    public function destroy(Guardian $guardian): JsonResponse
    {
        $guardian->user->update(['is_active' => false]);

        return response()->json(['message' => 'Acudiente desactivado']);
    }

    public function students(Guardian $guardian): JsonResponse
    {
        $students = $guardian->students()
            ->with(['user', 'group.grade'])
            ->get();

        return response()->json($students);
    }
}
