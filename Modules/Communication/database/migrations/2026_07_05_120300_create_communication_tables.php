<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->string('title', 190);
                $table->text('body');
                $table->json('target_user_ids');
                $table->json('channels');
                $table->string('status', 40)->default('draft');
                $table->timestamp('publish_at')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id', 'status']);
            });
        }

        if (! Schema::hasTable('communication_histories')) {
            Schema::create('communication_histories', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->string('source_type', 50);
                $table->unsignedBigInteger('source_id');
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('channel', 20);
                $table->string('subject', 190);
                $table->text('content');
                $table->string('status', 40)->default('sent');
                $table->json('provider_response')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();

                $table->index(['tenant_id', 'source_type', 'source_id']);
                $table->index(['tenant_id', 'user_id']);
                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'channel']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('communication_histories');
        Schema::dropIfExists('announcements');
    }
};
