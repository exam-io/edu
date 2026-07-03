<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lms_learning_progress', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('content_item_id')->nullable()->constrained('content_items')->nullOnDelete();
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->unsignedInteger('completed_items')->default(0);
            $table->unsignedInteger('total_items')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('status', 32)->default('in_progress');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'course_id']);
            $table->index(['tenant_id', 'student_id']);
            $table->index(['tenant_id', 'status']);
            $table->unique(['tenant_id', 'course_id', 'student_id'], 'lms_progress_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_learning_progress');
    }
};
