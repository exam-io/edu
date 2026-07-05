<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('crm_leads')) {
            Schema::create('crm_leads', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->string('first_name', 100);
                $table->string('last_name', 100)->nullable();
                $table->string('email', 190);
                $table->string('phone', 50)->nullable();
                $table->string('source', 50)->default('website');
                $table->string('status', 40)->default('new');
                $table->string('interest', 255)->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('converted_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'source']);
                $table->index(['tenant_id', 'email']);
            });
        }

        if (! Schema::hasTable('crm_lead_activities')) {
            Schema::create('crm_lead_activities', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->foreignId('lead_id')->constrained('crm_leads')->cascadeOnDelete();
                $table->string('activity_type', 50);
                $table->text('message');
                $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['tenant_id', 'lead_id']);
                $table->index(['tenant_id', 'activity_type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_lead_activities');
        Schema::dropIfExists('crm_leads');
    }
};
