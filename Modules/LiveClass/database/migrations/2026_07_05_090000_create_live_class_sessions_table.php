<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('live_class_sessions')) {
            return;
        }

        Schema::create('live_class_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->foreignId('host_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->string('provider', 32)->default('jitsi');
            $table->string('provider_meeting_id', 190)->nullable();
            $table->string('room_name', 190)->nullable();
            $table->string('meeting_url', 500)->nullable();
            $table->string('meeting_password', 120)->nullable();
            $table->timestamp('scheduled_start_at');
            $table->timestamp('scheduled_end_at');
            $table->timestamp('actual_start_at')->nullable();
            $table->timestamp('actual_end_at')->nullable();
            $table->string('attendance_policy', 32)->default('open');
            $table->unsignedInteger('max_participants')->nullable();
            $table->string('status', 32)->default('scheduled');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'scheduled_start_at', 'scheduled_end_at']);
            $table->index(['tenant_id', 'class_id', 'section_id']);
            $table->index(['tenant_id', 'provider_meeting_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_class_sessions');
    }
};
