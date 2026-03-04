<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Institution;
use App\Models\AcademicYear;
use App\Models\Period;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Area;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\TeacherAssignment;
use App\Models\GradeRecord;
use App\Models\Attendance;
use App\Models\Announcement;
use App\Models\Achievement;
use App\Models\AchievementIndicator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Institution
        $institution = Institution::create([
            'name' => 'Colegio Aula360 Demo',
            'nit' => '900.123.456-7',
            'dane_code' => '108001001234',
            'address' => 'Calle 45 # 23-15, Barrio Centro',
            'phone' => '605 3456789',
            'email' => 'contacto@aula360demo.edu.co',
            'city' => 'Barranquilla',
            'department' => 'Atlántico',
            'rector_name' => 'María Elena Rodríguez Pérez',
        ]);

        // 2. Admin User
        $admin = User::create([
            'institution_id' => $institution->id,
            'name' => 'Administrador Sistema',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'document_type' => 'CC',
            'document_number' => '1234567890',
            'phone' => '300 123 4567',
        ]);

        // 3. Coordinator User
        $coordinatorUser = User::create([
            'institution_id' => $institution->id,
            'name' => 'Carlos Alberto Mejía',
            'email' => 'coordinador@example.com',
            'password' => bcrypt('password'),
            'role' => 'coordinator',
            'document_type' => 'CC',
            'document_number' => '9876543210',
            'phone' => '301 234 5678',
        ]);

        // 4. Academic Year
        $year = AcademicYear::create([
            'institution_id' => $institution->id,
            'year' => 2026,
            'start_date' => '2026-01-12',
            'end_date' => '2026-11-27',
            'is_active' => true,
        ]);

        // 5. Periods
        $periodsData = [
            ['name' => 'Primer Período', 'number' => 1, 'weight' => 25, 'start_date' => '2026-01-12', 'end_date' => '2026-03-27', 'is_closed' => false],
            ['name' => 'Segundo Período', 'number' => 2, 'weight' => 25, 'start_date' => '2026-03-30', 'end_date' => '2026-06-12', 'is_closed' => false],
            ['name' => 'Tercer Período', 'number' => 3, 'weight' => 25, 'start_date' => '2026-07-06', 'end_date' => '2026-09-11', 'is_closed' => false],
            ['name' => 'Cuarto Período', 'number' => 4, 'weight' => 25, 'start_date' => '2026-09-14', 'end_date' => '2026-11-27', 'is_closed' => false],
        ];

        $periods = [];
        foreach ($periodsData as $p) {
            $periods[] = Period::create(array_merge($p, ['academic_year_id' => $year->id]));
        }

        // 6. Grades
        $gradesData = [
            ['name' => 'Transición', 'short_name' => 'Trans', 'order' => 0, 'level' => 'preescolar'],
            ['name' => 'Primero', 'short_name' => '1°', 'order' => 1, 'level' => 'primaria'],
            ['name' => 'Segundo', 'short_name' => '2°', 'order' => 2, 'level' => 'primaria'],
            ['name' => 'Tercero', 'short_name' => '3°', 'order' => 3, 'level' => 'primaria'],
            ['name' => 'Cuarto', 'short_name' => '4°', 'order' => 4, 'level' => 'primaria'],
            ['name' => 'Quinto', 'short_name' => '5°', 'order' => 5, 'level' => 'primaria'],
            ['name' => 'Sexto', 'short_name' => '6°', 'order' => 6, 'level' => 'secundaria'],
            ['name' => 'Séptimo', 'short_name' => '7°', 'order' => 7, 'level' => 'secundaria'],
            ['name' => 'Octavo', 'short_name' => '8°', 'order' => 8, 'level' => 'secundaria'],
            ['name' => 'Noveno', 'short_name' => '9°', 'order' => 9, 'level' => 'secundaria'],
            ['name' => 'Décimo', 'short_name' => '10°', 'order' => 10, 'level' => 'media'],
            ['name' => 'Undécimo', 'short_name' => '11°', 'order' => 11, 'level' => 'media'],
        ];

        $grades = [];
        foreach ($gradesData as $g) {
            $grades[] = Grade::create(array_merge($g, ['institution_id' => $institution->id]));
        }

        // 7. Areas and Subjects
        $areasData = [
            'Matemáticas' => ['Matemáticas', 'Geometría', 'Estadística'],
            'Humanidades' => ['Lengua Castellana', 'Inglés', 'Lectura Crítica'],
            'Ciencias Naturales' => ['Biología', 'Química', 'Física'],
            'Ciencias Sociales' => ['Historia', 'Geografía', 'Democracia'],
            'Educación Artística' => ['Artes Plásticas', 'Música'],
            'Educación Física' => ['Educación Física'],
            'Tecnología' => ['Informática', 'Tecnología'],
            'Ética y Valores' => ['Ética', 'Religión'],
        ];

        $subjects = [];
        $subjectOrder = 1;
        foreach ($areasData as $areaName => $subjectNames) {
            $area = Area::create([
                'institution_id' => $institution->id,
                'name' => $areaName,
                'order' => $subjectOrder,
            ]);

            foreach ($subjectNames as $subjectName) {
                foreach ($grades as $grade) {
                    $subjects[] = Subject::create([
                        'area_id' => $area->id,
                        'grade_id' => $grade->id,
                        'name' => $subjectName,
                        'weekly_hours' => rand(2, 5),
                        'order' => $subjectOrder,
                    ]);
                }
            }
            $subjectOrder++;
        }

        // 8. Create Groups (2 per grade)
        $groups = [];
        foreach ($grades as $grade) {
            foreach (['A', 'B'] as $groupName) {
                $groups[] = Group::create([
                    'grade_id' => $grade->id,
                    'academic_year_id' => $year->id,
                    'name' => $groupName,
                    'capacity' => 35,
                ]);
            }
        }

        // 9. Create Teachers (15 teachers)
        $teacherNames = [
            ['name' => 'María García López', 'specialty' => 'Matemáticas', 'contract' => 'full_time'],
            ['name' => 'Carlos Rodríguez Pérez', 'specialty' => 'Ciencias Naturales', 'contract' => 'full_time'],
            ['name' => 'Ana Martínez Gómez', 'specialty' => 'Lengua Castellana', 'contract' => 'full_time'],
            ['name' => 'Luis Hernández Castro', 'specialty' => 'Ciencias Sociales', 'contract' => 'full_time'],
            ['name' => 'Patricia López Díaz', 'specialty' => 'Inglés', 'contract' => 'full_time'],
            ['name' => 'Jorge Sánchez Ruiz', 'specialty' => 'Educación Física', 'contract' => 'full_time'],
            ['name' => 'Carmen Torres Vega', 'specialty' => 'Artes', 'contract' => 'part_time'],
            ['name' => 'Miguel Ramírez Silva', 'specialty' => 'Tecnología', 'contract' => 'full_time'],
            ['name' => 'Laura González Mora', 'specialty' => 'Ética y Religión', 'contract' => 'part_time'],
            ['name' => 'Pedro Vargas Luna', 'specialty' => 'Física', 'contract' => 'full_time'],
            ['name' => 'Sandra Mendoza Rico', 'specialty' => 'Química', 'contract' => 'full_time'],
            ['name' => 'Ricardo Flores Pinto', 'specialty' => 'Historia', 'contract' => 'full_time'],
            ['name' => 'Elena Ríos Cardona', 'specialty' => 'Geografía', 'contract' => 'part_time'],
            ['name' => 'Fernando Castro Mejía', 'specialty' => 'Música', 'contract' => 'contractor'],
            ['name' => 'Mónica Duarte Herrera', 'specialty' => 'Preescolar', 'contract' => 'full_time'],
        ];

        $teachers = [];
        $docNumber = 10000000;
        foreach ($teacherNames as $t) {
            $user = User::create([
                'institution_id' => $institution->id,
                'name' => $t['name'],
                'email' => Str::slug($t['name'], '.') . '@aula360demo.edu.co',
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'document_type' => 'CC',
                'document_number' => (string) $docNumber++,
                'phone' => '30' . rand(0, 9) . ' ' . rand(100, 999) . ' ' . rand(1000, 9999),
            ]);

            $teachers[] = Teacher::create([
                'user_id' => $user->id,
                'institution_id' => $institution->id,
                'specialization' => $t['specialty'],
            ]);
        }

        // 10. Assign first teacher as director of some groups
        foreach (array_slice($groups, 0, 6) as $index => $group) {
            $group->update(['director_id' => $teachers[$index % count($teachers)]->id]);
        }

        // 11. Create Teacher Assignments
        // María García López (teachers[0], Matemáticas) gets fixed assignments in 6° and 7°
        $mariaTeacher = $teachers[0];
        $mathSubjectNames = ['Matemáticas', 'Geometría', 'Estadística'];
        $mariaGrades = array_slice($grades, 6, 2); // Sexto (index 6) and Séptimo (index 7)
        $mariaGroupIds = [];

        foreach ($mariaGrades as $grade) {
            foreach ($groups as $group) {
                if ($group->grade_id === $grade->id) {
                    $mariaGroupIds[] = $group->id;
                }
            }
        }

        $assignedKeys = []; // track subject_id+group_id pairs already assigned

        foreach ($mariaGroupIds as $groupId) {
            $group = collect($groups)->firstWhere('id', $groupId);
            $mathSubjects = Subject::where('grade_id', $group->grade_id)
                ->whereIn('name', $mathSubjectNames)
                ->get();

            foreach ($mathSubjects as $subject) {
                TeacherAssignment::create([
                    'teacher_id' => $mariaTeacher->id,
                    'subject_id' => $subject->id,
                    'group_id' => $groupId,
                    'academic_year_id' => $year->id,
                ]);
                $assignedKeys["{$subject->id}-{$groupId}"] = true;
            }
        }

        // All remaining subjects assigned randomly across all teachers
        foreach ($groups as $group) {
            $gradeSubjects = Subject::where('grade_id', $group->grade_id)->get();
            foreach ($gradeSubjects as $subject) {
                if (isset($assignedKeys["{$subject->id}-{$group->id}"])) {
                    continue;
                }
                TeacherAssignment::create([
                    'teacher_id' => $teachers[array_rand($teachers)]->id,
                    'subject_id' => $subject->id,
                    'group_id' => $group->id,
                    'academic_year_id' => $year->id,
                ]);
            }
        }

        // 12. Create Students (15-25 per group)
        $firstNames = [
            'Santiago', 'Valentina', 'Matías', 'Sofía', 'Samuel',
            'Isabella', 'Nicolás', 'Mariana', 'Alejandro', 'Luciana',
            'Sebastián', 'Gabriela', 'Daniel', 'Valeria', 'Tomás',
            'Camila', 'Martín', 'Sara', 'Joaquín', 'Paula',
            'Andrés', 'María José', 'Felipe', 'Laura', 'David',
            'Ana María', 'Juan Pablo', 'Carolina', 'Diego', 'Natalia',
        ];

        $lastNames = [
            'García', 'Rodríguez', 'Martínez', 'López', 'González',
            'Hernández', 'Pérez', 'Sánchez', 'Ramírez', 'Torres',
            'Flores', 'Rivera', 'Gómez', 'Díaz', 'Reyes',
            'Morales', 'Jiménez', 'Ruiz', 'Álvarez', 'Romero',
        ];

        $students = [];
        $studentDocNumber = 1000000000;

        foreach ($groups as $group) {
            $numStudents = rand(15, 25);
            for ($i = 0; $i < $numStudents; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)] . ' ' . $lastNames[array_rand($lastNames)];

                // Crear acudiente (padre/madre)
                $guardianUser = User::create([
                    'institution_id' => $institution->id,
                    'name' => $lastNames[array_rand($lastNames)] . ' ' . $lastNames[array_rand($lastNames)],
                    'email' => 'acudiente' . $studentDocNumber . '@aula360demo.edu.co',
                    'password' => bcrypt('password'),
                    'role' => 'guardian',
                    'document_type' => 'CC',
                    'document_number' => (string) (20000000 + $studentDocNumber),
                    'phone' => '30' . rand(0, 9) . ' ' . rand(100, 999) . ' ' . rand(1000, 9999),
                ]);

                $guardian = Guardian::create([
                    'user_id' => $guardianUser->id,
                    'institution_id' => $institution->id,
                    'relationship' => ['father', 'mother'][array_rand(['father', 'mother'])],
                    'occupation' => ['Empleado', 'Independiente', 'Comerciante', 'Profesional'][array_rand(['Empleado', 'Independiente', 'Comerciante', 'Profesional'])],
                ]);

                // Crear usuario del estudiante (sin login - solo para datos)
                $studentUser = User::create([
                    'institution_id' => $institution->id,
                    'name' => "$firstName $lastName",
                    'email' => 'est' . $studentDocNumber . '@estudiante.aula360.co',
                    'password' => bcrypt('password'),
                    'role' => 'guardian', // No tiene acceso directo, usa el del acudiente
                    'document_type' => 'TI',
                    'document_number' => (string) $studentDocNumber++,
                ]);

                $student = Student::create([
                    'user_id' => $studentUser->id,
                    'group_id' => $group->id,
                    'enrollment_date' => '2026-01-08',
                    'enrollment_code' => 'EST-' . str_pad(count($students) + 1, 5, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]);

                // Vincular estudiante con acudiente
                $student->guardians()->attach($guardian->id, ['is_primary' => true]);

                $students[] = $student;
            }
        }

        // 13. Create Grade Records for first period (in progress)
        $firstPeriod = $periods[0];
        $this->command->info('Creando calificaciones del primer período (en curso)...');

        foreach ($groups as $group) {
            $groupStudents = Student::where('group_id', $group->id)->get();
            $gradeSubjects = Subject::where('grade_id', $group->grade_id)->get();

            foreach ($groupStudents as $student) {
                foreach ($gradeSubjects as $subject) {
                    // Generate realistic grades (most between 3.0 and 4.5)
                    $grade = $this->generateRealisticGrade();

                    $assignment = TeacherAssignment::where('subject_id', $subject->id)
                        ->where('group_id', $group->id)
                        ->first();

                    GradeRecord::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'period_id' => $firstPeriod->id,
                        'teacher_id' => $assignment?->teacher_id ?? $teachers[0]->id,
                        'grade' => $grade,
                        'observations' => $grade < 3.0 ? 'Requiere refuerzo en los temas vistos.' : null,
                        'recommendations' => $grade < 3.0 ? 'Realizar talleres de recuperación.' : null,
                    ]);
                }
            }
        }

        // 14. Create Achievements (logros) for first period
        $this->command->info('Creando logros por asignatura...');

        $achievementsBySubject = [
            'Matemáticas' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Identifica y aplica las operaciones básicas con números enteros en la resolución de problemas cotidianos.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Resuelve ecuaciones de primer grado aplicando propiedades de igualdad.'],
                ['code' => 'L3', 'type' => 'attitudinal', 'description' => 'Demuestra interés y perseverancia en la solución de problemas matemáticos.'],
            ],
            'Geometría' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Reconoce y clasifica figuras geométricas según sus propiedades.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Calcula áreas y perímetros de figuras planas aplicando fórmulas.'],
            ],
            'Estadística' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Interpreta y analiza datos estadísticos presentados en tablas y gráficos.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Calcula medidas de tendencia central: media, mediana y moda.'],
            ],
            'Lengua Castellana' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Comprende e interpreta textos narrativos identificando su estructura y elementos.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Produce textos escritos coherentes y cohesivos según el propósito comunicativo.'],
                ['code' => 'L3', 'type' => 'attitudinal', 'description' => 'Valora la lectura como medio de entretenimiento y conocimiento.'],
            ],
            'Inglés' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Comprende textos cortos en inglés identificando información específica.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Utiliza vocabulario y estructuras gramaticales básicas para expresarse en inglés.'],
            ],
            'Lectura Crítica' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Analiza textos argumentativos identificando tesis y argumentos.'],
            ],
            'Biología' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Explica la estructura y función de la célula como unidad básica de los seres vivos.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Realiza observaciones microscópicas siguiendo protocolos de laboratorio.'],
            ],
            'Química' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Describe las propiedades de la materia y sus cambios físicos y químicos.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Balancea ecuaciones químicas aplicando la ley de conservación de la masa.'],
            ],
            'Física' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Comprende los conceptos de movimiento, velocidad y aceleración.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Resuelve problemas de cinemática aplicando las ecuaciones del movimiento.'],
            ],
            'Historia' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Analiza los principales acontecimientos históricos de Colombia en el siglo XX.'],
                ['code' => 'L2', 'type' => 'attitudinal', 'description' => 'Valora la importancia del conocimiento histórico para comprender el presente.'],
            ],
            'Geografía' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Describe las características físicas y políticas del territorio colombiano.'],
            ],
            'Democracia' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Comprende los mecanismos de participación ciudadana en Colombia.'],
                ['code' => 'L2', 'type' => 'attitudinal', 'description' => 'Demuestra actitudes democráticas en el trabajo en equipo.'],
            ],
            'Artes Plásticas' => [
                ['code' => 'L1', 'type' => 'procedural', 'description' => 'Aplica técnicas de dibujo y pintura en la creación artística.'],
                ['code' => 'L2', 'type' => 'attitudinal', 'description' => 'Expresa creatividad y sensibilidad estética en sus producciones artísticas.'],
            ],
            'Música' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Reconoce elementos musicales básicos: ritmo, melodía y armonía.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Interpreta piezas musicales sencillas con voz o instrumento.'],
            ],
            'Educación Física' => [
                ['code' => 'L1', 'type' => 'procedural', 'description' => 'Ejecuta movimientos coordinados en actividades deportivas y recreativas.'],
                ['code' => 'L2', 'type' => 'attitudinal', 'description' => 'Practica hábitos de vida saludable y trabajo en equipo.'],
            ],
            'Informática' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Comprende los componentes y funcionamiento de un sistema informático.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Utiliza herramientas ofimáticas para crear documentos y presentaciones.'],
            ],
            'Tecnología' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Analiza el impacto de la tecnología en la sociedad y el medio ambiente.'],
                ['code' => 'L2', 'type' => 'procedural', 'description' => 'Diseña soluciones tecnológicas a problemas del entorno.'],
            ],
            'Ética' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Reflexiona sobre dilemas éticos y toma decisiones responsables.'],
                ['code' => 'L2', 'type' => 'attitudinal', 'description' => 'Practica valores como respeto, honestidad y solidaridad.'],
            ],
            'Religión' => [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => 'Conoce los fundamentos de las principales tradiciones religiosas.'],
                ['code' => 'L2', 'type' => 'attitudinal', 'description' => 'Respeta la diversidad de creencias y prácticas religiosas.'],
            ],
        ];

        $allSubjects = Subject::all();
        $achievementCount = 0;

        foreach ($allSubjects as $subject) {
            $subjectAchievements = $achievementsBySubject[$subject->name] ?? [
                ['code' => 'L1', 'type' => 'cognitive', 'description' => "Comprende los conceptos fundamentales de {$subject->name}."],
            ];

            foreach ($subjectAchievements as $order => $achievementData) {
                $achievement = Achievement::create([
                    'subject_id' => $subject->id,
                    'period_id' => $firstPeriod->id,
                    'code' => $achievementData['code'],
                    'description' => $achievementData['description'],
                    'type' => $achievementData['type'],
                    'order' => $order + 1,
                    'is_active' => true,
                ]);

                // Add indicators for the first achievement
                if ($order === 0) {
                    AchievementIndicator::create([
                        'achievement_id' => $achievement->id,
                        'code' => 'I1',
                        'description' => 'Demuestra comprensión de los conceptos básicos.',
                        'order' => 1,
                    ]);
                    AchievementIndicator::create([
                        'achievement_id' => $achievement->id,
                        'code' => 'I2',
                        'description' => 'Aplica los conocimientos en ejercicios prácticos.',
                        'order' => 2,
                    ]);
                }

                $achievementCount++;
            }
        }

        $this->command->info("   Creados {$achievementCount} logros para " . $allSubjects->count() . " asignaturas");

        // 15. Create Attendance Records (last 30 school days)
        $this->command->info('Creando registros de asistencia...');

        $schoolDays = [];
        $currentDate = Carbon::parse('2026-01-12');
        $endDate = Carbon::parse('2026-01-17');

        while ($currentDate <= $endDate) {
            if (!$currentDate->isWeekend()) {
                $schoolDays[] = $currentDate->format('Y-m-d');
            }
            $currentDate->addDay();
        }

        $attendanceStatuses = ['present', 'present', 'present', 'present', 'present',
                               'present', 'present', 'present', 'late', 'absent', 'excused'];

        foreach (array_slice($groups, 0, 6) as $group) { // Only first 6 groups for speed
            $groupStudents = Student::where('group_id', $group->id)->get();

            foreach ($schoolDays as $date) {
                foreach ($groupStudents as $student) {
                    $status = $attendanceStatuses[array_rand($attendanceStatuses)];

                    Attendance::create([
                        'student_id' => $student->id,
                        'group_id' => $group->id,
                        'period_id' => $firstPeriod->id,
                        'date' => $date,
                        'status' => $status,
                        'observation' => $status === 'excused' ? 'Cita médica' : null,
                        'registered_by' => $teachers[0]->user_id,
                    ]);
                }
            }
        }

        // 15. Create Announcements
        $announcements = [
            [
                'title' => 'Bienvenidos al Año Escolar 2026',
                'content' => 'Damos la bienvenida a toda la comunidad educativa al nuevo año escolar 2026. Les deseamos un año lleno de éxitos y aprendizajes.',
                'is_published' => true,
                'published_at' => '2026-01-12',
            ],
            [
                'title' => 'Reunión de Padres de Familia',
                'content' => 'Se convoca a todos los padres de familia a la reunión general que se llevará a cabo el día 24 de enero a las 7:00 AM en el auditorio principal. Tema: Presentación del plan académico 2026.',
                'is_published' => true,
                'published_at' => '2026-01-15',
            ],
            [
                'title' => 'Horarios de Atención',
                'content' => 'Recordamos que la secretaría académica atiende de lunes a viernes de 7:00 AM a 12:00 PM y de 2:00 PM a 5:00 PM. Para trámites especiales, agendar cita previa.',
                'is_published' => true,
                'published_at' => '2026-01-14',
            ],
            [
                'title' => 'Inscripciones Actividades Extracurriculares',
                'content' => 'Ya están abiertas las inscripciones para las actividades extracurriculares: Fútbol, Baloncesto, Danzas, Teatro, Robótica y Club de Ciencias. Inscríbase en coordinación hasta el 31 de enero.',
                'is_published' => true,
                'published_at' => '2026-01-13',
            ],
            [
                'title' => 'Simulacro de Evacuación',
                'content' => 'Se realizará un simulacro de evacuación el próximo miércoles. Solicitamos a todos los docentes revisar con sus estudiantes las rutas de evacuación asignadas.',
                'is_published' => false,
                'published_at' => null,
            ],
        ];

        foreach ($announcements as $a) {
            Announcement::create(array_merge($a, [
                'institution_id' => $institution->id,
                'user_id' => $admin->id,
            ]));
        }

        // Summary
        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  DATOS DE PRUEBA CREADOS EXITOSAMENTE');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->newLine();
        $this->command->info('  📊 Resumen:');
        $this->command->info('     • 1 Institución');
        $this->command->info('     • ' . User::count() . ' Usuarios');
        $this->command->info('     • ' . count($teachers) . ' Docentes');
        $this->command->info('     • ' . count($students) . ' Estudiantes');
        $this->command->info('     • ' . count($grades) . ' Grados');
        $this->command->info('     • ' . count($groups) . ' Grupos');
        $this->command->info('     • ' . Subject::count() . ' Asignaturas');
        $this->command->info('     • ' . GradeRecord::count() . ' Calificaciones');
        $this->command->info('     • ' . Achievement::count() . ' Logros');
        $this->command->info('     • ' . Attendance::count() . ' Registros de asistencia');
        $this->command->newLine();
        $this->command->info('  🔐 Credenciales de acceso:');
        $this->command->info('     Admin:       admin@example.com / password');
        $this->command->info('     Coordinador: coordinador@example.com / password');
        $this->command->info('     Docente:     maria.garcia.lopez@aula360demo.edu.co / password');
        $this->command->info('     Acudiente:   acudiente1000000000@aula360demo.edu.co / password');
        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════════════');
    }

    private function generateRealisticGrade(): float
    {
        // Generate grades with a realistic distribution
        // Most students between 3.0 and 4.5
        $random = mt_rand(1, 100);

        if ($random <= 5) {
            // 5% fail badly (1.0-2.4)
            return round(mt_rand(10, 24) / 10, 1);
        } elseif ($random <= 15) {
            // 10% barely fail (2.5-2.9)
            return round(mt_rand(25, 29) / 10, 1);
        } elseif ($random <= 40) {
            // 25% basic (3.0-3.5)
            return round(mt_rand(30, 35) / 10, 1);
        } elseif ($random <= 75) {
            // 35% good (3.6-4.2)
            return round(mt_rand(36, 42) / 10, 1);
        } else {
            // 25% excellent (4.3-5.0)
            return round(mt_rand(43, 50) / 10, 1);
        }
    }
}
