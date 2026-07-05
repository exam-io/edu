<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dashboard_definitions')) {
            return;
        }

        Schema::create('dashboard_definitions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name', 180);
            $table->string('role_key', 120);
            $table->boolean('is_default')->default(false);
            $table->json('layout')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'role_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_definitions');
    }
};
