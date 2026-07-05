<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dashboard_widgets')) {
            return;
        }

        Schema::create('dashboard_widgets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('dashboard_definition_id')->constrained('dashboard_definitions')->cascadeOnDelete();
            $table->string('widget_key', 120);
            $table->string('title', 180);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->json('config')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'dashboard_definition_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_widgets');
    }
};
