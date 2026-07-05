<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('alert_rules')) {
            return;
        }

        Schema::create('alert_rules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('metric_key', 120);
            $table->string('operator', 4)->default('>=');
            $table->decimal('threshold', 14, 4);
            $table->string('severity', 20)->default('warning');
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'metric_key'], 'mon_rule_tenant_key_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_rules');
    }
};
