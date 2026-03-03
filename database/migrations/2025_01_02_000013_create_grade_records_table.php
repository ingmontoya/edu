<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->decimal('grade', 3, 1)->nullable();
            $table->text('observations')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_records');
    }
};
