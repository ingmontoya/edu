<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplinary_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('period_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // type1, type2, type3
            $table->string('category'); // verbal, physical, psychological, cyberbullying, other
            $table->text('description');
            $table->date('date');
            $table->string('location')->nullable();
            $table->text('witnesses')->nullable();
            $table->text('action_taken')->nullable();
            $table->string('status')->default('open'); // open, in_process, resolved, escalated
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->boolean('notify_guardian')->default(true);
            $table->text('commitment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplinary_records');
    }
};
