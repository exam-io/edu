<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('payment_transactions')) {
            return;
        }

        Schema::create('payment_transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('payment_intent_id')->constrained('payment_intents')->cascadeOnDelete();
            $table->string('provider', 40);
            $table->string('provider_transaction_id', 120);
            $table->string('status', 40)->default('pending');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->timestamp('processed_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status'], 'pay_txn_tenant_status_idx');
            $table->index(['tenant_id', 'provider'], 'pay_txn_tenant_provider_idx');
            $table->unique(['provider', 'provider_transaction_id'], 'pay_txn_provider_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
