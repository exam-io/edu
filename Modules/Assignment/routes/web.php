<?php

use Illuminate\Support\Facades\Route;
use Modules\Assignment\Http\Controllers\AssignmentController;

Route::middleware(['auth', 'verified'])->group(function () {
    // API-only module.
});
