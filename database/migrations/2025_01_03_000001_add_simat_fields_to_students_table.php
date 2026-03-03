<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('simat_code')->nullable()->after('enrollment_code');
            $table->tinyInteger('stratum')->nullable()->after('simat_code');
            $table->string('health_insurer')->nullable()->after('stratum');
            $table->string('ethnicity')->nullable()->after('health_insurer');
            $table->string('disability_type')->nullable()->after('ethnicity');
            $table->string('municipality')->nullable()->after('disability_type');
            $table->string('birth_municipality')->nullable()->after('municipality');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'simat_code', 'stratum', 'health_insurer',
                'ethnicity', 'disability_type', 'municipality', 'birth_municipality',
            ]);
        });
    }
};
