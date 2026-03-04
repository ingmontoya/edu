<?php

use App\Http\Controllers\Api\AcademicYearController;
use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\DisciplinaryController;
use App\Http\Controllers\Api\GradeActivityController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\GradeRecordController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\GuardianController;
use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\PeriodController;
use App\Http\Controllers\Api\PortalController;
use App\Http\Controllers\Api\RemedialController;
use App\Http\Controllers\Api\ReportCardController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\StudentTaskController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Sistema de Gestión Académica para Colegios - MVP
|
*/

// ============ Public Routes ============
Route::get('/health', fn () => response()->json(['status' => 'ok', 'timestamp' => now()]));
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// ============ Protected Routes ============
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

    // Auth (no tenant scope needed)
    Route::prefix('auth')->withoutMiddleware('tenant')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/password', [AuthController::class, 'changePassword']);
    });

    // Institution
    Route::get('/institution', [InstitutionController::class, 'show']);
    Route::put('/institution', [InstitutionController::class, 'update']);
    Route::post('/institution/logo', [InstitutionController::class, 'uploadLogo']);

    // Academic Years
    Route::apiResource('academic-years', AcademicYearController::class);
    Route::post('academic-years/{academicYear}/activate', [AcademicYearController::class, 'activate']);

    // Periods
    Route::get('periods', [PeriodController::class, 'index']);
    Route::apiResource('academic-years.periods', PeriodController::class)->shallow();
    Route::post('periods/{period}/close', [PeriodController::class, 'close']);
    Route::post('periods/{period}/open', [PeriodController::class, 'open']);

    // Grades (grados escolares)
    Route::apiResource('grades', GradeController::class);

    // Groups
    Route::apiResource('groups', GroupController::class);
    Route::get('groups/{group}/students', [GroupController::class, 'students']);
    Route::post('groups/{group}/assign-director', [GroupController::class, 'assignDirector']);

    // Areas & Subjects
    Route::apiResource('areas', AreaController::class);
    Route::apiResource('subjects', SubjectController::class);

    // SIMAT Export
    Route::get('students/simat-export', [StudentController::class, 'simatExport']);

    // Students
    Route::apiResource('students', StudentController::class);
    Route::get('students/{student}/grades', [StudentController::class, 'grades']);
    Route::get('students/{student}/attendance', [StudentController::class, 'attendance']);
    Route::post('students/{student}/assign-guardian', [StudentController::class, 'assignGuardian']);
    Route::post('students/import', [StudentController::class, 'importFromCsv']);

    // Certificates
    Route::get('certificates/student/{student}/enrollment', [CertificateController::class, 'enrollmentPdf']);
    Route::get('certificates/student/{student}/grades', [CertificateController::class, 'gradesPdf']);

    // Teachers
    Route::apiResource('teachers', TeacherController::class);
    Route::get('teachers/{teacher}/assignments', [TeacherController::class, 'assignments']);
    Route::post('teachers/{teacher}/assign', [TeacherController::class, 'assign']);
    Route::delete('teachers/{teacher}/unassign/{assignment}', [TeacherController::class, 'unassign']);
    Route::post('teachers/import', [TeacherController::class, 'importFromCsv']);

    // Guardians
    Route::apiResource('guardians', GuardianController::class);
    Route::get('guardians/{guardian}/students', [GuardianController::class, 'students']);

    // Grade Activities (Actividades Evaluativas)
    Route::get('grade-activities', [GradeActivityController::class, 'index']);
    Route::post('grade-activities', [GradeActivityController::class, 'store']);
    Route::put('grade-activities/{gradeActivity}', [GradeActivityController::class, 'update']);
    Route::delete('grade-activities/{gradeActivity}', [GradeActivityController::class, 'destroy']);
    Route::get('grade-activities/{gradeActivity}/scores', [GradeActivityController::class, 'scores']);
    Route::post('grade-activities/{gradeActivity}/scores/bulk', [GradeActivityController::class, 'bulkScores']);

    // Grade Records (Notas)
    Route::get('grade-records', [GradeRecordController::class, 'index']);
    Route::post('grade-records/bulk', [GradeRecordController::class, 'bulkStore']);
    Route::put('grade-records/{gradeRecord}', [GradeRecordController::class, 'update']);
    Route::get('grade-records/by-student/{student}', [GradeRecordController::class, 'byStudent']);
    Route::get('grade-records/worksheet', [GradeRecordController::class, 'worksheet']);

    // Attendance (Asistencia)
    Route::get('attendance', [AttendanceController::class, 'index']);
    Route::post('attendance/bulk', [AttendanceController::class, 'bulkStore']);
    Route::put('attendance/{attendance}', [AttendanceController::class, 'update']);
    Route::get('attendance/report', [AttendanceController::class, 'report']);
    Route::get('attendance/daily/{group}', [AttendanceController::class, 'daily']);

    // Report Cards (Boletines)
    Route::get('report-cards/student/{student}/period/{period}', [ReportCardController::class, 'show']);
    Route::get('report-cards/student/{student}/period/{period}/pdf', [ReportCardController::class, 'pdf']);
    Route::get('report-cards/group/{group}/period/{period}/pdf', [ReportCardController::class, 'bulkPdf']);

    // Reports
    Route::get('reports/consolidation', [ReportController::class, 'consolidation']);
    Route::get('reports/failing-students', [ReportController::class, 'failingStudents']);
    Route::get('reports/attendance-summary', [ReportController::class, 'attendanceSummary']);
    Route::get('reports/risk-scores', [ReportController::class, 'riskScores']);

    // AI Insights (Claude / Ollama)
    Route::post('reports/ai/student-analysis', [ReportController::class, 'aiStudentAnalysis']);
    Route::post('reports/ai/weekly-summary', [ReportController::class, 'aiWeeklySummary']);
    Route::get('students/{student}/ai-analyses', [ReportController::class, 'studentAiAnalyses']);

    // Announcements
    Route::apiResource('announcements', AnnouncementController::class);
    Route::post('announcements/{announcement}/publish', [AnnouncementController::class, 'publish']);

    // ============ SIEE - Sistema Institucional de Evaluación ============

    // Achievements (Logros)
    Route::prefix('achievements')->group(function () {
        Route::get('/', [AchievementController::class, 'index']);
        Route::post('/', [AchievementController::class, 'store']);
        Route::put('/{achievement}', [AchievementController::class, 'update']);
        Route::delete('/{achievement}', [AchievementController::class, 'destroy']);
        Route::post('/{achievement}/indicators', [AchievementController::class, 'addIndicator']);
        Route::post('/record', [AchievementController::class, 'recordStudentAchievement']);
        Route::post('/bulk-record', [AchievementController::class, 'bulkRecordAchievements']);
        Route::post('/copy', [AchievementController::class, 'copyAchievements']);
        Route::post('/import', [AchievementController::class, 'importFromCsv']);
    });
    Route::get('students/{student}/achievements', [AchievementController::class, 'studentAchievements']);

    // Remedial Activities (Nivelaciones/Recuperaciones)
    Route::prefix('remedials')->group(function () {
        Route::get('/', [RemedialController::class, 'index']);
        Route::post('/', [RemedialController::class, 'store']);
        Route::get('/{remedialActivity}', [RemedialController::class, 'show']);
        Route::put('/{remedialActivity}', [RemedialController::class, 'update']);
        Route::delete('/{remedialActivity}', [RemedialController::class, 'destroy']);
        Route::post('/{remedialActivity}/assign-students', [RemedialController::class, 'assignStudents']);
        Route::post('/{remedialActivity}/auto-assign', [RemedialController::class, 'autoAssignFailingStudents']);
        Route::post('/{remedialActivity}/bulk-grade', [RemedialController::class, 'bulkGrade']);
        Route::get('/students-needing', [RemedialController::class, 'studentsNeedingRemedial']);
    });
    Route::put('student-remedials/{studentRemedial}/grade', [RemedialController::class, 'gradeStudent']);
    Route::get('students/{student}/remedials', [RemedialController::class, 'studentRemedials']);

    // Disciplinary (Convivencia Escolar - Ley 1620/2013)
    Route::apiResource('disciplinary', DisciplinaryController::class);
    Route::get('students/{student}/disciplinary', [DisciplinaryController::class, 'studentHistory']);

    // Tasks (Tareas)
    Route::get('tasks', [TaskController::class, 'index']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::get('tasks/{task}', [TaskController::class, 'show']);
    Route::post('tasks/{task}', [TaskController::class, 'update']); // POST for multipart
    Route::delete('tasks/{task}', [TaskController::class, 'destroy']);
    Route::get('tasks/{task}/attachment', [TaskController::class, 'downloadAttachment']);

    // Student task submissions
    Route::post('student-tasks/{studentTask}/submit', [StudentTaskController::class, 'submit']);
    Route::get('student-tasks/{studentTask}/download', [StudentTaskController::class, 'downloadSubmission']);
    Route::put('student-tasks/{studentTask}/review', [StudentTaskController::class, 'review']);

    // Portal Acudiente (rutas específicas)
    Route::prefix('guardian')->group(function () {
        Route::get('students', [PortalController::class, 'students']);
        Route::get('students/{student}', [PortalController::class, 'studentDetail']);
        Route::get('students/{student}/grades', [PortalController::class, 'grades']);
        Route::get('students/{student}/attendance', [PortalController::class, 'attendance']);
        Route::get('students/{student}/report-card/{period}/pdf', [PortalController::class, 'reportCardPdf']);
        Route::get('students/{student}/certificates/enrollment', [CertificateController::class, 'enrollmentPdf']);
        Route::get('students/{student}/certificates/grades', [CertificateController::class, 'gradesPdf']);
        Route::get('announcements', [PortalController::class, 'announcements']);
        Route::get('tasks', [PortalController::class, 'tasks']);
    });
});
