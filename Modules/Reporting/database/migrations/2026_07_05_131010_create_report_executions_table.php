<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('report_executions')) {
            return;
        }

        Schema::create('report_executions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('report_template_id')->constrained('report_templates')->cascadeOnDelete();
            $table->string('status', 40)->default('queued');
            $table->json('filters')->nullable();
            $table->json('result_payload')->nullable();
            $table->unsignedInteger('row_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_executions');
    }
};
