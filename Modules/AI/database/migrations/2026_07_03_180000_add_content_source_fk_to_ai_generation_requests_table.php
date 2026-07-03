<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ai_generation_requests') || ! Schema::hasTable('content_sources')) {
            return;
        }

        Schema::table('ai_generation_requests', function (Blueprint $table): void {
            $table->foreign('content_source_id', 'ai_req_content_source_fk')
                ->references('id')
                ->on('content_sources')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ai_generation_requests')) {
            return;
        }

        Schema::table('ai_generation_requests', function (Blueprint $table): void {
            $table->dropForeign('ai_req_content_source_fk');
        });
    }
};
