<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('assignment_submissions')) {
            return;
        }

        $duplicates = DB::table('assignment_submissions')
            ->select([
                'tenant_id',
                'assessment_id',
                'student_id',
                DB::raw('MAX(id) as keep_id'),
            ])
            ->groupBy('tenant_id', 'assessment_id', 'student_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('assignment_submissions')
                ->where('tenant_id', $duplicate->tenant_id)
                ->where('assessment_id', $duplicate->assessment_id)
                ->where('student_id', $duplicate->student_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('assignment_submissions', function (Blueprint $table): void {
            $table->unique(
                ['tenant_id', 'assessment_id', 'student_id'],
                'assignment_submissions_tenant_assessment_student_unique'
            );
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('assignment_submissions')) {
            return;
        }

        Schema::table('assignment_submissions', function (Blueprint $table): void {
            $table->dropUnique('assignment_submissions_tenant_assessment_student_unique');
        });
    }
};
