<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('usage_snapshots')) {
            return;
        }

        Schema::create('usage_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->date('snapshot_date');
            $table->json('metrics')->nullable();
            $table->decimal('mrr', 12, 2)->default(0);
            $table->decimal('arr', 12, 2)->default(0);
            $table->unsignedInteger('active_subscribers')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'snapshot_date'], 'usage_snapshot_tenant_date_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_snapshots');
    }
};
