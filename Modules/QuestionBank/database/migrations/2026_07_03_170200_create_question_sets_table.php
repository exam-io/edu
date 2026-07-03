<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('question_sets')) {
            return;
        }

        Schema::create('question_sets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('ai_generation_request_id')->nullable()->constrained('ai_generation_requests')->nullOnDelete();
            $table->unsignedBigInteger('content_source_id')->nullable();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('question_type', 32)->default('mixed');
            $table->string('difficulty', 32)->default('medium');
            $table->unsignedInteger('total_questions')->default(0);
            $table->string('status', 32)->default('draft');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'question_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_sets');
    }
};
