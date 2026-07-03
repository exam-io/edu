<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('student_parents')) {
            return;
        }

        // Repair logic is only required for MySQL partial-runs where metadata exists in information_schema.
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        $hasParentForeign = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'student_parents')
            ->where('COLUMN_NAME', 'parent_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->exists();

        $hasUnique = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'student_parents')
            ->where('INDEX_NAME', 'student_parents_tenant_student_parent_unique')
            ->exists();

        Schema::table('student_parents', function (Blueprint $table) use ($hasParentForeign, $hasUnique): void {
            if (! $hasParentForeign) {
                $table->foreign('parent_id')->references('id')->on('parents')->cascadeOnDelete();
            }

            if (! $hasUnique) {
                $table->unique(['tenant_id', 'student_id', 'parent_id'], 'student_parents_tenant_student_parent_unique');
            }
        });
    }

    public function down(): void
    {
        // This is a repair migration for partial-run states.
        // Rollback is intentionally a no-op because base table rollback handles teardown.
        return;
    }
};
