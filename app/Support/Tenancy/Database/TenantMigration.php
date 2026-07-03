<?php

namespace App\Support\Tenancy\Database;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

abstract class TenantMigration extends Migration
{
    protected function addTenantColumn(Blueprint $table, bool $nullable = false): void
    {
        $column = $table->foreignId('tenant_id');

        if ($nullable) {
            $column->nullable();
        }

        $column->constrained('tenants')->cascadeOnDelete();
        $table->index('tenant_id');
    }
}
