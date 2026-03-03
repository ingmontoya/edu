<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // BelongsToTenant trait automatically filters by current institution
        $query = Teacher::with('user');

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('specialization', 'like', "%{$search}%");
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $request->per_page ?? 15;
        $teachers = $query->paginate($perPage);

        return response()->json([
            'data' => $teachers->items(),
            'meta' => [
                'current_page' => $teachers->currentPage(),
                'last_page' => $teachers->lastPage(),
                'per_page' => $teachers->perPage(),
                'total' => $teachers->total(),
            ],
        ]);
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
            'specialization' => 'nullable|string|max:255',
        ]);

        $institutionId = TenantService::getInstitutionId();

        $teacher = DB::transaction(function () use ($request, $institutionId) {
            $user = User::create([
                'institution_id' => $institutionId,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // BelongsToTenant trait automatically sets institution_id
            return Teacher::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization,
            ]);
        });

        return response()->json($teacher->load('user'), 201);
    }

    public function show(Teacher $teacher): JsonResponse
    {
        $teacher->load(['user', 'assignments.subject', 'assignments.group.grade']);

        return response()->json($teacher);
    }

    public function update(Request $request, Teacher $teacher): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $teacher->user_id,
            'document_type' => 'sometimes|string|max:10',
            'document_number' => 'sometimes|string|max:50',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $teacher) {
            $teacher->user->update($request->only([
                'name', 'email', 'document_type', 'document_number', 'phone', 'address'
            ]));

            $teacher->update($request->only(['specialization']));
        });

        return response()->json($teacher->load('user'));
    }

    public function destroy(Teacher $teacher): JsonResponse
    {
        $teacher->user->update(['is_active' => false]);

        return response()->json(['message' => 'Docente desactivado']);
    }

    public function assignments(Teacher $teacher): JsonResponse
    {
        $assignments = $teacher->assignments()
            ->with(['subject.area', 'group.grade', 'academicYear'])
            ->get();

        return response()->json($assignments);
    }

    public function assign(Request $request, Teacher $teacher): JsonResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'group_id' => 'required|exists:groups,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $assignment = TeacherAssignment::firstOrCreate([
            'teacher_id' => $teacher->id,
            'subject_id' => $request->subject_id,
            'group_id' => $request->group_id,
            'academic_year_id' => $request->academic_year_id,
        ]);

        return response()->json([
            'message' => 'Asignación creada',
            'assignment' => $assignment->load(['subject', 'group.grade']),
        ]);
    }

    public function unassign(Teacher $teacher, TeacherAssignment $assignment): JsonResponse
    {
        if ($assignment->teacher_id !== $teacher->id) {
            return response()->json(['message' => 'La asignación no pertenece a este docente'], 403);
        }

        $assignment->delete();

        return response()->json(['message' => 'Asignación eliminada']);
    }

    /**
     * Import teachers from CSV
     */
    public function importFromCsv(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $institutionId = TenantService::getInstitutionId();
        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');

        // Skip header row
        $header = fgetcsv($handle, 0, ',');

        $imported = 0;
        $errors = [];
        $lineNumber = 1;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $lineNumber++;

            // Expected format: nombre, documento_tipo, documento_numero, email, telefono, direccion, especializacion
            if (count($row) < 3) {
                $errors[] = "Línea $lineNumber: formato inválido (mínimo: nombre, tipo_documento, numero_documento)";
                continue;
            }

            $name = trim($row[0] ?? '');
            $documentType = trim($row[1] ?? 'CC');
            $documentNumber = trim($row[2] ?? '');
            $email = trim($row[3] ?? '');
            $phone = trim($row[4] ?? '');
            $address = trim($row[5] ?? '');
            $specialization = trim($row[6] ?? '');

            if (empty($name) || empty($documentNumber)) {
                $errors[] = "Línea $lineNumber: nombre y número de documento son requeridos";
                continue;
            }

            // Generate email if not provided
            if (empty($email)) {
                $email = strtolower(str_replace(' ', '.', $name)) . '.' . $documentNumber . '@docente.edu';
            }

            // Check for duplicate email
            if (User::where('email', $email)->exists()) {
                $errors[] = "Línea $lineNumber: el email '$email' ya existe";
                continue;
            }

            try {
                DB::transaction(function () use (
                    $institutionId, $name, $documentType, $documentNumber,
                    $email, $phone, $address, $specialization, &$imported
                ) {
                    $user = User::create([
                        'institution_id' => $institutionId,
                        'name' => $name,
                        'email' => $email,
                        'password' => bcrypt('password'),
                        'role' => 'teacher',
                        'document_type' => $documentType,
                        'document_number' => $documentNumber,
                        'phone' => $phone ?: null,
                        'address' => $address ?: null,
                    ]);

                    Teacher::create([
                        'user_id' => $user->id,
                        'specialization' => $specialization ?: null,
                    ]);

                    $imported++;
                });
            } catch (\Exception $e) {
                $errors[] = "Línea $lineNumber: " . $e->getMessage();
            }
        }

        fclose($handle);

        return response()->json([
            'message' => "Se importaron $imported docentes",
            'count' => $imported,
            'errors' => $errors,
        ]);
    }
}
