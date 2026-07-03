<?php

use Illuminate\Support\Facades\Route;
use Modules\Teacher\Http\Controllers\TeacherController;

Route::middleware(['tenant', 'auth:sanctum'])->group(function (): void {
    Route::get('teachers', [TeacherController::class, 'index'])->middleware('identity.permission:teacher.view');
    Route::post('teachers', [TeacherController::class, 'store'])->middleware('identity.permission:teacher.create');
    Route::get('teachers/{id}', [TeacherController::class, 'show'])->middleware('identity.permission:teacher.view');
    Route::put('teachers/{id}', [TeacherController::class, 'update'])->middleware('identity.permission:teacher.update');
    Route::delete('teachers/{id}', [TeacherController::class, 'destroy'])->middleware('identity.permission:teacher.delete');
});
