<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('generated_insights')) {
            return;
        }

        Schema::create('generated_insights', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('insight_rule_id')->nullable()->constrained('insight_rules')->nullOnDelete();
            $table->string('title', 180);
            $table->text('summary');
            $table->string('severity', 20)->default('medium');
            $table->json('context_payload')->nullable();
            $table->timestamp('generated_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'severity', 'generated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_insights');
    }
};
