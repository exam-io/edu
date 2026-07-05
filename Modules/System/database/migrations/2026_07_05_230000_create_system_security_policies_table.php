<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('system_security_policies')) {
            return;
        }

        Schema::create('system_security_policies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->boolean('force_mfa')->default(false);
            $table->unsignedInteger('session_ttl_minutes')->default(120);
            $table->unsignedInteger('password_rotation_days')->default(90);
            $table->boolean('allow_ip_restriction')->default(false);
            $table->json('allowed_ip_ranges')->nullable();
            $table->boolean('strict_transport_security')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('tenant_id', 'sys_sec_policy_tenant_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_security_policies');
    }
};
