<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('analytics_events')) {
            return;
        }

        Schema::create('analytics_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('event_name', 120);
            $table->string('source_module', 80);
            $table->string('entity_type', 120)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('occurred_at');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'event_name', 'occurred_at']);
            $table->index(['tenant_id', 'source_module', 'occurred_at']);
            $table->index(['tenant_id', 'entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
