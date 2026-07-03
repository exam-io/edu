<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            // Add columns if they don't exist
            if (!Schema::hasColumn('tenants', 'custom_domain')) {
                $table->string('custom_domain')->nullable()->unique()->after('domain');
            }

            if (!Schema::hasColumn('tenants', 'plan')) {
                $table->string('plan')->default('free')->after('status');
            }

            if (!Schema::hasColumn('tenants', 'provisioned_at')) {
                $table->timestamp('provisioned_at')->nullable()->after('plan');
            }

            if (!Schema::hasColumn('tenants', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('provisioned_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            $table->dropUnique(['custom_domain']);
            $table->dropColumn([
                'custom_domain',
                'plan',
                'provisioned_at',
                'suspended_at',
            ]);
        });
    }
};
