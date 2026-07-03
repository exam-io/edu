<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['tenant'])->group(function (): void {
    Route::get('/health', function (Request $request) {
        $tenant = app(\App\Support\Tenancy\Contracts\TenantContextInterface::class)->tenant();

        return response()->json([
            'status' => 'ok',
            'tenant' => $tenant?->slug,
            'timestamp' => now()->toIso8601String(),
        ]);
    });

    Route::get('/openapi', function () {
        return response()->json([
            'spec_url' => url('/api/v1/openapi.yaml'),
            'format' => 'yaml',
            'version' => '1.0.0',
        ]);
    });

    Route::get('/openapi.yaml', function () {
        return response()->file(base_path('openapi/openapi.yaml'), [
            'Content-Type' => 'application/yaml; charset=UTF-8',
        ]);
    });
});
