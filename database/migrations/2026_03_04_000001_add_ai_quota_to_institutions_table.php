<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->unsignedInteger('ai_analyses_limit')->default(200)->after('grading_scale');
            $table->unsignedInteger('ai_analyses_used')->default(0)->after('ai_analyses_limit');
            $table->date('ai_quota_resets_at')->nullable()->after('ai_analyses_used');
        });
    }

    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn(['ai_analyses_limit', 'ai_analyses_used', 'ai_quota_resets_at']);
        });
    }
};
