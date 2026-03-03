<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Actividades de nivelación/recuperación (SIEE)
        Schema::create('remedial_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->text('instructions')->nullable(); // Instrucciones para el estudiante
            $table->string('type')->default('recovery'); // recovery, reinforcement, leveling
            $table->date('assigned_date');
            $table->date('due_date');
            $table->decimal('max_grade', 3, 1)->default(3.0); // Nota máxima alcanzable (típicamente 3.0)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Asignación de actividades de nivelación a estudiantes
        Schema::create('student_remedials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('remedial_activity_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, submitted, graded, excused
            $table->decimal('grade', 3, 1)->nullable(); // Nota obtenida
            $table->text('submission_notes')->nullable(); // Notas del estudiante
            $table->text('teacher_feedback')->nullable(); // Retroalimentación del docente
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'remedial_activity_id']);
        });

        // Historial de promociones y decisiones del consejo académico
        Schema::create('promotion_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('decision'); // promoted, retained, early_promoted, conditional
            $table->text('observations')->nullable();
            $table->json('failed_subjects')->nullable(); // Asignaturas perdidas
            $table->integer('failed_count')->default(0);
            $table->decimal('final_average', 3, 1)->nullable();
            $table->string('performance_level')->nullable();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_records');
        Schema::dropIfExists('student_remedials');
        Schema::dropIfExists('remedial_activities');
    }
};
