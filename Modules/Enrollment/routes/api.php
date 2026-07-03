<?php

use Illuminate\Support\Facades\Route;
use Modules\Enrollment\Http\Controllers\EnrollmentController;
use Modules\Enrollment\Http\Controllers\TeacherAssignmentController;

Route::middleware(['tenant', 'auth:sanctum'])->group(function (): void {
    Route::get('enrollments', [EnrollmentController::class, 'index'])->middleware('identity.permission:enrollment.view');
    Route::post('enrollments', [EnrollmentController::class, 'store'])->middleware('identity.permission:enrollment.create');
    Route::get('enrollments/{id}', [EnrollmentController::class, 'show'])->middleware('identity.permission:enrollment.view');
    Route::put('enrollments/{id}', [EnrollmentController::class, 'update'])->middleware('identity.permission:enrollment.update');
    Route::delete('enrollments/{id}', [EnrollmentController::class, 'destroy'])->middleware('identity.permission:enrollment.delete');

    Route::get('teacher-assignments', [TeacherAssignmentController::class, 'index'])->middleware('identity.permission:teacher-assignment.view');
    Route::post('teacher-assignments', [TeacherAssignmentController::class, 'store'])->middleware('identity.permission:teacher-assignment.create');
    Route::get('teacher-assignments/{id}', [TeacherAssignmentController::class, 'show'])->middleware('identity.permission:teacher-assignment.view');
    Route::put('teacher-assignments/{id}', [TeacherAssignmentController::class, 'update'])->middleware('identity.permission:teacher-assignment.update');
    Route::delete('teacher-assignments/{id}', [TeacherAssignmentController::class, 'destroy'])->middleware('identity.permission:teacher-assignment.delete');
});
