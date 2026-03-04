<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Ensures the demo teacher (María García López) has specific assignments
 * so the tasks/new and grades/record pages show filtered data correctly.
 *
 * Safe to run multiple times (uses firstOrCreate).
 * Run: php artisan db:seed --class=TeacherAssignmentsSeeder
 */
class TeacherAssignmentsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'maria.garcia.lopez@aula360demo.edu.co')->first();

        if (! $user) {
            $this->command->error('No se encontró a María García López. Ejecuta SchoolSeeder primero.');

            return;
        }

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (! $teacher) {
            $this->command->error('No se encontró el registro Teacher para María García López.');

            return;
        }

        $year = AcademicYear::where('is_active', true)->first();

        if (! $year) {
            $this->command->error('No hay año académico activo.');

            return;
        }

        // Asignar Matemáticas, Geometría y Estadística en 6° y 7°
        $targetGradeNames = ['Sexto', 'Séptimo'];
        $targetSubjectNames = ['Matemáticas', 'Geometría', 'Estadística'];

        $grades = Grade::whereIn('name', $targetGradeNames)
            ->where('institution_id', $teacher->institution_id)
            ->get();

        if ($grades->isEmpty()) {
            $this->command->error('No se encontraron los grados Sexto y Séptimo.');

            return;
        }

        $created = 0;

        foreach ($grades as $grade) {
            $groups = Group::where('grade_id', $grade->id)
                ->where('academic_year_id', $year->id)
                ->get();

            $subjects = Subject::where('grade_id', $grade->id)
                ->whereIn('name', $targetSubjectNames)
                ->get();

            foreach ($groups as $group) {
                foreach ($subjects as $subject) {
                    TeacherAssignment::firstOrCreate(
                        [
                            'subject_id' => $subject->id,
                            'group_id' => $group->id,
                            'academic_year_id' => $year->id,
                        ],
                        ['teacher_id' => $teacher->id]
                    );

                    // If the assignment existed but was for another teacher, reassign to María
                    TeacherAssignment::where('subject_id', $subject->id)
                        ->where('group_id', $group->id)
                        ->where('academic_year_id', $year->id)
                        ->update(['teacher_id' => $teacher->id]);

                    $created++;
                }
            }
        }

        $this->command->info("✓ {$created} asignaciones garantizadas para María García López");
        $this->command->info('  Asignaturas: '.implode(', ', $targetSubjectNames));
        $this->command->info('  Grados: '.implode(', ', $targetGradeNames));
    }
}
