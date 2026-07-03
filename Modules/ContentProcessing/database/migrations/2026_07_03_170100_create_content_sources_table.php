<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('content_sources')) {
            return;
        }

        Schema::create('content_sources', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 255);
            $table->string('source_type', 32);
            $table->string('source_ref', 1024)->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->string('status', 32)->default('queued');
            $table->json('meta')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'source_type']);
            $table->index(['tenant_id', 'uploaded_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_sources');
    }
};
