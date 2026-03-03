<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Student::with(['user', 'group.grade']);

        if ($request->group_id) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active');
        }

        if ($request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $request->per_page ?? 15;
        $students = $query->paginate($perPage);

        return response()->json([
            'data' => $students->items(),
            'meta' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total(),
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
            'group_id' => 'required|exists:groups,id',
            'enrollment_code' => 'nullable|string|max:50',
            'enrollment_date' => 'required|date',
        ]);

        $institutionId = TenantService::getInstitutionId();

        $student = DB::transaction(function () use ($request, $institutionId) {
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

            return Student::create([
                'user_id' => $user->id,
                'group_id' => $request->group_id,
                'enrollment_code' => $request->enrollment_code,
                'enrollment_date' => $request->enrollment_date,
            ]);
        });

        return response()->json($student->load(['user', 'group.grade']), 201);
    }

    public function show(Student $student): JsonResponse
    {
        $student->load(['user', 'group.grade', 'guardians.user']);

        return response()->json($student);
    }

    public function update(Request $request, Student $student): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$student->user_id,
            'document_type' => 'sometimes|string|max:10',
            'document_number' => 'sometimes|string|max:50',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'group_id' => 'sometimes|exists:groups,id',
            'enrollment_code' => 'nullable|string|max:50',
            'status' => 'sometimes|string|in:active,inactive,withdrawn,graduated',
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->user->update($request->only([
                'name', 'email', 'document_type', 'document_number', 'phone', 'address',
            ]));

            $student->update($request->only([
                'group_id', 'enrollment_code', 'status',
            ]));
        });

        return response()->json($student->load(['user', 'group.grade']));
    }

    public function destroy(Student $student): JsonResponse
    {
        $student->update(['status' => 'inactive']);

        return response()->json(['message' => 'Estudiante desactivado']);
    }

    public function grades(Student $student, Request $request): JsonResponse
    {
        $query = $student->gradeRecords()->with(['subject.area', 'period']);

        if ($request->period_id) {
            $query->where('period_id', $request->period_id);
        }

        $grades = $query->get();

        return response()->json($grades);
    }

    public function attendance(Student $student, Request $request): JsonResponse
    {
        $query = $student->attendances()->with('period');

        if ($request->period_id) {
            $query->where('period_id', $request->period_id);
        }

        $attendance = $query->orderByDesc('date')->get();

        return response()->json($attendance);
    }

    public function assignGuardian(Request $request, Student $student): JsonResponse
    {
        $request->validate([
            'guardian_id' => 'required|exists:guardians,id',
            'is_primary' => 'nullable|boolean',
        ]);

        $student->guardians()->syncWithoutDetaching([
            $request->guardian_id => ['is_primary' => $request->is_primary ?? false],
        ]);

        return response()->json([
            'message' => 'Acudiente asignado',
            'student' => $student->load('guardians.user'),
        ]);
    }

    public function simatExport(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $institution = TenantService::getInstitution();

        $students = Student::with(['user', 'group.grade'])
            ->where('status', 'active')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="simat_export_'.now()->format('Ymd').'.csv"',
        ];

        return response()->stream(function () use ($students, $institution) {
            $handle = fopen('php://output', 'w');

            // BOM for UTF-8 Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Header row (SIMAT required columns)
            fputcsv($handle, [
                'CODIGO_DANE_ESTABLECIMIENTO',
                'NOMBRE_ESTABLECIMIENTO',
                'NIT_ESTABLECIMIENTO',
                'CODIGO_SIMAT_ESTUDIANTE',
                'TIPO_DOCUMENTO',
                'NUMERO_DOCUMENTO',
                'PRIMER_APELLIDO',
                'SEGUNDO_APELLIDO',
                'PRIMER_NOMBRE',
                'SEGUNDO_NOMBRE',
                'FECHA_NACIMIENTO',
                'GENERO',
                'GRADO',
                'GRUPO',
                'JORNADA',
                'MUNICIPIO_RESIDENCIA',
                'ESTRATO',
                'TIPO_DISCAPACIDAD',
                'ETNIA',
                'EPS_ASEGURADORA',
            ]);

            foreach ($students as $student) {
                $nameParts = explode(' ', $student->user->name, 4);
                $lastName1 = $nameParts[0] ?? '';
                $lastName2 = isset($nameParts[1]) && count($nameParts) > 2 ? $nameParts[1] : '';
                $firstName = $nameParts[count($nameParts) > 2 ? 2 : 1] ?? '';
                $firstName2 = $nameParts[count($nameParts) > 2 ? 3 : 2] ?? '';

                fputcsv($handle, [
                    $institution->dane_code ?? '',
                    $institution->name ?? '',
                    $institution->nit ?? '',
                    $student->simat_code ?? '',
                    $student->user->document_type ?? '',
                    $student->user->document_number ?? '',
                    $lastName1,
                    $lastName2,
                    $firstName,
                    $firstName2,
                    $student->user->birth_date ?? '',
                    '',  // gender not in current model
                    $student->group->grade->short_name ?? '',
                    $student->group->name ?? '',
                    'COMPLETA',
                    $student->municipality ?? '',
                    $student->stratum ?? '',
                    $student->disability_type ?? '',
                    $student->ethnicity ?? '',
                    $student->health_insurer ?? '',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Import students from CSV
     */
    public function importFromCsv(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
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

            // Expected format: nombre, documento_tipo, documento_numero, email, telefono, direccion, codigo_matricula
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
            $enrollmentCode = trim($row[6] ?? '');

            if (empty($name) || empty($documentNumber)) {
                $errors[] = "Línea $lineNumber: nombre y número de documento son requeridos";

                continue;
            }

            // Generate email if not provided
            if (empty($email)) {
                $email = strtolower(str_replace(' ', '.', $name)).'.'.$documentNumber.'@estudiante.edu';
            }

            // Check for duplicate email
            if (User::where('email', $email)->exists()) {
                $errors[] = "Línea $lineNumber: el email '$email' ya existe";

                continue;
            }

            try {
                DB::transaction(function () use (
                    $request, $institutionId, $name, $documentType, $documentNumber,
                    $email, $phone, $address, $enrollmentCode, &$imported
                ) {
                    $user = User::create([
                        'institution_id' => $institutionId,
                        'name' => $name,
                        'email' => $email,
                        'password' => bcrypt('password'),
                        'role' => 'guardian', // Students use guardian role for portal access
                        'document_type' => $documentType,
                        'document_number' => $documentNumber,
                        'phone' => $phone ?: null,
                        'address' => $address ?: null,
                    ]);

                    Student::create([
                        'user_id' => $user->id,
                        'group_id' => $request->group_id,
                        'enrollment_code' => $enrollmentCode ?: null,
                        'enrollment_date' => now(),
                        'status' => 'active',
                    ]);

                    $imported++;
                });
            } catch (\Exception $e) {
                $errors[] = "Línea $lineNumber: ".$e->getMessage();
            }
        }

        fclose($handle);

        return response()->json([
            'message' => "Se importaron $imported estudiantes",
            'count' => $imported,
            'errors' => $errors,
        ]);
    }
}
