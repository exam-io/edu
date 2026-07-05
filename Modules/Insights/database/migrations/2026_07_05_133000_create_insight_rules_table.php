<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('insight_rules')) {
            return;
        }

        Schema::create('insight_rules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name', 180);
            $table->string('metric_key', 120);
            $table->string('operator', 20);
            $table->decimal('threshold', 14, 4);
            $table->string('severity', 20)->default('medium');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'metric_key', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insight_rules');
    }
};
