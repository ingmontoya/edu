<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->constrained()->cascadeOnDelete();
            $table->string('risk_level'); // high|medium|low
            $table->decimal('risk_score', 5, 1)->default(0);
            $table->text('narrative');
            $table->json('recommendations');
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['student_id', 'period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_ai_analyses');
    }
};
