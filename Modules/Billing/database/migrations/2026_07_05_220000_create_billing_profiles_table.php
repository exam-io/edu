<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('billing_profiles')) {
            return;
        }

        Schema::create('billing_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('legal_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('country', 80)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('address_line')->nullable();
            $table->string('postal_code', 30)->nullable();
            $table->string('tax_id', 80)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_profiles');
    }
};
