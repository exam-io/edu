<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admission_workflows')) {
            Schema::create('admission_workflows', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->string('name', 120);
                $table->json('stages')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id', 'is_default']);
            });
        }

        if (! Schema::hasTable('admission_applications')) {
            Schema::create('admission_applications', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->foreignId('lead_id')->nullable()->constrained('crm_leads')->nullOnDelete();
                $table->foreignId('workflow_id')->nullable()->constrained('admission_workflows')->nullOnDelete();
                $table->string('first_name', 100);
                $table->string('last_name', 100)->nullable();
                $table->string('email', 190);
                $table->string('phone', 50)->nullable();
                $table->string('program', 190)->nullable();
                $table->string('source', 100)->nullable();
                $table->string('status', 40)->default('submitted');
                $table->text('notes')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'program']);
                $table->index(['tenant_id', 'email']);
            });
        }

        if (! Schema::hasTable('admission_application_status_histories')) {
            Schema::create('admission_application_status_histories', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
                $table->foreignId('admission_application_id')->constrained('admission_applications')->cascadeOnDelete();
                $table->string('from_status', 40)->nullable();
                $table->string('to_status', 40);
                $table->string('note', 1000)->nullable();
                $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['tenant_id', 'admission_application_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_application_status_histories');
        Schema::dropIfExists('admission_applications');
        Schema::dropIfExists('admission_workflows');
    }
};
