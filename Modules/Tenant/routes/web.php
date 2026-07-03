<?php

use Illuminate\Support\Facades\Route;

Route::middleware([])->group(function (): void {
    // Intentionally empty: Tenant module is API-only.
});
