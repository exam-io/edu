<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionInfrastructureSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->call([
            RoleSeeder::class,
            AcademicPermissionSeeder::class,
            UserManagementPermissionSeeder::class,
            CourseLmsPermissionSeeder::class,
            AIContentEnginePermissionSeeder::class,
            AssessmentExamPermissionSeeder::class,
            LiveClassCalendarNotificationPermissionSeeder::class,
            AnalyticsReportingBiPermissionSeeder::class,
            ProductionHardeningPermissionSeeder::class,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
