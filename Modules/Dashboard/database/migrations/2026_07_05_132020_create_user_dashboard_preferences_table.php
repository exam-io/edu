<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_dashboard_preferences')) {
            return;
        }

        Schema::create('user_dashboard_preferences', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dashboard_definition_id')->nullable()->constrained('dashboard_definitions')->nullOnDelete();
            $table->json('preferences')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'user_id'], 'tenant_user_dashboard_pref_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_dashboard_preferences');
    }
};
