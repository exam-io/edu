<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('campaigns')) {
            Schema::create('campaigns', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->string('name', 190);
                $table->string('campaign_type', 50)->default('broadcast');
                $table->string('subject', 190);
                $table->text('message');
                $table->json('channels');
                $table->string('status', 40)->default('draft');
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('launched_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'campaign_type']);
            });
        }

        if (! Schema::hasTable('campaign_recipients')) {
            Schema::create('campaign_recipients', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('status', 40)->default('pending');
                $table->timestamps();

                $table->unique(['tenant_id', 'campaign_id', 'user_id'], 'campaign_recipients_unique');
                $table->index(['tenant_id', 'campaign_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_recipients');
        Schema::dropIfExists('campaigns');
    }
};
