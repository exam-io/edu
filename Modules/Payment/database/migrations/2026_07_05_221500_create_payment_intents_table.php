<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('payment_intents')) {
            return;
        }

        Schema::create('payment_intents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->string('provider', 40);
            $table->string('provider_intent_id', 120);
            $table->string('currency', 3)->default('USD');
            $table->decimal('amount', 12, 2);
            $table->string('status', 40)->default('requires_capture');
            $table->string('client_secret')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'provider'], 'pay_intent_tenant_provider_idx');
            $table->index(['tenant_id', 'status'], 'pay_intent_tenant_status_idx');
            $table->unique(['provider', 'provider_intent_id'], 'pay_intent_provider_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_intents');
    }
};
