<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Guardian;
use App\Models\Institution;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Creates a predictable demo student + guardian pair.
 *
 * The student is placed in Sexto A, one of María García López's groups,
 * so the guardian portal shows relevant tasks and grades immediately.
 *
 * Credentials:
 *   Acudiente:  acudiente.demo@aula360demo.edu.co / password
 *
 * Safe to run multiple times (uses firstOrCreate).
 * Run: php artisan db:seed --class=DemoUsersSeeder
 */
class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $institution = Institution::first();

        if (! $institution) {
            $this->command->error('No hay institución. Ejecuta SchoolSeeder primero.');

            return;
        }

        $year = AcademicYear::where('institution_id', $institution->id)
            ->where('is_active', true)
            ->first();

        if (! $year) {
            $this->command->error('No hay año académico activo.');

            return;
        }

        // Find Sexto A — one of María's assigned groups
        $sexto = Grade::where('institution_id', $institution->id)
            ->where('name', 'Sexto')
            ->first();

        if (! $sexto) {
            $this->command->error('No se encontró el grado Sexto.');

            return;
        }

        $sextoA = Group::where('grade_id', $sexto->id)
            ->where('academic_year_id', $year->id)
            ->where('name', 'A')
            ->first();

        if (! $sextoA) {
            $this->command->error('No se encontró el grupo Sexto A.');

            return;
        }

        // ── Student ──────────────────────────────────────────────────────────

        $studentUser = User::firstOrCreate(
            ['email' => 'estudiante.demo@aula360demo.edu.co'],
            [
                'institution_id' => $institution->id,
                'name' => 'Santiago García López',
                'password' => bcrypt('password'),
                'role' => 'guardian', // students share the guardian role (no direct login yet)
                'document_type' => 'TI',
                'document_number' => '9900000001',
                'phone' => '300 000 0001',
            ]
        );

        $student = Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            [
                'group_id' => $sextoA->id,
                'enrollment_date' => '2026-01-12',
                'enrollment_code' => 'EST-DEMO-01',
                'status' => 'active',
            ]
        );

        // Ensure student is in the correct group (update if already existed)
        $student->update(['group_id' => $sextoA->id, 'status' => 'active']);

        // ── Guardian ─────────────────────────────────────────────────────────

        $guardianUser = User::firstOrCreate(
            ['email' => 'acudiente.demo@aula360demo.edu.co'],
            [
                'institution_id' => $institution->id,
                'name' => 'Pedro García Rodríguez',
                'password' => bcrypt('password'),
                'role' => 'guardian',
                'document_type' => 'CC',
                'document_number' => '9900000002',
                'phone' => '300 000 0002',
            ]
        );

        $guardian = Guardian::firstOrCreate(
            ['user_id' => $guardianUser->id],
            [
                'institution_id' => $institution->id,
                'relationship' => 'father',
                'occupation' => 'Profesional',
            ]
        );

        // Link guardian ↔ student (ignore if already linked)
        if (! $guardian->students()->where('student_id', $student->id)->exists()) {
            $guardian->students()->attach($student->id, ['is_primary' => true]);
        }

        // ── Summary ──────────────────────────────────────────────────────────

        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  DEMO: ESTUDIANTE + ACUDIENTE');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  Estudiante:  Santiago García López');
        $this->command->info('  Grupo:       Sexto A (grupo de María García López)');
        $this->command->info('');
        $this->command->info('  Acudiente:   Pedro García Rodríguez');
        $this->command->info('  Email:       acudiente.demo@aula360demo.edu.co');
        $this->command->info('  Password:    password');
        $this->command->info('═══════════════════════════════════════════════════════');
    }
}
