<?php

use Illuminate\Support\Facades\Route;
use Modules\Institute\Http\Controllers\InstituteController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('institutes', InstituteController::class)->names('institute');
});
