<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('report_schedules')) {
            return;
        }

        Schema::create('report_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('report_template_id')->constrained('report_templates')->cascadeOnDelete();
            $table->string('frequency', 40);
            $table->timestamp('next_run_at');
            $table->boolean('is_active')->default(true);
            $table->json('filters')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'is_active', 'next_run_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_schedules');
    }
};
