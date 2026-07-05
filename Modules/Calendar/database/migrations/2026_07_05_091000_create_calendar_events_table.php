<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('calendar_events')) {
            return;
        }

        Schema::create('calendar_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('event_type', 64)->default('general');
            $table->string('status', 32)->default('scheduled');
            $table->string('source_type', 120)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('url', 500)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'start_at', 'end_at']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'event_type']);
            $table->unique(['tenant_id', 'source_type', 'source_id'], 'calendar_event_source_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
