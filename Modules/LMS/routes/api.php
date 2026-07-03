<?php

use Illuminate\Support\Facades\Route;
use Modules\LMS\Http\Controllers\LMSController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('lms', LMSController::class)->names('lms');
});
