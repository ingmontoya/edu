<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nit')->nullable();
            $table->string('dane_code')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('department')->nullable();
            $table->string('rector_name')->nullable();
            $table->json('grading_scale')->nullable();
            $table->timestamps();
        });

        // Add foreign key to users table now that institutions exists
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('institution_id')
                ->references('id')
                ->on('institutions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['institution_id']);
        });
        Schema::dropIfExists('institutions');
    }
};
