<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('usage_counters')) {
            return;
        }

        Schema::create('usage_counters', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('metric_key', 120);
            $table->string('period_key', 30);
            $table->decimal('counter', 14, 4)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'metric_key', 'period_key'], 'usage_counter_tenant_metric_period_unique');
            $table->index(['tenant_id', 'period_key'], 'usage_counter_tenant_period_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_counters');
    }
};
