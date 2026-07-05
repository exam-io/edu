<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('backup_executions')) {
            return;
        }

        Schema::create('backup_executions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('status', 20)->default('running');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('storage_disk', 40)->nullable();
            $table->string('storage_path')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status'], 'ops_backup_tenant_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_executions');
    }
};
