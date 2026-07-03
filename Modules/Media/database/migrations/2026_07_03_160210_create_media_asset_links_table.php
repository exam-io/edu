<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_asset_links', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('media_asset_id')->constrained('media_assets')->cascadeOnDelete();
            $table->string('link_type', 64);
            $table->unsignedBigInteger('link_id');
            $table->timestamps();

            $table->index(['tenant_id', 'link_type', 'link_id']);
            $table->index(['tenant_id', 'media_asset_id']);
            $table->unique(['tenant_id', 'media_asset_id', 'link_type', 'link_id'], 'media_asset_unique_link');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_asset_links');
    }
};