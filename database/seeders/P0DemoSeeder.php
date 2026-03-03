<?php

namespace Database\Seeders;

use App\Models\DisciplinaryRecord;
use App\Models\Institution;
use App\Models\Period;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class P0DemoSeeder extends Seeder
{
    public function run(): void
    {
        $institution = Institution::first();

        if (! $institution) {
            $this->command->warn('No institution found. Run SchoolSeeder first.');

            return;
        }

        // Get reporter (admin or coordinator)
        $reporter = User::where('institution_id', $institution->id)
            ->whereIn('role', ['admin', 'coordinator'])
            ->first();

        if (! $reporter) {
            $this->command->warn('No admin/coordinator user found.');

            return;
        }

        // Get first period of active year
        $period = Period::whereHas('academicYear', function ($q) use ($institution) {
            $q->where('institution_id', $institution->id)->where('is_active', true);
        })->orderBy('number')->first();

        // Get students
        $students = Student::whereHas('user', function ($q) use ($institution) {
            $q->where('institution_id', $institution->id);
        })->with('user')->take(10)->get();

        if ($students->isEmpty()) {
            $this->command->warn('No students found. Run SchoolSeeder first.');

            return;
        }

        // 1. Add SIMAT fields to first 10 students
        $this->command->info('Agregando datos SIMAT a estudiantes...');

        $estratos = [1, 1, 2, 2, 2, 3, 3, 3, 4, 4];
        $eps = ['SURA', 'Compensar', 'Sanitas', 'Nueva EPS', 'SISBÉN', 'Coomeva', 'Salud Total', 'Famisanar', 'Cruz Blanca', 'Cafesalud'];
        $municipios = ['Barranquilla', 'Soledad', 'Malambo', 'Puerto Colombia', 'Galapa', 'Sabanagrande', 'Sabanalarga', 'Baranoa', 'Palmar de Varela', 'Ponedera'];
        $etnias = [null, null, null, null, null, null, 'ninguna', 'ninguna', 'afrocolombiano', 'indígena'];
        $discapacidades = [null, null, null, null, null, null, null, null, 'visual_leve', null];

        foreach ($students as $index => $student) {
            // Skip if already has SIMAT data
            if ($student->simat_code) {
                continue;
            }

            $student->update([
                'simat_code' => 'SIMAT-'.str_pad($student->id, 8, '0', STR_PAD_LEFT),
                'stratum' => $estratos[$index % count($estratos)],
                'health_insurer' => $eps[$index % count($eps)],
                'municipality' => $municipios[$index % count($municipios)],
                'birth_municipality' => $municipios[($index + 2) % count($municipios)],
                'ethnicity' => $etnias[$index % count($etnias)],
                'disability_type' => $discapacidades[$index % count($discapacidades)],
            ]);
        }

        $this->command->info('   Actualizados '.$students->count().' estudiantes con datos SIMAT');

        // 2. Create Disciplinary Records (skip if already have some)
        if (DisciplinaryRecord::where('institution_id', $institution->id)->count() > 0) {
            $this->command->info('Registros de convivencia ya existen, omitiendo...');
        } else {
            $this->command->info('Creando registros de convivencia (Ley 1620/2013)...');

            $records = [
                [
                    'student' => $students[0],
                    'type' => 'type1',
                    'category' => 'verbal',
                    'description' => 'Discusión verbal entre estudiantes durante el recreo por un conflicto sobre el uso de la cancha de fútbol. Palabras ofensivas registradas por docente de guardia.',
                    'date' => '2026-01-15',
                    'location' => 'Patio principal',
                    'witnesses' => 'Prof. Jorge Sánchez, estudiante Daniel García',
                    'action_taken' => 'Se llamó a ambas partes a dialogar. Se les explicó la importancia del respeto mutuo.',
                    'status' => 'resolved',
                    'resolution' => 'Los estudiantes se comprometieron a respetar los turnos de uso de la cancha. Firmaron acuerdo de convivencia.',
                    'notify_guardian' => true,
                    'commitment' => 'Respetar los acuerdos de uso de espacios comunes y comunicarse asertivamente.',
                ],
                [
                    'student' => $students[1],
                    'type' => 'type2',
                    'category' => 'psychological',
                    'description' => 'Estudiante reportado por compañeros por comentarios reiterados de burla y exclusión hacia un compañero con dificultades de aprendizaje. Situación se ha presentado en los últimos 15 días.',
                    'date' => '2026-01-20',
                    'location' => 'Salón de clases 6-A',
                    'witnesses' => 'Prof. Ana Martínez, 3 estudiantes del salón',
                    'action_taken' => 'Citación al estudiante agresor. Comunicación con acudiente. Remisión a orientación escolar.',
                    'status' => 'in_process',
                    'resolution' => null,
                    'notify_guardian' => true,
                    'commitment' => null,
                ],
                [
                    'student' => $students[2],
                    'type' => 'type1',
                    'category' => 'verbal',
                    'description' => 'Irrespeto al docente durante la clase de Matemáticas. El estudiante respondió de manera agresiva al ser llamado a atención por uso del celular.',
                    'date' => '2026-01-22',
                    'location' => 'Aula 7-B',
                    'witnesses' => 'Estudiantes del grupo 7-B',
                    'action_taken' => 'Se retiró el celular y se remitió a coordinación. Se notificó al acudiente.',
                    'status' => 'resolved',
                    'resolution' => 'El estudiante presentó disculpas al docente. Se estableció compromiso de respeto y uso adecuado del celular.',
                    'notify_guardian' => true,
                    'commitment' => 'No traer celular al colegio durante las próximas 2 semanas. Respetar las normas del manual de convivencia.',
                ],
                [
                    'student' => $students[3],
                    'type' => 'type2',
                    'category' => 'cyberbullying',
                    'description' => 'Se recibió denuncia de acoso cibernético en grupos de WhatsApp. Estudiante difundió fotos de un compañero con comentarios ofensivos. Padres reportaron la situación.',
                    'date' => '2026-01-25',
                    'location' => 'Entorno digital - WhatsApp',
                    'witnesses' => 'Capturas de pantalla presentadas por acudiente afectado',
                    'action_taken' => 'Se citaron ambas familias. Se solicitó eliminación del contenido. Remisión a comité de convivencia.',
                    'status' => 'escalated',
                    'resolution' => null,
                    'notify_guardian' => true,
                    'commitment' => null,
                ],
                [
                    'student' => $students[4],
                    'type' => 'type1',
                    'category' => 'physical',
                    'description' => 'Empujón en el corredor que causó caída de una estudiante. Según testigos fue accidental durante el cambio de clase, pero generó malestar.',
                    'date' => '2026-01-28',
                    'location' => 'Corredor primer piso',
                    'witnesses' => 'Prof. Carmen Torres',
                    'action_taken' => 'Se verificó el estado de salud de la estudiante afectada (sin lesiones). Llamado de atención al responsable.',
                    'status' => 'resolved',
                    'resolution' => 'Estudiante presentó disculpas y se comprometió a ser más cuidadoso en los corredores.',
                    'notify_guardian' => false,
                    'commitment' => 'Caminar con precaución en los corredores y respetar el espacio de los demás.',
                ],
                [
                    'student' => $students[5 % $students->count()],
                    'type' => 'type3',
                    'category' => 'psychological',
                    'description' => 'Estudiante reporta por parte de su acudiente situación de matoneo sistemático por parte de grupo de compañeros de otro curso. Estudiante presenta síntomas de ansiedad y no quiere asistir al colegio.',
                    'date' => '2026-01-30',
                    'location' => 'Varios espacios del colegio',
                    'witnesses' => 'Acudiente presentó evidencias y relato del estudiante afectado',
                    'action_taken' => 'Activación del protocolo de atención Tipo 3. Remisión a orientación escolar urgente. Notificación a rectoría y padres de todos los involucrados.',
                    'status' => 'in_process',
                    'resolution' => null,
                    'notify_guardian' => true,
                    'commitment' => null,
                ],
            ];

            foreach ($records as $record) {
                DisciplinaryRecord::create([
                    'institution_id' => $institution->id,
                    'student_id' => $record['student']->id,
                    'reporter_id' => $reporter->id,
                    'period_id' => $period?->id,
                    'type' => $record['type'],
                    'category' => $record['category'],
                    'description' => $record['description'],
                    'date' => $record['date'],
                    'location' => $record['location'],
                    'witnesses' => $record['witnesses'],
                    'action_taken' => $record['action_taken'],
                    'status' => $record['status'],
                    'resolution' => $record['resolution'] ?? null,
                    'resolved_at' => $record['status'] === 'resolved' ? now() : null,
                    'notify_guardian' => $record['notify_guardian'],
                    'commitment' => $record['commitment'] ?? null,
                ]);
            }

            $this->command->info('   Creados '.count($records).' registros de convivencia');
        }

        // Summary
        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  P0 DEMO DATA CREADO EXITOSAMENTE');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  • Estudiantes con datos SIMAT: '.Student::whereNotNull('simat_code')->count());
        $this->command->info('  • Registros de convivencia: '.DisciplinaryRecord::count());
        $this->command->info('');
        $this->command->info('  Para descargar el CSV SIMAT:');
        $this->command->info('    GET /api/students/simat-export?academic_year_id={id}');
        $this->command->info('');
        $this->command->info('  Para descargar constancias PDF:');
        $this->command->info('    GET /api/certificates/student/{id}/enrollment');
        $this->command->info('    GET /api/certificates/student/{id}/grades');
        $this->command->info('═══════════════════════════════════════════════════════');
    }
}
