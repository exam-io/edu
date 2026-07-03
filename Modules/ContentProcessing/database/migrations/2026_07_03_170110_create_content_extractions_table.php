<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('content_extractions')) {
            return;
        }

        Schema::create('content_extractions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('content_source_id')->constrained('content_sources')->cascadeOnDelete();
            $table->string('status', 32);
            $table->longText('extracted_text')->nullable();
            $table->unsignedInteger('word_count')->default(0);
            $table->string('error_message', 1000)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'content_source_id']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_extractions');
    }
};
