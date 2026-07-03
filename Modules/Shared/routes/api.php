<?php

use Illuminate\Support\Facades\Route;
use Modules\Shared\Http\Controllers\SharedController;

Route::middleware(['tenant', 'auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('shareds', SharedController::class)->names('shared');
});
