<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('relationship', 60);
            $table->string('phone', 40);
            $table->string('email')->nullable();
            $table->string('occupation')->nullable();
            $table->text('address')->nullable();
            $table->string('status', 32)->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('phone');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
