<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Area;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\GradeRecord;
use App\Models\Group;
use App\Models\Institution;
use App\Models\Period;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class ColeMayorSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Institution ───────────────────────────────────────────────────
        $institution = Institution::create([
            'name' => 'Colegio Mayor de Cartagena',
            'nit' => '890.480.128-3',
            'dane_code' => '130010005678',
            'address' => 'Calle del Bouquet # 36-66, Centro Histórico',
            'phone' => '605 6600100',
            'email' => 'admisiones@colmayor.edu.co',
            'city' => 'Cartagena',
            'department' => 'Bolívar',
            'rector_name' => 'Dra. Claudia Patricia Berbesi de Salcedo',
            'education_level' => 'higher',
            'ai_analyses_limit' => 100,
        ]);

        // ─── 2. Staff Users ───────────────────────────────────────────────────
        $admin = User::create([
            'institution_id' => $institution->id,
            'name' => 'Dr. Hernando Pérez Gómez',
            'email' => 'admin@colmayor.edu.co',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'document_type' => 'CC',
            'document_number' => '73100001',
        ]);

        User::create([
            'institution_id' => $institution->id,
            'name' => 'Lic. Sandra Milena Orozco',
            'email' => 'coordinador@colmayor.edu.co',
            'password' => bcrypt('password'),
            'role' => 'coordinator',
            'document_type' => 'CC',
            'document_number' => '73100002',
        ]);

        // ─── 3. Teacher Users + Teacher records ───────────────────────────────
        $luisUser = User::create([
            'institution_id' => $institution->id,
            'name' => 'Prof. Luis Fernando Díaz Herrera',
            'email' => 'docente.luis@colmayor.edu.co',
            'password' => bcrypt('password'),
            'role' => 'teacher',
            'document_type' => 'CC',
            'document_number' => '73100003',
        ]);
        $teacherLuis = Teacher::create([
            'user_id' => $luisUser->id,
            'institution_id' => $institution->id,
            'specialization' => 'Administración',
        ]);

        $anaUser = User::create([
            'institution_id' => $institution->id,
            'name' => 'Dra. Ana María Ospina Romero',
            'email' => 'docente.ana@colmayor.edu.co',
            'password' => bcrypt('password'),
            'role' => 'teacher',
            'document_type' => 'CC',
            'document_number' => '73100004',
        ]);
        $teacherAna = Teacher::create([
            'user_id' => $anaUser->id,
            'institution_id' => $institution->id,
            'specialization' => 'Economía y Contabilidad',
        ]);

        $ricardoUser = User::create([
            'institution_id' => $institution->id,
            'name' => 'Ing. Ricardo Andrés Torres Vega',
            'email' => 'docente.ricardo@colmayor.edu.co',
            'password' => bcrypt('password'),
            'role' => 'teacher',
            'document_type' => 'CC',
            'document_number' => '73100005',
        ]);
        $teacherRicardo = Teacher::create([
            'user_id' => $ricardoUser->id,
            'institution_id' => $institution->id,
            'specialization' => 'Sistemas e Informática',
        ]);

        // ─── 4. Academic Year ─────────────────────────────────────────────────
        $year = AcademicYear::create([
            'institution_id' => $institution->id,
            'year' => 2026,
            'start_date' => '2026-01-20',
            'end_date' => '2026-11-30',
            'is_active' => true,
        ]);

        // ─── 5. Periods (Cortes) ──────────────────────────────────────────────
        $corte1 = Period::create([
            'academic_year_id' => $year->id,
            'name' => 'Corte 1',
            'number' => 1,
            'weight' => 30,
            'start_date' => '2026-01-20',
            'end_date' => '2026-03-28',
            'is_closed' => true,
        ]);

        Period::create([
            'academic_year_id' => $year->id,
            'name' => 'Corte 2',
            'number' => 2,
            'weight' => 30,
            'start_date' => '2026-03-30',
            'end_date' => '2026-06-13',
            'is_closed' => false,
        ]);

        Period::create([
            'academic_year_id' => $year->id,
            'name' => 'Corte 3',
            'number' => 3,
            'weight' => 40,
            'start_date' => '2026-07-07',
            'end_date' => '2026-11-14',
            'is_closed' => false,
        ]);

        // ─── 6. Grades (Programas) ────────────────────────────────────────────
        $gradeAdm = Grade::create([
            'institution_id' => $institution->id,
            'name' => 'Administración de Empresas',
            'short_name' => 'ADM',
            'order' => 1,
            'level' => 'higher',
        ]);

        $gradeSis = Grade::create([
            'institution_id' => $institution->id,
            'name' => 'Tecnología en Sistemas de Información',
            'short_name' => 'SIS',
            'order' => 2,
            'level' => 'higher',
        ]);

        $gradeCon = Grade::create([
            'institution_id' => $institution->id,
            'name' => 'Contaduría Pública',
            'short_name' => 'CON',
            'order' => 3,
            'level' => 'higher',
        ]);

        // ─── 7. Groups (Semestres) ────────────────────────────────────────────
        $groupAdmSem1 = Group::create([
            'grade_id' => $gradeAdm->id,
            'academic_year_id' => $year->id,
            'name' => 'Semestre I',
            'capacity' => 30,
        ]);

        $groupAdmSem3 = Group::create([
            'grade_id' => $gradeAdm->id,
            'academic_year_id' => $year->id,
            'name' => 'Semestre III',
            'capacity' => 30,
        ]);

        $groupSisSem1 = Group::create([
            'grade_id' => $gradeSis->id,
            'academic_year_id' => $year->id,
            'name' => 'Semestre I',
            'capacity' => 30,
        ]);

        $groupConSem1 = Group::create([
            'grade_id' => $gradeCon->id,
            'academic_year_id' => $year->id,
            'name' => 'Semestre I',
            'capacity' => 30,
        ]);

        // ─── 8. Areas ─────────────────────────────────────────────────────────
        $areaAdm = Area::create([
            'institution_id' => $institution->id,
            'name' => 'Ciencias Administrativas',
            'order' => 1,
        ]);

        $areaSis = Area::create([
            'institution_id' => $institution->id,
            'name' => 'Tecnología e Informática',
            'order' => 2,
        ]);

        $areaCon = Area::create([
            'institution_id' => $institution->id,
            'name' => 'Ciencias Contables',
            'order' => 3,
        ]);

        $areaHum = Area::create([
            'institution_id' => $institution->id,
            'name' => 'Humanidades y Comunicación',
            'order' => 4,
        ]);

        // ─── 9. Subjects ──────────────────────────────────────────────────────
        // ADM Semestre I
        $subjIntroAdm = Subject::create([
            'area_id' => $areaAdm->id,
            'grade_id' => $gradeAdm->id,
            'name' => 'Introducción a la Administración',
            'credits' => 3,
            'weekly_hours' => 4,
            'order' => 1,
        ]);
        $subjFundEcon = Subject::create([
            'area_id' => $areaAdm->id,
            'grade_id' => $gradeAdm->id,
            'name' => 'Fundamentos de Economía',
            'credits' => 3,
            'weekly_hours' => 4,
            'order' => 2,
        ]);
        $subjComEmp = Subject::create([
            'area_id' => $areaHum->id,
            'grade_id' => $gradeAdm->id,
            'name' => 'Comunicación Empresarial',
            'credits' => 2,
            'weekly_hours' => 3,
            'order' => 3,
        ]);

        // ADM Semestre III — stored under same grade (ADM), differentiated by group
        $subjMatFin = Subject::create([
            'area_id' => $areaAdm->id,
            'grade_id' => $gradeAdm->id,
            'name' => 'Matemáticas Financieras',
            'credits' => 4,
            'weekly_hours' => 5,
            'order' => 4,
        ]);
        $subjGthh = Subject::create([
            'area_id' => $areaAdm->id,
            'grade_id' => $gradeAdm->id,
            'name' => 'Gestión del Talento Humano',
            'credits' => 3,
            'weekly_hours' => 4,
            'order' => 5,
        ]);

        // SIS Semestre I
        $subjProg = Subject::create([
            'area_id' => $areaSis->id,
            'grade_id' => $gradeSis->id,
            'name' => 'Programación Básica',
            'credits' => 4,
            'weekly_hours' => 6,
            'order' => 1,
        ]);
        $subjRedes = Subject::create([
            'area_id' => $areaSis->id,
            'grade_id' => $gradeSis->id,
            'name' => 'Redes y Comunicaciones',
            'credits' => 3,
            'weekly_hours' => 4,
            'order' => 2,
        ]);
        $subjIngles = Subject::create([
            'area_id' => $areaHum->id,
            'grade_id' => $gradeSis->id,
            'name' => 'Inglés Técnico',
            'credits' => 2,
            'weekly_hours' => 3,
            'order' => 3,
        ]);

        // CON Semestre I
        $subjContab = Subject::create([
            'area_id' => $areaCon->id,
            'grade_id' => $gradeCon->id,
            'name' => 'Contabilidad General',
            'credits' => 4,
            'weekly_hours' => 5,
            'order' => 1,
        ]);
        $subjLegCom = Subject::create([
            'area_id' => $areaCon->id,
            'grade_id' => $gradeCon->id,
            'name' => 'Legislación Comercial',
            'credits' => 3,
            'weekly_hours' => 4,
            'order' => 2,
        ]);

        // ─── 10. Teacher Assignments ──────────────────────────────────────────
        // Luis: Intro a la Adm (ADM SemI), Mat. Financieras (ADM SemIII)
        TeacherAssignment::create([
            'teacher_id' => $teacherLuis->id,
            'subject_id' => $subjIntroAdm->id,
            'group_id' => $groupAdmSem1->id,
            'academic_year_id' => $year->id,
        ]);
        TeacherAssignment::create([
            'teacher_id' => $teacherLuis->id,
            'subject_id' => $subjMatFin->id,
            'group_id' => $groupAdmSem3->id,
            'academic_year_id' => $year->id,
        ]);

        // Ana: Fund. Economía (ADM SemI), GTH (ADM SemIII), Contabilidad (CON SemI)
        TeacherAssignment::create([
            'teacher_id' => $teacherAna->id,
            'subject_id' => $subjFundEcon->id,
            'group_id' => $groupAdmSem1->id,
            'academic_year_id' => $year->id,
        ]);
        TeacherAssignment::create([
            'teacher_id' => $teacherAna->id,
            'subject_id' => $subjGthh->id,
            'group_id' => $groupAdmSem3->id,
            'academic_year_id' => $year->id,
        ]);
        TeacherAssignment::create([
            'teacher_id' => $teacherAna->id,
            'subject_id' => $subjContab->id,
            'group_id' => $groupConSem1->id,
            'academic_year_id' => $year->id,
        ]);

        // Ricardo: Programación Básica y Redes (SIS SemI)
        TeacherAssignment::create([
            'teacher_id' => $teacherRicardo->id,
            'subject_id' => $subjProg->id,
            'group_id' => $groupSisSem1->id,
            'academic_year_id' => $year->id,
        ]);
        TeacherAssignment::create([
            'teacher_id' => $teacherRicardo->id,
            'subject_id' => $subjRedes->id,
            'group_id' => $groupSisSem1->id,
            'academic_year_id' => $year->id,
        ]);

        // ─── 11. Students (10 — todos con group_id=NULL) ──────────────────────
        $studentsData = [
            ['email' => 'santiago.gomez@colmayor.edu.co',      'name' => 'Santiago Gómez Ríos',        'doc' => '1001234567', 'num' => 1],
            ['email' => 'mariafernanda.lopez@colmayor.edu.co', 'name' => 'María Fernanda López',       'doc' => '1001234568', 'num' => 2],
            ['email' => 'andres.martinez@colmayor.edu.co',     'name' => 'Andrés Felipe Martínez',     'doc' => '1001234569', 'num' => 3],
            ['email' => 'laura.pena@colmayor.edu.co',          'name' => 'Laura Cristina Peña',        'doc' => '1001234570', 'num' => 4],
            ['email' => 'diego.torres@colmayor.edu.co',        'name' => 'Diego Alejandro Torres',     'doc' => '1001234571', 'num' => 5],
            ['email' => 'valentina.rios@colmayor.edu.co',      'name' => 'Valentina Ríos Montoya',     'doc' => '1001234572', 'num' => 6],
            ['email' => 'camilo.herrera@colmayor.edu.co',      'name' => 'Camilo Herrera Díaz',        'doc' => '1001234573', 'num' => 7],
            ['email' => 'isabella.moreno@colmayor.edu.co',     'name' => 'Isabella Moreno Castro',     'doc' => '1001234574', 'num' => 8],
            ['email' => 'juandavid.castro@colmayor.edu.co',    'name' => 'Juan David Castro Vargas',   'doc' => '1001234575', 'num' => 9],
            ['email' => 'sofia.vargas@colmayor.edu.co',        'name' => 'Sofía Alejandra Vargas',     'doc' => '1001234576', 'num' => 10],
        ];

        $studentObjects = [];
        foreach ($studentsData as $data) {
            $studentUser = User::create([
                'institution_id' => $institution->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'role' => 'student',
                'document_type' => 'CC',
                'document_number' => $data['doc'],
            ]);

            $student = Student::create([
                'user_id' => $studentUser->id,
                'group_id' => null,
                'enrollment_code' => 'CM-2026-'.str_pad($data['num'], 3, '0', STR_PAD_LEFT),
                'enrollment_date' => '2026-01-20',
                'status' => 'active',
            ]);

            $studentObjects[$data['num']] = $student;
        }

        [$santiago, $mariaF, $andres, $laura, $diego, $valentina, $camilo, $isabella, $juanDavid, $sofia] =
            array_values($studentObjects);

        // ─── 12. Enrollments ──────────────────────────────────────────────────

        // ── Santiago (ADM SemI) — matrícula completa, todas activas ──────────
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $santiago->id,
            'subject_id' => $subjIntroAdm->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $santiago->id,
            'subject_id' => $subjFundEcon->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $santiago->id,
            'subject_id' => $subjComEmp->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);

        // ── María Fernanda (ADM SemI) — retiro de una materia ─────────────────
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $mariaF->id,
            'subject_id' => $subjIntroAdm->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $mariaF->id,
            'subject_id' => $subjFundEcon->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $mariaF->id,
            'subject_id' => $subjComEmp->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'withdrawn',
        ]);

        // ── Andrés (ADM SemI) — matriculado con notas del Corte 1 ────────────
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $andres->id,
            'subject_id' => $subjIntroAdm->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $andres->id,
            'subject_id' => $subjFundEcon->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $andres->id,
            'subject_id' => $subjComEmp->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        // GradeRecords Corte 1
        GradeRecord::create([
            'student_id' => $andres->id,
            'subject_id' => $subjIntroAdm->id,
            'period_id' => $corte1->id,
            'teacher_id' => $teacherLuis->id,
            'grade' => 4.2,
        ]);
        GradeRecord::create([
            'student_id' => $andres->id,
            'subject_id' => $subjFundEcon->id,
            'period_id' => $corte1->id,
            'teacher_id' => $teacherAna->id,
            'grade' => 3.8,
        ]);
        GradeRecord::create([
            'student_id' => $andres->id,
            'subject_id' => $subjComEmp->id,
            'period_id' => $corte1->id,
            'teacher_id' => $teacherLuis->id,
            'grade' => 4.5,
        ]);

        // ── Laura (SemI completado + SemIII actual) ────────────────────────────
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $laura->id,
            'subject_id' => $subjIntroAdm->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'completed',
            'final_grade' => 4.1,
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $laura->id,
            'subject_id' => $subjFundEcon->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'completed',
            'final_grade' => 3.7,
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $laura->id,
            'subject_id' => $subjComEmp->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'completed',
            'final_grade' => 4.8,
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $laura->id,
            'subject_id' => $subjMatFin->id,
            'academic_year_id' => $year->id,
            'semester_number' => 2,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $laura->id,
            'subject_id' => $subjGthh->id,
            'academic_year_id' => $year->id,
            'semester_number' => 2,
            'status' => 'enrolled',
        ]);

        // ── Diego (ADM SemI) — reprobó una materia y re-matriculó ─────────────
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $diego->id,
            'subject_id' => $subjIntroAdm->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $diego->id,
            'subject_id' => $subjFundEcon->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'failed',
            'final_grade' => 2.1,
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $diego->id,
            'subject_id' => $subjFundEcon->id,
            'academic_year_id' => $year->id,
            'semester_number' => 2,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $diego->id,
            'subject_id' => $subjComEmp->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        // GradeRecord: Fund. Economía Corte 1 (nota que lo llevó a reprobar)
        GradeRecord::create([
            'student_id' => $diego->id,
            'subject_id' => $subjFundEcon->id,
            'period_id' => $corte1->id,
            'teacher_id' => $teacherAna->id,
            'grade' => 1.8,
            'observations' => 'Rendimiento insuficiente. No alcanzó los mínimos requeridos.',
            'recommendations' => 'Requiere nivelación antes de continuar con materias avanzadas.',
        ]);

        // ── Valentina (SIS SemI) — matrícula completa SIS ────────────────────
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $valentina->id,
            'subject_id' => $subjProg->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $valentina->id,
            'subject_id' => $subjRedes->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $valentina->id,
            'subject_id' => $subjIngles->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);

        // ── Camilo (CON SemI) — matrícula completa CON ────────────────────────
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $camilo->id,
            'subject_id' => $subjContab->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $camilo->id,
            'subject_id' => $subjLegCom->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);

        // ── Isabella — SIN matrículas (admitida pero no matriculada) ──────────
        // No se crean enrollments para Isabella ($isabella variable not used further)

        // ── Juan David (SIS SemI) — matriculado con notas Y asistencia ────────
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $juanDavid->id,
            'subject_id' => $subjProg->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $juanDavid->id,
            'subject_id' => $subjRedes->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $juanDavid->id,
            'subject_id' => $subjIngles->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        // GradeRecords Corte 1
        GradeRecord::create([
            'student_id' => $juanDavid->id,
            'subject_id' => $subjProg->id,
            'period_id' => $corte1->id,
            'teacher_id' => $teacherRicardo->id,
            'grade' => 3.5,
        ]);
        GradeRecord::create([
            'student_id' => $juanDavid->id,
            'subject_id' => $subjRedes->id,
            'period_id' => $corte1->id,
            'teacher_id' => $teacherRicardo->id,
            'grade' => 4.0,
        ]);
        GradeRecord::create([
            'student_id' => $juanDavid->id,
            'subject_id' => $subjIngles->id,
            'period_id' => $corte1->id,
            'teacher_id' => $teacherRicardo->id,
            'grade' => 3.9,
        ]);
        // Attendance: 5 días recientes para Juan David
        $attendanceDays = [
            ['date' => '2026-03-02', 'status' => 'present'],
            ['date' => '2026-03-03', 'status' => 'present'],
            ['date' => '2026-03-04', 'status' => 'late'],
            ['date' => '2026-03-05', 'status' => 'present'],
            ['date' => '2026-03-06', 'status' => 'absent'],
        ];
        foreach ($attendanceDays as $day) {
            Attendance::create([
                'student_id' => $juanDavid->id,
                'group_id' => $groupSisSem1->id,
                'period_id' => $corte1->id,
                'date' => $day['date'],
                'status' => $day['status'],
                'observation' => $day['status'] === 'absent' ? 'No se presentó sin justificación.' : null,
                'registered_by' => $ricardoUser->id,
            ]);
        }

        // ── Sofía (ADM SemIII) — estudiante de semestre avanzado ─────────────
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $sofia->id,
            'subject_id' => $subjMatFin->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'institution_id' => $institution->id,
            'student_id' => $sofia->id,
            'subject_id' => $subjGthh->id,
            'academic_year_id' => $year->id,
            'semester_number' => 1,
            'status' => 'enrolled',
        ]);

        // ─── Summary ──────────────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('===================================================');
        $this->command->info('  COLEGIO MAYOR DE CARTAGENA — DEMO LISTA');
        $this->command->info('===================================================');
        $this->command->newLine();
        $this->command->info('  Institucion: '.$institution->name);
        $this->command->info('  Programas:   3 (ADM, SIS, CON)');
        $this->command->info('  Grupos:      4 semestres');
        $this->command->info('  Docentes:    3');
        $this->command->info('  Estudiantes: 10 (todos group_id=NULL)');
        $this->command->info('  Matriculas:  con edge cases cubiertos');
        $this->command->newLine();
        $this->command->info('  Credenciales:');
        $this->command->info('    Admin:       admin@colmayor.edu.co / password');
        $this->command->info('    Coordinador: coordinador@colmayor.edu.co / password');
        $this->command->info('    Docente:     docente.luis@colmayor.edu.co / password');
        $this->command->info('    Docente:     docente.ana@colmayor.edu.co / password');
        $this->command->info('    Docente:     docente.ricardo@colmayor.edu.co / password');
        $this->command->info('    Estudiante:  santiago.gomez@colmayor.edu.co / password');
        $this->command->newLine();
        $this->command->info('===================================================');
    }
}
