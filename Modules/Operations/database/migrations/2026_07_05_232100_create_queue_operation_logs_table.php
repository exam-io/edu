<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('queue_operation_logs')) {
            return;
        }

        Schema::create('queue_operation_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('operation', 120);
            $table->string('status', 20)->default('completed');
            $table->json('meta')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'operation'], 'ops_queue_tenant_op_idx');
            $table->index(['tenant_id', 'executed_at'], 'ops_queue_tenant_exec_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_operation_logs');
    }
};
