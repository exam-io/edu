<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('assignment_submissions')) {
            return;
        }

        Schema::table('assignment_submissions', function (Blueprint $table): void {
            if (! Schema::hasColumn('assignment_submissions', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('assignment_submissions')) {
            return;
        }

        Schema::table('assignment_submissions', function (Blueprint $table): void {
            if (Schema::hasColumn('assignment_submissions', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
