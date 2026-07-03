<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

Route::middleware(['tenant', 'auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('settings', SettingsController::class)->names('settings');
});
