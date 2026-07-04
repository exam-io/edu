<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('assessment_assignments')) {
            return;
        }

        Schema::create('assessment_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('academic_session_id')->nullable()->constrained('academic_sessions')->nullOnDelete();
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->timestamps();

            $table->index(['tenant_id', 'assessment_id']);
            $table->index(['tenant_id', 'academic_session_id']);
            $table->unique([
                'assessment_id',
                'academic_session_id',
                'program_id',
                'class_id',
                'section_id',
                'batch_id',
            ], 'assessment_assignments_unique_scope');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_assignments');
    }
};
