<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_generation_outputs')) {
            return;
        }

        Schema::create('ai_generation_outputs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('ai_generation_request_id')->constrained('ai_generation_requests')->cascadeOnDelete();
            $table->string('output_type', 32);
            $table->string('title', 255)->nullable();
            $table->longText('body')->nullable();
            $table->json('structured_payload')->nullable();
            $table->string('model_name', 120)->nullable();
            $table->unsignedInteger('token_usage_input')->nullable();
            $table->unsignedInteger('token_usage_output')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'output_type']);
            $table->index(['tenant_id', 'ai_generation_request_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_generation_outputs');
    }
};
