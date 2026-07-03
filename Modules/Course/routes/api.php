<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\Http\Controllers\CourseController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('courses', [CourseController::class, 'index'])->middleware('identity.permission:course.view');
    Route::post('courses', [CourseController::class, 'store'])->middleware('identity.permission:course.create');
    Route::get('courses/{id}', [CourseController::class, 'show'])->middleware('identity.permission:course.view');
    Route::put('courses/{id}', [CourseController::class, 'update'])->middleware('identity.permission:course.update');
    Route::delete('courses/{id}', [CourseController::class, 'destroy'])->middleware('identity.permission:course.delete');
});
