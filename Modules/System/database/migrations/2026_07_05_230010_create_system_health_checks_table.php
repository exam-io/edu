<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('system_health_checks')) {
            return;
        }

        Schema::create('system_health_checks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('check_name', 100);
            $table->string('status', 20);
            $table->timestamp('checked_at');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'check_name'], 'sys_health_tenant_check_idx');
            $table->index(['tenant_id', 'checked_at'], 'sys_health_tenant_time_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_health_checks');
    }
};
