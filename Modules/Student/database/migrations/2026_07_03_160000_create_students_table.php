<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('admission_no', 80);
            $table->string('roll_no', 80)->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('gender', 32);
            $table->date('date_of_birth');
            $table->string('blood_group', 16)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('emergency_contact', 100)->nullable();
            $table->date('admission_date');
            $table->string('status', 32)->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('admission_no');
            $table->index('roll_no');
            $table->index('status');
            $table->unique(['tenant_id', 'admission_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
