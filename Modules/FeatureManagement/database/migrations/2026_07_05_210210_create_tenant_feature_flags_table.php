<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tenant_feature_flags')) {
            return;
        }

        Schema::create('tenant_feature_flags', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('feature_key', 120);
            $table->boolean('enabled')->default(false);
            $table->string('source', 40)->default('manual');
            $table->timestamps();

            $table->unique(['tenant_id', 'feature_key']);
            $table->index(['tenant_id', 'enabled']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_feature_flags');
    }
};
