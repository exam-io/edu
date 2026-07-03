<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('questions')) {
            return;
        }

        Schema::create('questions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('question_set_id')->constrained('question_sets')->cascadeOnDelete();
            $table->text('stem');
            $table->string('question_type', 32)->default('short_answer');
            $table->string('difficulty', 32)->default('medium');
            $table->json('options')->nullable();
            $table->json('correct_answer')->nullable();
            $table->text('explanation')->nullable();
            $table->unsignedInteger('sort_order')->default(1);
            $table->string('status', 32)->default('active');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'question_set_id']);
            $table->index(['tenant_id', 'question_type']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
