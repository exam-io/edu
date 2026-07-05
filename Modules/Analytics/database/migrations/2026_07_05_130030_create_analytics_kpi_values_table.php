<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('analytics_kpi_values')) {
            return;
        }

        Schema::create('analytics_kpi_values', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('kpi_definition_id')->constrained('analytics_kpi_definitions')->cascadeOnDelete();
            $table->decimal('kpi_value', 14, 4)->default(0);
            $table->timestamp('period_start');
            $table->timestamp('period_end');
            $table->timestamp('computed_at');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'kpi_definition_id', 'period_start', 'period_end'], 'analytics_kpi_values_period_unique');
            $table->index(['tenant_id', 'computed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_kpi_values');
    }
};
