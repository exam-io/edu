<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('subscription_plans')) {
            return;
        }

        Schema::create('subscription_plans', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('name', 120);
            $table->string('description', 400)->nullable();
            $table->string('billing_interval', 30)->default('monthly');
            $table->decimal('price_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->json('quota')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'billing_interval'], 'sub_plan_active_interval_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
