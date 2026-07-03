<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lms_course_enrollments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('enrolled_at');
            $table->string('status', 32)->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'course_id']);
            $table->index(['tenant_id', 'student_id']);
            $table->index(['tenant_id', 'status']);
            $table->unique(['tenant_id', 'course_id', 'student_id'], 'lms_enrollment_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_course_enrollments');
    }
};
