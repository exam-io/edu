<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('audit_logs')) {
            return;
        }

        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor_type', 60);
            $table->string('action', 120);
            $table->string('resource_type', 120);
            $table->string('resource_id', 120)->nullable();
            $table->json('before_state')->nullable();
            $table->json('after_state')->nullable();
            $table->json('context')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'action'], 'audit_tenant_action_idx');
            $table->index(['tenant_id', 'occurred_at'], 'audit_tenant_occurred_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
