<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('alert_incidents')) {
            return;
        }

        Schema::create('alert_incidents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('metric_key', 120);
            $table->string('severity', 20);
            $table->string('status', 20)->default('open');
            $table->timestamp('triggered_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status'], 'mon_incident_tenant_status_idx');
            $table->index(['tenant_id', 'metric_key'], 'mon_incident_tenant_metric_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_incidents');
    }
};
