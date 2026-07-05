<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('report_templates')) {
            return;
        }

        Schema::create('report_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name', 180);
            $table->string('slug', 180);
            $table->string('source', 120);
            $table->json('definition');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'source', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_templates');
    }
};
