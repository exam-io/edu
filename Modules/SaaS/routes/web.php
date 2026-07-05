<?php

use Illuminate\Support\Facades\Route;
use Modules\SaaS\Http\Controllers\SaaSController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('saas', SaaSController::class)->names('saas');
});
