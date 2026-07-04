<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('assessment_attempts')) {
            return;
        }

        Schema::table('assessment_attempts', function (Blueprint $table): void {
            $table->index(
                ['tenant_id', 'assessment_id', 'student_id', 'status'],
                'assessment_attempts_tenant_assessment_student_status_index'
            );
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('assessment_attempts')) {
            return;
        }

        Schema::table('assessment_attempts', function (Blueprint $table): void {
            $table->dropIndex('assessment_attempts_tenant_assessment_student_status_index');
        });
    }
};
