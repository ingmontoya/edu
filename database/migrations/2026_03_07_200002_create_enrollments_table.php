<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('semester_number')->unsigned()->default(1);
            $table->enum('status', ['enrolled', 'withdrawn', 'completed', 'failed'])->default('enrolled');
            $table->decimal('final_grade', 4, 2)->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'academic_year_id', 'semester_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
