<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_enrollments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->date('enrollment_date');
            $table->string('status', 32)->default('active');
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('student_id');
            $table->index('academic_session_id');
            $table->index('class_id');
            $table->index('status');
            $table->unique(['tenant_id', 'student_id', 'academic_session_id', 'status'], 'uniq_active_enroll_student_session_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_enrollments');
    }
};
