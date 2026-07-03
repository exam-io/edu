<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('employee_no', 80);
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('gender', 32);
            $table->string('phone', 40)->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->date('joining_date');
            $table->unsignedInteger('experience_years')->nullable();
            $table->string('status', 32)->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('employee_no');
            $table->index('status');
            $table->unique(['tenant_id', 'employee_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
