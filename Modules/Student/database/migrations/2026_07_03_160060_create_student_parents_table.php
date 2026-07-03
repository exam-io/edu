<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('student_parents')) {
            return;
        }

        Schema::create('student_parents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('parent_id')->constrained('parents')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('student_id');
            $table->index('parent_id');
            $table->unique(['tenant_id', 'student_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_parents');
    }
};
