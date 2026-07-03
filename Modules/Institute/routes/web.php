<?php

use Illuminate\Support\Facades\Route;
use Modules\Institute\Http\Controllers\InstituteController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('institutes', InstituteController::class)->names('institute');
});
