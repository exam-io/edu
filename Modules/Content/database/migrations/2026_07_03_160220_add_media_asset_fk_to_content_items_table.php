<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('content_items') || ! Schema::hasTable('media_assets')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $database = DB::getDatabaseName();
        $foreignExists = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', 'content_items')
            ->where('COLUMN_NAME', 'media_asset_id')
            ->where('REFERENCED_TABLE_NAME', 'media_assets')
            ->exists();

        if ($foreignExists) {
            return;
        }

        Schema::table('content_items', function (Blueprint $table): void {
            $table->foreign('media_asset_id')
                ->references('id')
                ->on('media_assets')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('content_items')) {
            return;
        }

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $database = DB::getDatabaseName();
        $foreignExists = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', 'content_items')
            ->where('COLUMN_NAME', 'media_asset_id')
            ->where('REFERENCED_TABLE_NAME', 'media_assets')
            ->exists();

        if (! $foreignExists) {
            return;
        }

        Schema::table('content_items', function (Blueprint $table): void {
            $table->dropForeign(['media_asset_id']);
        });
    }
};
