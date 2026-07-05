<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('report_exports')) {
            return;
        }

        Schema::create('report_exports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('report_execution_id')->constrained('report_executions')->cascadeOnDelete();
            $table->string('format', 20);
            $table->string('file_path', 500);
            $table->string('status', 40)->default('ready');
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'format', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_exports');
    }
};
