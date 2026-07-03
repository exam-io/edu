<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

Route::middleware(['tenant', 'auth', 'verified'])->group(function () {
    Route::resource('settings', SettingsController::class)->names('settings');
});
