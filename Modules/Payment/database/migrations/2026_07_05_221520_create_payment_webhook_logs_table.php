<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('payment_webhook_logs')) {
            return;
        }

        Schema::create('payment_webhook_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('provider', 40);
            $table->string('event_key', 120);
            $table->json('payload');
            $table->string('status', 40)->default('queued');
            $table->timestamp('processed_at')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'provider'], 'pay_webhook_tenant_provider_idx');
            $table->index(['tenant_id', 'status'], 'pay_webhook_tenant_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_webhook_logs');
    }
};
