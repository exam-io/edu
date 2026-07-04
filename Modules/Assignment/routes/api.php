<?php

use Illuminate\Support\Facades\Route;
use Modules\Assignment\Http\Controllers\AssignmentSubmissionController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::post('assignments/{id}/submit', [AssignmentSubmissionController::class, 'submit'])->middleware('identity.permission:assignment.submit');
    Route::get('assignments/submissions', [AssignmentSubmissionController::class, 'index'])->middleware('identity.permission:submission.view');
});
