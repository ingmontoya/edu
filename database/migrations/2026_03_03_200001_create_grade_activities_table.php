<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['quiz', 'tarea', 'participacion', 'sustentacion', 'examen', 'proyecto', 'otro']);
            $table->decimal('weight', 5, 2)->default(100);
            $table->date('date')->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            $table->index(['subject_id', 'period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_activities');
    }
};
