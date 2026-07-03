<?php

use Illuminate\Support\Facades\Route;
use Modules\Shared\Http\Controllers\SharedController;

Route::middleware(['tenant', 'auth', 'verified'])->group(function () {
    Route::resource('shareds', SharedController::class)->names('shared');
});
