<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('live_class_attendances')) {
            return;
        }

        Schema::create('live_class_attendances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('live_class_session_id')->constrained('live_class_sessions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->string('attendance_status', 32)->default('joined');
            $table->string('source', 32)->default('web');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'live_class_session_id', 'student_id'], 'live_class_attendance_unique_student');
            $table->index(['tenant_id', 'live_class_session_id']);
            $table->index(['tenant_id', 'student_id', 'attendance_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_class_attendances');
    }
};
