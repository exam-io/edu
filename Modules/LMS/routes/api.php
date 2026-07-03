<?php

use Illuminate\Support\Facades\Route;
use Modules\LMS\Http\Controllers\CourseEnrollmentController;
use Modules\LMS\Http\Controllers\LearningProgressController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('lms/enrollments', [CourseEnrollmentController::class, 'index'])->middleware('identity.permission:lms.enrollment.view');
    Route::post('lms/enrollments', [CourseEnrollmentController::class, 'store'])->middleware('identity.permission:lms.enrollment.create');
    Route::get('lms/enrollments/{id}', [CourseEnrollmentController::class, 'show'])->middleware('identity.permission:lms.enrollment.view');
    Route::put('lms/enrollments/{id}', [CourseEnrollmentController::class, 'update'])->middleware('identity.permission:lms.enrollment.update');
    Route::delete('lms/enrollments/{id}', [CourseEnrollmentController::class, 'destroy'])->middleware('identity.permission:lms.enrollment.delete');

    Route::get('lms/progress', [LearningProgressController::class, 'index'])->middleware('identity.permission:lms.progress.view');
    Route::post('lms/progress', [LearningProgressController::class, 'store'])->middleware('identity.permission:lms.progress.create');
    Route::get('lms/progress/{id}', [LearningProgressController::class, 'show'])->middleware('identity.permission:lms.progress.view');
    Route::put('lms/progress/{id}', [LearningProgressController::class, 'update'])->middleware('identity.permission:lms.progress.update');
    Route::delete('lms/progress/{id}', [LearningProgressController::class, 'destroy'])->middleware('identity.permission:lms.progress.delete');
});
