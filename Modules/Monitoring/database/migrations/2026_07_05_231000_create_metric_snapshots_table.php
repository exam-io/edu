<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('metric_snapshots')) {
            return;
        }

        Schema::create('metric_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('metric_key', 120);
            $table->string('period_key', 40);
            $table->decimal('value', 14, 4)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'metric_key'], 'mon_metric_tenant_key_idx');
            $table->index(['tenant_id', 'period_key'], 'mon_metric_tenant_period_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metric_snapshots');
    }
};
