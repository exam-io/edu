<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tenant_notifications')) {
            return;
        }

        Schema::create('tenant_notifications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('notification_type', 64)->default('general');
            $table->string('title', 255);
            $table->text('body')->nullable();
            $table->string('status', 32)->default('queued');
            $table->json('channels')->nullable();
            $table->json('data')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'user_id', 'status']);
            $table->index(['tenant_id', 'notification_type']);
            $table->index(['tenant_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_notifications');
    }
};
