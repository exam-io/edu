<?php

use Illuminate\Support\Facades\Route;
use Modules\Calendar\Http\Controllers\CalendarController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('calendar/events', [CalendarController::class, 'index'])->middleware('identity.permission:calendar.view');
    Route::post('calendar/events', [CalendarController::class, 'store'])->middleware('identity.permission:calendar.create');
    Route::get('calendar/events/{id}', [CalendarController::class, 'show'])->middleware('identity.permission:calendar.view');
    Route::put('calendar/events/{id}', [CalendarController::class, 'update'])->middleware('identity.permission:calendar.update');
    Route::delete('calendar/events/{id}', [CalendarController::class, 'destroy'])->middleware('identity.permission:calendar.delete');
});
