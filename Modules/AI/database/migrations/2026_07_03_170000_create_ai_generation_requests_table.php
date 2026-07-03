<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_generation_requests')) {
            return;
        }

        Schema::create('ai_generation_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('content_source_id')->nullable();
            $table->string('generation_type', 32);
            $table->string('status', 32)->default('queued');
            $table->longText('prompt_text')->nullable();
            $table->json('options')->nullable();
            $table->string('error_message', 1000)->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'generation_type']);
            $table->index(['tenant_id', 'content_source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_generation_requests');
    }
};
