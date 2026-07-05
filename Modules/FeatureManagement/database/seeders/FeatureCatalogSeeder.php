<?php

namespace Modules\FeatureManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\FeatureManagement\Domain\Models\FeatureCatalog;

class FeatureCatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('featuremanagement.default_features', []) as $feature) {
            FeatureCatalog::query()->updateOrCreate(
                ['key' => (string) $feature['key']],
                [
                    'name' => (string) $feature['name'],
                    'enabled_by_default' => (bool) ($feature['enabled_by_default'] ?? false),
                ],
            );
        }
    }
}
