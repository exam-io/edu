<?php

use Illuminate\Support\Facades\Route;
use Modules\Student\Http\Controllers\StudentController;

Route::middleware(['tenant', 'auth:sanctum'])->group(function (): void {
    Route::get('students', [StudentController::class, 'index'])->middleware('identity.permission:student.view');
    Route::post('students', [StudentController::class, 'store'])->middleware('identity.permission:student.create');
    Route::get('students/{id}', [StudentController::class, 'show'])->middleware('identity.permission:student.view');
    Route::put('students/{id}', [StudentController::class, 'update'])->middleware('identity.permission:student.update');
    Route::delete('students/{id}', [StudentController::class, 'destroy'])->middleware('identity.permission:student.delete');
});
