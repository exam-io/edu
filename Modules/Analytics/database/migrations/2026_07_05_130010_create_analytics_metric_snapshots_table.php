<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('analytics_metric_snapshots')) {
            return;
        }

        Schema::create('analytics_metric_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('metric_key', 120);
            $table->string('dimension_key', 120)->nullable();
            $table->string('dimension_value', 120)->nullable();
            $table->decimal('metric_value', 14, 4)->default(0);
            $table->timestamp('period_start');
            $table->timestamp('period_end');
            $table->timestamp('generated_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'metric_key', 'generated_at'], 'ams_tenant_metric_generated_idx');
            $table->index(['tenant_id', 'dimension_key', 'dimension_value'], 'ams_tenant_dimension_idx');
            $table->index(['tenant_id', 'period_start', 'period_end'], 'ams_tenant_period_range_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_metric_snapshots');
    }
};
