<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Logros por asignatura y período (SIEE)
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->constrained()->cascadeOnDelete();
            $table->string('code')->nullable(); // L1, L2, L3...
            $table->text('description'); // Descripción del logro
            $table->string('type')->default('cognitive'); // cognitive, procedural, attitudinal
            $table->integer('order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['subject_id', 'period_id', 'code']);
        });

        // Indicadores de logro (criterios específicos)
        Schema::create('achievement_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->string('code')->nullable(); // I1, I2...
            $table->text('description');
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        // Registro del alcance de logros por estudiante
        Schema::create('student_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, achieved, in_progress, not_achieved
            $table->text('observations')->nullable();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'achievement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_achievements');
        Schema::dropIfExists('achievement_indicators');
        Schema::dropIfExists('achievements');
    }
};
